<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
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


// 🚀 Authentication routes
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 📝 Post routes with 'auth:api' middleware
Route::middleware(['auth:api'])->group(function () {
    // 📄 Get all posts
    Route::get('/posts', [PostController::class, 'index']);

    // 🛡️ Protected route to fetch posts created by the authenticated user
    Route::get('/user-posts', [PostController::class, 'fetchUserPosts']);
});
