<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ScreeningServiceProvider extends ServiceProvider
{
    // 配列でのバインドもできる
//    public $bindings = [
//        ScreeningRepository::class => ScreeningEloquentRepository::class,
//    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // インタフェースと実体クラスのバインド
        $this->app->bind('SampleDdd\Domain\Repository\ScreeningRepository', 'App\ScreeningEloquentRepository');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
