<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google\AdsApi\AdManager\AdManagerSession;
use Google\AdsApi\AdManager\AdManagerSessionBuilder;
use Google\AdsApi\AdManager\Util\v202405\ReportDownloader;
use Google\AdsApi\AdManager\Util\v202405\StatementBuilder;
use Google\AdsApi\AdManager\v202405\Column;
use Google\AdsApi\AdManager\v202405\DateRangeType;
use Google\AdsApi\AdManager\v202405\Dimension;
use Google\AdsApi\AdManager\v202405\ExportFormat;
use Google\AdsApi\AdManager\v202405\ReportJob;
use Google\AdsApi\AdManager\v202405\ReportQuery;
use Google\AdsApi\AdManager\v202405\ReportQueryAdUnitView;
use Google\AdsApi\AdManager\v202405\ServiceFactory;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\AdsApi\AdManager\Util\v202405\AdManagerDateTimes;
use App\Models\AdunitReport;

class GenerateAdunitReport extends Command
{
    protected $signature = 'report:generate {--range=yesterday}';
    protected $description = 'Generate ad unit reports for a specified date range';

    private function getDateRange($type) {
        $now = new \DateTime('now', new \DateTimeZone('America/New_York'));
        
        switch ($type) {
            case 'yesterday':
                $startDate = (clone $now)->modify('-1 day')->format('Ymd');
                $endDate = (clone $now)->modify('-1 day')->format('Ymd');
                break;
            case 'thisMonth':
                $startDate = (clone $now)->modify('first day of this month')->format('Ymd');
                if ($now->format('j') === '1') {
                    $endDate = (clone $now)->modify('+1 day')->format('Ymd');
                } else {
                    $endDate = $now->format('Ymd');
                }
                
                break;
            case 'previousMonth':
                $startDate = (clone $now)->modify('first day of last month')->format('Ymd');
                $endDate = (clone $now)->modify('last day of last month')->format('Ymd');
                break;
            default:
                throw new \InvalidArgumentException("Invalid date range type.");
        }
        
        return [$startDate, $endDate];
    }
    
    private function runReport($serviceFactory, AdManagerSession $session, $startDate, $endDate) {
        $reportService = $serviceFactory->createReportService($session);
        
        // Create statement to filter on a specific ad unit ID.
        $adUnitId = '23195943922'; 
        $statementBuilder = (new StatementBuilder())
            ->where('AD_UNIT_ID = :adUnitId')
            ->withBindVariableValue('adUnitId', intval($adUnitId));
        
        // Create report query.
        $reportQuery = new ReportQuery();
        $reportQuery->setDimensions([
            Dimension::AD_UNIT_ID,
            Dimension::AD_UNIT_NAME,
            Dimension::DATE,

        ]);
        $reportQuery->setColumns([
            // Column::AD_SERVER_IMPRESSIONS,
            // Column::AD_SERVER_CLICKS,
            Column::TOTAL_LINE_ITEM_LEVEL_IMPRESSIONS,
            Column::TOTAL_LINE_ITEM_LEVEL_CPM_AND_CPC_REVENUE
        ]);
        $reportQuery->setStatement($statementBuilder->toStatement());
        $reportQuery->setAdUnitView(ReportQueryAdUnitView::HIERARCHICAL);
        $reportQuery->setDateRangeType(DateRangeType::CUSTOM_DATE);
        $reportQuery->setStartDate(AdManagerDateTimes::fromDateTime(new \DateTime($startDate))->getDate());
        $reportQuery->setEndDate(AdManagerDateTimes::fromDateTime(new \DateTime($endDate))->getDate());
        
        // Create report job and start it.
        $reportJob = new ReportJob();
        $reportJob->setReportQuery($reportQuery);
        $reportJob = $reportService->runReportJob($reportJob);
        
        // Create report downloader to poll report's status and download when ready.
        $reportDownloader = new ReportDownloader($reportService, $reportJob->getId());
        $reportContent = $reportDownloader->waitForReportToFinish();
        
        if ($reportDownloader->waitForReportToFinish()) {
            // Write to system temp directory by default.
            $filePath = sprintf('%s.csv.gz', tempnam(sys_get_temp_dir(), 'inventory-report-'));
            $this->info("Downloading report to $filePath ...");
            $reportDownloader->downloadReport(ExportFormat::CSV_DUMP, $filePath);
            
            $csvRows = [];
            if ($handle = gzopen($filePath, 'rb')) {
                while (!feof($handle)) {
                    $csvRows[] = gzgets($handle, 4096);
                }
                gzclose($handle);
            }
            
            // Skip header row
            if (!empty($csvRows)) {
                unset($csvRows[0]); // Skip header row
            }
            
            // Process each row in the report and save to database
            foreach ($csvRows as $csvRow) {
                $row = str_getcsv($csvRow);
                $adname= $row[1];
                $date = $row[2]; // Date
                $impressions = $row[3]; // Impressions
                $revenue = $row[4] / 1000000;
                $totalRevenue = round($revenue, 2);

                // Save data to database


                AdunitReport::updateOrCreate(
                    [
                        'ad_unit_id' => $adUnitId,
                        'ad_unit_name' => $adname,
                        'date' => $date,
                    ],
                    [
                        'impressions' => $impressions,
                        'revenue' => $totalRevenue
                    ]
                );
            }
            $this->info("done.");
        } else {
            $this->error("Report failed.");
        }
    }

    public function handle()
    {
        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile()->build();
        $session = (new AdManagerSessionBuilder())->fromFile()->withOAuth2Credential($oAuth2Credential)->build();
        $adManagerServices = new ServiceFactory();
        
        $dateRangeType = $this->option('range');
        $dateRanges = [$dateRangeType];
        
        foreach ($dateRanges as $range) {
            list($startDate, $endDate) = $this->getDateRange($range);
            $this->info("\nFetching data for $range (From $startDate To $endDate):");
            $this->runReport($adManagerServices, $session, $startDate, $endDate);
        }
    }
}
