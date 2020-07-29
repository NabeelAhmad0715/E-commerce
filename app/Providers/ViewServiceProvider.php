<?php

namespace App\Providers;

use App\ImageManager;
use Illuminate\Support\ServiceProvider;
use App\Type;
use Illuminate\Support\Facades\View;
use App\GeneralSetting;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['layouts.*', '*'], function ($view) {
            $types = Type::all();
            $settings = GeneralSetting::first();
            $images = ImageManager::orderBy('id', 'desc')->paginate(20);
            $view->with(compact('types', 'settings', 'images'));
        });
    }
}
