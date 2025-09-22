<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\LabelController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('greetFromHexlet');
})->name('home');

Route::resource('tasks', TaskController::class);
Route::resource('task_statuses', TaskStatusController::class);
Route::resource('labels', LabelController::class);

require __DIR__ . '/auth.php';
