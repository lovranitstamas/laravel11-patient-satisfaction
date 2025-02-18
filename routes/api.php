<?php

use App\Http\Controllers\ExportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/

Route::post('/export/filteredResponses', [ExportController::class, 'index'])
  ->name('api.export.filteredResponses');