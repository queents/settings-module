<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelSettings\Models\SettingsProperty;



// Test database connection
try {
    DB::connection()->getPdo();

    if (!function_exists('setting')) {
        function setting($key)
        {
            try{
                Config::set('session.driver','database');
                $setting = SettingsProperty::where('name', $key)->first();
            }catch (\Exception $e){
                $setting = false;
                Config::set('session.driver','array');
            }
            if ($setting) {
                return json_decode($setting->payload);
            } else {
                return false;
            }
        }
    }
    if (!function_exists('dollar')) {
        function dollar($total)
        {
            $getDollar = setting('site_currency');
            if ($getDollar) {
                return "<b>" . number_format($total, 2) . "</b><small>$getDollar</small>";
            } else {
                return false;
            }
        }
    }
    if (!function_exists('hasModule')) {
        function hasModule($name)
        {
            return \Module::collections()->has($name);
        }
    }

    Config::set('session.driver','database');

} catch (\Exception $e) {
    if (!function_exists('setting')) {
        function setting($key)
        {
            return $key;
        }
    }

    Config::set('session.driver','array');
}

