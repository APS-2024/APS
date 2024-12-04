<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdunitReport;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Index extends Controller
{
    function __construct() {
        $this->middleware('auth');
        $this->middleware('role_or_permission:SuperAdmin|AdminPanel access', ['only' => 'index']);
    }

    public function index(FlasherInterface $flasher) {
        // Check if user is authenticated
        if (auth()->check()) {
            $userName = auth()->user()->name;
            $message = 'Welcome ' . $userName . '!';
            $now = now();

            // Get the first and last day of the previous month
            $startOfLastMonths =  $now->copy()->startOfMonth()->subMonth()->format('d/m/Y');
       $endOfLastMonths = $now->copy()->subMonth()->endOfMonth()->format('d/m/Y');
       $last30DaysDate = $now->copy()->subDays(30)->format('Y-m-d');
   $startOfLastMonth = $now->copy()->startOfMonth()->subMonth()->format('Y-m-d');
   
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
   

            // Query the database to get the total revenue for the previous month
            $totalRevenueLastMonth = AdunitReport::whereBetween('date',  [$startOfLastMonth, $endOfLastMonth])
                ->sum('revenue');
// Query the database to get the total revenue for the yesterday
                $totalRevenueyesterday = AdunitReport::whereBetween('date', [$yesterdayDate, $currentDate])
                ->sum('revenue');
// Query the database to get the total revenue for the this month
                $totalRevenueThisMonth = AdunitReport::whereBetween('date', [$currentmonth, $currentmonthday])
                ->sum('revenue');


            $flasher->success($message, ['title' => 'Dash UI']); // Pass options as an array
        } else {
            // Handle the case where the user is not authenticated
            $flasher->info('Welcome, Guest!', ['title' => 'Dash UI']); // Pass options as an array
        }
    
        return view('admin.index',compact('totalRevenueLastMonth','startOfLastMonths','currentDate','startOfLastMonth','last30DaysDate','endOfLastMonths','currentmonth','currentmonthday','endOfLastMonth','totalRevenueyesterday','startDate','totalRevenueThisMonth', 'startDate1','endDate1'));
    }
    

    public function graphDataDash(Request $request){

    
        $timePeriod = $request->input('timePeriod');
        list($startDates, $endDates) = explode(' to ', $timePeriod);
      
          
           
    
           // Query the database to get the total revenue for the previous month
           $totaldata = AdunitReport::whereBetween('date', [$startDates, $endDates])->get();
               
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