<?php
/**
 * Project: Caller_Core
 * Package: Novaon\Agent\Providers
 * Author: tony
 * Create time: 10:28 9/29/20
 * Copyright (c) 2020 NOVAON.
 **/


namespace Novaon\Menus\Providers;


use App\Models\Menu;
use Illuminate\Support\ServiceProvider;
use Novaon\User\Observers\UserObserver;

class MenuServiceProvider extends ServiceProvider
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
