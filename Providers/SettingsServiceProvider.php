<?php

namespace Modules\Settings\Providers;


use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\ServiceProvider;
use Modules\Base\Services\Components\Base\Lang;
use Modules\Base\Services\Core\VILT;
use Modules\Settings\Console\GenerateSettings;

include __DIR__ . '/helpers.php';

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Settings';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'settings';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        VILT::loadPages($this->moduleName);

        VILT::registerTranslation(Lang::make('site_settings.sidebar')->label(__('Site Settings')));
        VILT::registerTranslation(Lang::make('google_settings.sidebar')->label(__('Google Settings')));
        VILT::registerTranslation(Lang::make('email_settings.sidebar')->label(__('Email Settings')));

        $this->commands([
            GenerateSettings::class
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'),
            $this->moduleNameLower
        );
    }
}
