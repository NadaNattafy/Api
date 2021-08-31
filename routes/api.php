<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [PassportAuthController::class, 'register']);

Route::post('login', [PassportAuthController::class, 'login']);

Route::get('logout', [PassportAuthController::class, 'logout']);

Route::middleware('auth:api')->group(function () {

    Route::get('get-user', [PassportAuthController::class, 'userInfo']);

    Route::resource('products', ProductController::class);

    Route::resource('tasks', TaskController::class);

    Route::get("count", [TaskController::class, 'taskCount']);
});

Route::resource('image', ImageController::class);

Route::get('email/verify/{id}', [VerificationController::class ,'verify'])->name('verification.verify');

Route::get('email/resend', [VerificationController::class , 'resend'])->name('verification.resend');
