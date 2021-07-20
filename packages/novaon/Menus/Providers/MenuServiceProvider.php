<?php


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
