<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Google\AdsApi\AdManager\AdManagerSession;
use Google\AdsApi\AdManager\AdManagerSessionBuilder;
use Google\AdsApi\AdManager\Util\v202405\StatementBuilder;
use Google\AdsApi\AdManager\v202405\ServiceFactory;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use App\Models\AdunitReport;
use App\Models\Admin\Adunitpercen;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdUnitController extends Controller
{
    public function index()
    {
        
    
        return view('admin.adUnit.index');
    }
    
   public function view($unit_id){

    $userName = auth()->user()->name;
    $message = 'Welcome ' . $userName . '!';
    $now = now();

    // Get the first and last day of the previous month
    $startOfLastMonth = (clone $now)->modify('first day of last month')->format('d/m/Y');
    $endOfLastMonth = (clone $now)->modify('last day of last month')->format('d/m/Y');

    $startDate = (clone $now)->modify('-1 day')->format('d/m/Y');
        $endDate = (clone $now)->modify('-1 day')->format('d/m/Y');


        $startDate1 = (clone $now)->modify('first day of this month')->format('d/m/Y');
        if ($now->format('j') === '1') {
            $endDate1 = (clone $now)->modify('+1 day')->format('d/m/Y');
        } else {
            $endDate1 = $now->format('d/m/Y');
        }


    // Query the database to get the total revenue for the previous month
    $totalRevenueLastMonth = AdunitReport::where('ad_unit_id',$unit_id)->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
        ->sum('revenue');
// Query the database to get the total revenue for the yesterday
        $totalRevenueyesterday = AdunitReport::where('ad_unit_id',$unit_id)->whereBetween('date', [$startDate, $endDate])
        ->sum('revenue');
// Query the database to get the total revenue for the this month
        $totalRevenueThisMonth = AdunitReport::where('ad_unit_id',$unit_id)->whereBetween('date', [$startDate1, $endDate1])
        ->sum('revenue');

$userexist= User::where('ad_unit_id',$unit_id)->first();

return view('admin.adUnit.view',compact('totalRevenueLastMonth','startOfLastMonth','endOfLastMonth','totalRevenueyesterday','startDate','totalRevenueThisMonth', 'startDate1','endDate1','unit_id','userexist'));


    
}

public function dashboard(){


   return view('admin.index-dashboard');


} 


 public function dashboardData(Request $request){

    $unit_id = $request->input('unitId', []);
  $user= auth()->user()->user_allow;
    $timePeriod = $request->input('timePeriod');
    list($startDates, $endDates) = explode(' to ', $timePeriod);
  
       $now = now();
      
       // Get the first and last day of the previous month
       $startOfLastMonths =  $now->copy()->startOfMonth()->subMonth()->format('d/m/Y');
       $endOfLastMonths = $now->copy()->subMonth()->endOfMonth()->format('d/m/Y');
   
   $startOfLastMonth = $now->copy()->startOfMonth()->subMonth()->format('Y-m-d');
   $last30DaysDate = $now->copy()->subDays(30)->format('Y-m-d');
   // End of last month
   $endOfLastMonth = $now->copy()->subMonth()->endOfMonth()->format('Y-m-d');
   
      $startDate = (clone $now)->format('Y-m-d');
      
   // $endDate = (clone $now)->modify('-1 day')->format('Y-m-d');
   
   $currentDate = now()->format('Y-m-d');
   $yesterdayDate = now()->subDay()->format('Y-m-d');
   
   $startDate1 = $now->copy()->startOfMonth()->format('d/m/Y');
   
   $currentmonth = $now->copy()->startOfMonth()->format('Y-m-d');
         if ($now->day === 1) {
       $currentmonthday = $now->copy()->addDay()->format('Y-m-d');
        $endDate1 = $now->copy()->addDay()->format('d/m/Y');
   } else {
       $endDate1 = $now->format('d/m/Y');
        $currentmonthday = $now->format('Y-m-d');
   }
   
   
        $percentage= Adunitpercen::whereIn('ad_unit_id',$unit_id)->sum('percentage');


       // Query the database to get the total revenue for the previous month
       $totalRevenueLastMonth = AdunitReport::whereIn('ad_unit_id',$unit_id)->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])->where('user_allow',$user)
           ->sum('revenue');
           
          $twentyPercentRevenues= $totalRevenueLastMonth * $percentage/100;
           $totalRevenueLastMonth=number_format($totalRevenueLastMonth-$twentyPercentRevenues,2);
           
   // Query the database to get the total revenue for the yesterday
         
           $totalRevenueyesterday = AdunitReport::whereIn('ad_unit_id', $unit_id)->where('user_allow',$user)
       ->whereBetween('date', [$yesterdayDate, $currentDate])
       ->sum('revenue');
        $twentyPercentRevenuess= $totalRevenueyesterday * $percentage/100;
           $totalRevenueyesterday=$totalRevenueyesterday-$twentyPercentRevenuess;
       
   // Query the database to get the total revenue for the this month
           $totalRevenueThisMonth = AdunitReport::whereIn('ad_unit_id',$unit_id)->whereBetween('date', [$currentmonth, $currentmonthday])->where('user_allow',$user)
           ->sum('revenue');
   
   $twentyPercentRevenue = $totalRevenueThisMonth * $percentage/100;
   
   $totalRevenueThisMonth= $totalRevenueThisMonth-$twentyPercentRevenue;

$commonArray=[

'totalRevenueLastMonth' => $totalRevenueLastMonth,
'startOfLastMonths' => $startOfLastMonths,
'startOfLastMonth' => $startOfLastMonth,
'endOfLastMonths' => $endOfLastMonths,
'currentmonth' => $currentmonth,
'currentmonthday' => $currentmonthday,
'endOfLastMonth' => $endOfLastMonth,
'totalRevenueyesterday' => $totalRevenueyesterday,
'startDate' => $startDate,
'totalRevenueThisMonth' => $totalRevenueThisMonth,
'startDate1' => $startDate1,
'endDate1' => $endDate1,
'unit_id' => $unit_id


];
   return response()->json([
    'success' => true,
    'array_value' => $commonArray
]);

   
  
   
   
   } 





   public function graphData(Request $request){

    $unit_id = $request->input('unitId', []);
  $user= auth()->user()->user_allow;
    $timePeriod = $request->input('timePeriod');
    list($startDates, $endDates) = explode(' to ', $timePeriod);
  
      
   
        $percentage= Adunitpercen::whereIn('ad_unit_id',$unit_id)->sum('percentage');


       // Query the database to get the total revenue for the previous month
       $totaldata = AdunitReport::whereIn('ad_unit_id',$unit_id)->where('user_allow',$user)->whereBetween('date', [$startDates, $endDates])->get();
           
        //   $twentyPercentRevenues= $totalRevenueLastMonth * $percentage/100;
        //    $totalRevenueLastMonth=number_format($totalRevenueLastMonth-$twentyPercentRevenues,2);


$commonArray=[

'totaldata' => $totaldata


];
   return response()->json([
    'success' => true,
    'array_value' => $commonArray
]);

   
   
   } 






}
