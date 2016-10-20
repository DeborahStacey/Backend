<?php
namespace WellCat\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WellCat\JsonResponse;

class ApiController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function EndPoints()
    {
        $data = array(
            '/' => 'Get end points',
            '/user/authenticate' => 'Checks to see if current user is authenticated/logged in'
        );
        return new JsonResponse($data, 201);
    }
}
