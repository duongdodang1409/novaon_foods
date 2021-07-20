<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
  return view('index');
});
Route::get('/login', [LoginController::class, 'sso']);
Route::get('/callback', [LoginController::class, 'login']);
//Route::get('/order', function () {
//    return view('order');
//});
Route::get('/order', [LoginController::class, 'checkUser']);

Route::get('/logout', [LoginController::class, 'logout']);




Route::group(['middleware' => 'web'], function () {
    Route::get('admin/{any}', [\App\Http\Controllers\LaravueController::class, 'index'])->where('any', '.*');
});

Route::group([
    'prefix' => 'laravel-filemanager',
    'middleware' => [
        'web',
        'filemanager',
    ]], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
