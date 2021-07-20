<?php
/**
 * Project: Caller_Core
 * Package: Novaon\Agent\Providers
 * Author: tony
 * Create time: 10:28 9/29/20
 * Copyright (c) 2020 NOVAON.
 **/


namespace Novaon\User\Providers;


use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Novaon\User\Observers\UserObserver;

class UserServiceProvider extends ServiceProvider
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

        $this->publishes([
            __DIR__.'/../databases/migrations' => database_path('migrations')
        ], 'migrations');

        User::observe(UserObserver::class);
    }
}
