<?php

use App\Http\Controllers\BackupController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//------------------------------- Backup with package --------------------------\\
//------------------------------------------------------------------\\
Route::resource('backups', BackupController::class)->only([
    'store', 'index', 'destroy'
])->names('backup.with.package');


//------------------------------- Backup without package --------------------------\\
//------------------------------------------------------------------\\
Route::get('backups', [BackupController::class, 'index2'])->name('backups.without.package.all');
Route::post('backups/create', [BackupController::class, 'store2'])->name('backups.without.package.create');
Route::delete('/backups/{name}', [BackupController::class, 'destroy2'])->name('backups.without.package.delete');

