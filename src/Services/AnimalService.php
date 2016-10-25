<?php

namespace WellCat\Services;

use Silex\Application;

class AnimalService
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function CheckBreedExists($breedId)
    {
        if (!$breedId || !is_numeric($breedId)) {
            return false;
        }

        $sql = 'SELECT NULL FROM breed WHERE breedid = :breedid';

        $stmt = $this->app['db']->prepare($sql);
        $success = $stmt->execute(array(
          ':breedid' => $breedId
        ));

        if ($success) {
            return true;
        } else {
            return false;
        }
    }
}
