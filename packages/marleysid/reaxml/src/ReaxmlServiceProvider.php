<?php

namespace Marleysid\Reaxml;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class ReaxmlServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    protected $defer = false;

    public function boot()
    {

        AliasLoader::getInstance()->alias('Reaxml', 'Marleysid\Reaxml\Facade\ReaxmlFacade');
        /*$this->publishes([
            __DIR__ . '/config/Reaxml.php' => config_path('geo-address.php'),
        ]);*/
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['Reaxml'] = $this->app->share(function ($app) {
            /*$config                   = array();
            $config['applicationKey'] = Config::get('geo-address.applicationKey');*/
            return new Reaxml();
        });
    }

    
}
