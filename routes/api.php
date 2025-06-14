<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;

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

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('API.AUTH.LOGIN');
    Route::post('registration', [AuthController::class, 'registration'])->name('API.AUTH.REGISTRATION');
    Route::post('forgot', [AuthController::class, 'forgot'])->name('API.AUTH.FORGOT');
    Route::post('reset', [AuthController::class, 'reset'])->name('API.AUTH.RESET');
    Route::post('verification', [AuthController::class, 'verification'])->name('API.AUTH.VERIFICATION');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('users')->group(function () {
        Route::get('other-users', [AuthController::class, 'users'])->name('API.USERS.GET.ALL');
    });

    Route::prefix('profile')->group(function () {
        Route::post('details', [AuthController::class, 'details'])->name('API.PROFILE.DETAILS');
        Route::post('change-details', [AuthController::class, 'changeDetails'])->name('API.PROFILE.CHANGE.DETAILS');
        Route::post('change-password', [AuthController::class, 'changePassword'])->name('API.PROFILE.CHANGE.PASSWORD');
        Route::post('logout', [AuthController::class, 'logout'])->name('API.PROFILE.LOGOUT');
    });

    Route::prefix('chat')->group(function () {
        Route::get('list', [ChatController::class, 'list'])->name('API.CHAT.LIST');
        Route::post('store', [ChatController::class, 'store'])->name('API.CHAT.STORE');
        Route::delete('delete/{id}', [ChatController::class, 'delete'])->name('API.CHAT.DELETE');
        Route::delete('clear', [ChatController::class, 'clear'])->name('API.CHAT.CLEAR');
    });

});
