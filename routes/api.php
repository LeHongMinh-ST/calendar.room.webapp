<?php

use App\Http\Controllers\Api\CalendarController;
use App\Http\Controllers\Api\FacultyController;
use App\Http\Controllers\AuthController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    Route::group(['middleware' => ['jwt.auth']], function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::get('get-event-calendar', [CalendarController::class, 'getEventCalender']);
    });
});

Route::get('get-events-calendar', [CalendarController::class, 'getEventsCalender']);

Route::group(['middleware' => ['jwt.auth']], function () {

    Route::prefix('faculty')->name('faculty.')->group(function () {
        Route::get('/', [FacultyController::class, 'index']);
        Route::post('/', [FacultyController::class, 'store']);
        Route::put('/{id}', [FacultyController::class, 'update']);
        Route::get('/{id}', [FacultyController::class, 'show']);
        Route::delete('/{id}', [FacultyController::class, 'destroy']);
    });
});

