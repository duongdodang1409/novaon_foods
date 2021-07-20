<?php
use Novaon\Menus\Controllers\Api\RestaurantController;
use Novaon\Menus\Controllers\Api\FoodController;
use Novaon\Menus\Controllers\Api\WeekdayController;
use Novaon\Menus\Controllers\Api\CustomerController;
use Novaon\Menus\Controllers\Api\HistoryController;
use Novaon\Menus\Controllers\Api\OrderController;
Route::group([
    'as' => 'api.',
    'prefix' => 'api/v1',
    'middleware' => 'bindings',
], function () {
    Route::resource('restaurants',RestaurantController::class);
    Route::resource('foods',FoodController::class);
    Route::resource('weekdays',WeekdayController::class);
    Route::resource('customers',CustomerController::class);
    Route::resource('history',HistoryController::class);
    Route::resource('orders',OrderController::class);





});


