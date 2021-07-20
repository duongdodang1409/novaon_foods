<?php
Route::group([
    'as' => 'novaon.',
], function () {
    includeRouteFilesInFolder(__DIR__.'/Routes/Api');
});
