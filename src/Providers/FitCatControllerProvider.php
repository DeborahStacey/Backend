<?php
namespace WellCat\Providers;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Silex\ServiceProviderInterface;
use WellCat\Controllers\FitCatController;
use WellCat\JsonResponse;

class FitCatControllerProvider implements ControllerProviderInterface, ServiceProviderInterface
{

    /**
     * Registers
     */
    public function register(Application $app)
    {
        $app['api.fitcat'] = $app->share(function () use ($app) {
            return new FitCatController($app);
        });
    }

    public function boot(Application $app)
    {

    }

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers
            ->post('/weight', 'api.fitcat:Weight')
        ;

        $controllers
            ->post('/steps', 'api.fitcat:Steps')
        ;

        $controllers
            ->post('/water', 'api.fitcat:Water')
        ;
        
        $controllers
            ->post('/food', 'api.fitcat:Food')
        ;

        return $controllers;
    }
}
