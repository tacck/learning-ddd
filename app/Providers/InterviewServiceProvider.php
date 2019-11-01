<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class InterviewServiceProvider extends ServiceProvider
{
    // 配列でのバインドもできる
//    public $bindings = [
//        InterviewRepository::class => InterviewEloquentRepository::class,
//    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // インタフェースと実体クラスのバインド
        $this->app->bind('SampleDdd\Domain\Repository\InterviewRepository', 'App\InterviewEloquentRepository');
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
