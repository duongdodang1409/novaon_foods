<?php
/**
 * Project: Caller_Core
 * Author: tony
 * Create time: 11:29 9/28/20
 * Copyright (c) 2020 NOVAON.
 **/

use Novaon\User\Controllers\Api\UserController;
//use Novaon\User\Controllers\Api\PostController;

Route::group([
    'as' => 'api.',
    'prefix' => 'api/v1',
    'middleware' => 'bindings',
], function () {
    Route::resource('users', UserController::class);
//    Route::resource('posts', PostController::class);
});
