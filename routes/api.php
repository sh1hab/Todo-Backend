<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Todo\TodoController;
use App\Http\Controllers\Api\User\TokenController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'verify.user.token', 'prefix' => 'todos'], function () {
    Route::resource('/', TodoController::class)->only(['index', 'store']);
    Route::put('/save', [TodoController::class, 'save']);
    Route::delete('/clear', [TodoController::class, 'clear']);
});

Route::post('/generate/user/token', [TokenController::class, 'generateUserToken']);
