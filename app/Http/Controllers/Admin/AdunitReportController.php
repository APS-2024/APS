<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
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
use App\Models\RevenueDeducation;
use DateTime;
use DateTimeZone;
use App\Http\Controllers\Controller;
use Flasher\Prime\FlasherInterface;
use Google\AdsApi\AdManager\AdManagerServices;
use Illuminate\Support\Facades\DB;



class AdunitReportController extends Controller
{
    public function generateReport(Request $request,FlasherInterface $flasher)
    {
        // Retrieve date range from request or use default
        $range = $request->input('range', 'yesterday');

        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile()->build();

        // Initialize the Ad Manager session.
        $session = (new AdManagerSessionBuilder())->fromFile()
            ->withOAuth2Credential($oAuth2Credential)
            ->build();

        // Create a ServiceFactory.
        $serviceFactory = new ServiceFactory();

        // Run the report
        $this->runReport($serviceFactory, $session);


    
        $flasher->success('This is a test message.');
    
        return redirect()->route('admin.dashboard');
       // return response()->json(['message' => 'Report generated successfully','redirect' => route('admin.dashboard')]);
    }

    private function runReport($serviceFactory, AdManagerSession $session)
    {
        $reportService = $serviceFactory->createReportService($session);

        $statementBuilder = (new StatementBuilder());

        $reportQuery = new ReportQuery();
         $reportQuery->setDimensions([
            Dimension::AD_UNIT_ID,
            Dimension::AD_UNIT_NAME,
            Dimension::DATE,
        ]);
        $reportQuery->setColumns([
            Column::TOTAL_LINE_ITEM_LEVEL_IMPRESSIONS,
            Column::TOTAL_LINE_ITEM_LEVEL_CPM_AND_CPC_REVENUE,
            Column::TOTAL_LINE_ITEM_LEVEL_CLICKS,
            Column::TOTAL_LINE_ITEM_LEVEL_WITHOUT_CPD_AVERAGE_ECPM
        ]);

        $reportQuery->setStatement($statementBuilder->toStatement());
        $reportQuery->setAdUnitView(ReportQueryAdUnitView::HIERARCHICAL);
        $reportQuery->setDateRangeType(DateRangeType::CUSTOM_DATE);
        $reportQuery->setStartDate(
            AdManagerDateTimes::fromDateTime(
                new DateTime('-90 days', new DateTimeZone('America/New_York'))
            )->getDate()
        );
        $reportQuery->setEndDate(
            AdManagerDateTimes::fromDateTime(
                new DateTime('now', new DateTimeZone('America/New_York'))
            )->getDate()
        );

        $reportJob = new ReportJob();
        $reportJob->setReportQuery($reportQuery);
        $reportJob = $reportService->runReportJob($reportJob);

        $reportDownloader = new ReportDownloader(
            $reportService,
            $reportJob->getId()
        );

    


            if ($reportDownloader->waitForReportToFinish()) {
                $directory = storage_path('app/reports'); // Specify your directory
                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                $filePath = sprintf('%s/%s.csv.gz', $directory, uniqid('inventory-report-', true));
                \Log::info("Downloading report to $filePath ...");


                
            $reportDownloader->downloadReport(ExportFormat::CSV_DUMP, $filePath);

            $csvRows = [];
            if ($handle = gzopen($filePath, 'rb')) {
                while (!feof($handle)) {
                    $csvRows[] = gzgets($handle, 4096);
                }
                gzclose($handle);
            }

            if (!empty($csvRows)) {
                unset($csvRows[0]); // Skip header row
            }
$adManagerServices = new AdManagerServices();
            $inventoryService = $serviceFactory->createInventoryService($session);
            foreach ($csvRows as $csvRow) {
                $row = str_getcsv($csvRow);
                $adUnitId = $row[0];
                $adname = $row[1];
                $date = $row[2];
                $impressions = $row[3];
                $revenue = $row[4] / 1000000;
                $totalRevenue = round($revenue, 2);
                $clicks= $row[5];
                $ecpm= $row[6]/1000000;
                $totalecpm = round($ecpm, 2);

                AdunitReport::updateOrCreate(
                    [
                        'ad_unit_id' => $adUnitId,
                        'ad_unit_name' => $adname,
                        'date' => $date,
                    ],
                    [
                        'impressions' => $impressions,
                        'revenue' => $totalRevenue,
                          'clicks' => $clicks,
                        'ecpm' => $totalecpm
                    ]
                );
            }
            \Log::info("done.");
        } else {
            \Log::error("Report failed.");
        }
    }
    
    
    
   
public function summaryUser(){


    $user= auth()->user()->ad_unit_id;
   
    $unit=explode(',',$user);
    $months = AdunitReport::select(DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),'ad_unit_id')
    ->whereIn('ad_unit_id', $unit)   // Filter records by ad_unit_id
    ->groupBy(DB::raw('DATE_FORMAT(date, "%Y-%m")'),'ad_unit_id') // Group by formatted month only
    ->get();

    return view('admin.adUnit.summary',compact('months'));

}

public function summary(){

 
    
    $months = AdunitReport::select(DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),'ad_unit_id')->groupBy(DB::raw('DATE_FORMAT(date, "%Y-%m")'), 'ad_unit_id')->get();
    return view('admin.adUnit.summary-admin',compact('months'));

}

public function updateRvenue(Request $request){


    $date = $request->input('month');
    $ad_unit_id = $request->input('unitid');
    $deduct = $request->input('deduct');
    $final_revenue = $request->input('finalrevenue');

    // Check if a record with the same date and ad_unit_id exists
    $revenue = RevenueDeducation::where('date', $date)
                ->where('ad_unit_id', $ad_unit_id)
                ->first();

    if ($revenue) {
        // If record exists, update it
        $revenue->deducation = $deduct;
        $revenue->final_revenue = $final_revenue;
        $revenue->save();
    } else {
        // If no record exists, create a new one

        $revenue = new RevenueDeducation();
        $revenue->date = $date;
        $revenue->ad_unit_id = $ad_unit_id;
        $revenue->deducation = $deduct;
        $revenue->final_revenue = $final_revenue;
        $revenue->save();
    }

    // Save the record (whether updated or new)
   

    return response()->json([
        'success' => true,
        'message' => 'Revenue updated successfully.',
    ]);

} 
    
    
   public function updateStatus(Request $request){

    $date = $request->input('date');
    $ad_unit_id = $request->input('unitid');

    $revenue = RevenueDeducation::where('date', $date)
    ->where('ad_unit_id', $ad_unit_id)
    ->first();

    if ($revenue) {
        // If record exists, update it
        $revenue->status = 'Final';
        $revenue->save();

    }
    return response()->json([
        'success' => true,
        'message' => 'Status Update Successfull.',
    ]);


} 
    
    
    
    
}
