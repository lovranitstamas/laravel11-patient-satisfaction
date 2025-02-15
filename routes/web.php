<?php

use App\Http\Controllers\SpaController;
use Illuminate\Support\Facades\Route;

//Route::get('/inventories', DefaultController::class)->name('inventories.list');

Route::get('/{any}', [SpaController::class, 'index'])
  ->where('any', '^(?!manifest\.json|favicon\.ico).*$');

/*
Route::get('/', function () {
    return view('welcome');
});
*/
