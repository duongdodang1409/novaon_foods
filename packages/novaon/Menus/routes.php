<?php
/**
 * Project: Caller_Core
 * Author: tony
 * Create time: 10:29 9/29/20
 * Copyright (c) 2020 NOVAON.
 **/

Route::group([
    'as' => 'novaon.',
], function () {
    includeRouteFilesInFolder(__DIR__.'/Routes/Api');
});
