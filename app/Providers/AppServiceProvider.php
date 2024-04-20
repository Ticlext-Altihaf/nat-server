<?php

namespace App\Providers;

use App\Models\AppSettings;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

// use Illuminate\Support\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
        if (app()->runningInConsole()) return;
        $devices_name = AppSettings::getDevicesName();
        foreach ($devices_name->value as $id => $name) {

            app('translator')->addLines([
                'devices_name.' . $id => $name,
            ], 'id');
        }


        $translation = AppSettings::getTranslation();
        foreach ($translation->value as $key => $value) {
            app('translator')->addLines([
                'translation.' . $key => $value,
            ], 'id');
        }
    }
}
