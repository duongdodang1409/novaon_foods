<?php
/**
 * Project: Caller_Core
 * Package: Novaon\Agent\Providers
 * Author: tony
 * Create time: 10:28 9/29/20
 * Copyright (c) 2020 NOVAON.
 **/


namespace Novaon\Product\Providers;


use App\Models\User;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //TODO
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Register routes
        $this->loadRoutesFrom(__DIR__.'/../routes.php');


    }
}
