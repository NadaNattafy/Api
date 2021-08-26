<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
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
Route::post('login',[PassportAuthController::class,'login']);
Route::get('logout', [PassportAuthController::class, 'logout']);

 Route::middleware('auth:api')->group(function () {
    Route::get('get-user', [PassportAuthController::class, 'userInfo']);

 Route::resource('products', ProductController::class);

 Route::resource('tasks', TaskController::class);

 Route::post("create-task", "TaskController@createTask");

    Route::get("tasks", "TaskController@tasks");

    Route::get("task/{task_id}", "TaskController@task");

    Route::delete("task/{task_id}", "TaskController@deleteTask");

    Route::get("task-by-status-auth/{status}","TaskController@tasks_by_status_auth");

        Route::get("task-by-status/{status}","TaskController@tasks_by_status");



 });
