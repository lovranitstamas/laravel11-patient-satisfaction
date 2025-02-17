<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DefaultController;
use App\Http\Controllers\SpaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

$bRenderMode = (int)config('app.blade_render_mode');
if ($bRenderMode == 1) {
  Route::get('/', [UserController::class, 'index']);
  Route::get('/admin', [AdminController::class, 'index']);

  Auth::routes();
  Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

  Route::group(['middleware' => ['auth']], function () {
    Route::get('/surveys', DefaultController::class);
    Route::get('/questionnaires', DefaultController::class);
    Route::get('/responses', DefaultController::class);
  });
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
