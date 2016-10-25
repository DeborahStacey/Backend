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

    public function CheckAnimalExists($animalTypeId)
    {
        if (!$animalTypeId || !is_numeric($animalTypeId)) {
            return false;
        }

        $sql = 'SELECT NULL FROM animal WHERE animaltypeid = :animaltypeid';

        $stmt = $this->app['db']->prepare($sql);
        $success = $stmt->execute(array(
            ':animaltypeid' => $animalTypeId,
        ));

        if ($success) {
            return true;
        } else {
            return false;
        }
    }

    public function CheckAnimalBreedExists($animalTypeId, $breedId)
    {
        if (!$animalTypeId || !is_numeric($animalTypeId) || !$breedId || !is_numeric($breedId)) {
            return false;
        }

        $sql = 'SELECT NULL FROM breed WHERE animaltypeid = :animaltypeid AND breedid = :breedid';

        $stmt = $this->app['db']->prepare($sql);
        $success = $stmt->execute(array(
          ':animaltypeid' => $animalTypeId,
          ':breedid' => $breedId
        ));

        if ($success) {
            return true;
        } else {
            return false;
        }
    }
}
