<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReminderController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    // user
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // reminders
    Route::get('/reminders', [ReminderController::class, 'index']); // all user reminders
    Route::post('/reminders', [ReminderController::class, 'store']); // create reminder
    Route::get('/reminders/{id}', [ReminderController::class, 'show']); // get single reminder
    Route::put('/reminders/{id}', [ReminderController::class, 'update']); // update reminder
    Route::delete('/reminders/{id}', [ReminderController::class, 'destroy']); // delete reminder
    Route::delete('/reminders', [ReminderController::class, 'deleteTrashedReminders']); // delete history
});
