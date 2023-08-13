<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('oauth/{provider}', [SocialController::class ,'redirectToProvider']);
Route::get('oauth/google/callback', [SocialController::class, 'callbackGoogle']);
Route::get('oauth/linkedin/callback', [SocialController::class, 'callbackLinkedin']);




Route::resource('Product', ProductController::class);


Route::resource('Users', UserController::class);