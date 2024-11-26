<?php

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
  return  DB::select(DB::raw('EXPLAIN SELECT COUNT(*) FROM orders WHERE created_at BETWEEN "2024-08-01" AND "2024-09-31"'));

    //  return DB::select("SELECT COUNT(*) as total_orders FROM orders WHERE created_at BETWEEN '2024-08-01' AND '2024-10-31'");

/*    return  DB::table('orders')
        ->whereBetween('created_at',  ['2024-08-15', '2024-9-31'])
        ->count();*/
   //  return    $orders = Order::whereBetween('created_at', ['2024-08-15', '2024-10-31'])->get();

    /*  Cache::remember('orders_count', 600, function () {
        return  DB::table('orders')->count();
      });*/

   // return  DB::table('orders')->count();

    //return DB::table('orders')->where('status', 'completed')->count();


    // return  Order::take(10000)->get(['id','order_name','quantity','price']);
    // return DB::select("SELECT id ,order_name ,quantity ,price FROM orders WHERE user_id = 1000");
    // return DB::table('orders')->where('user_id',111)->get();
    // return DB::table('orders')->where('user_id',111)->get();
});
