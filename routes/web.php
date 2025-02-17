<?php

use App\Http\Controllers\DefaultController;
use App\Http\Controllers\SpaController;
use Illuminate\Support\Facades\Route;

$bRenderMode = (int)config('app.blade_render_mode');
if ($bRenderMode == 1) {
  Route::get('/surveys', DefaultController::class);
} else {
//Vue router
  Route::get('/{any}', [SpaController::class, 'index'])
    ->where('any', '^(?!manifest\.json|favicon\.ico).*$');
}

/*
Route::get('/', function () {
    return view('welcome');
});
*/
