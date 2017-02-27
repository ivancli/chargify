<?php
namespace IvanCLI\Chargify;

use Illuminate\Support\ServiceProvider;

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 23/10/2016
 * Time: 1:07 AM
 */
class ChargifyServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/chargify.php' => config_path('chargify.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerChargify();
    }

    private function registerChargify()
    {
        $this->app->bind('chargify', function ($app) {
            return new Chargify($app);
        });
    }
}