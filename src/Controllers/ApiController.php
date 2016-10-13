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

    public function entryPoint()
    {
        $data = array(
            '/' => 'Get end points'

        );
        return new JsonResponse($data, 201);
    }
}
