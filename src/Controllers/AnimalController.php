<?php

namespace WellCat\Controllers;

use WellCat\JsonResponse;

class AnimalController
{
    protected $app;

    public function __construct(\Silex\Application $app)
    {
        $this->app = $app;
        $this->app['session']->start();
    }

    public function GetAnimals()
    {
        // Get animals from database
        $sql = 'SELECT animaltypeid AS id, name FROM animal';

        $stmt = $this->app['db']->prepare($sql);
        $success = $stmt->execute();

        if ($success) {
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if ($result == false) {
                return JsonReponse::userError('Unable to retreive animals');
            }

            return new JsonResponse($result);
        } else {
            return JsonReponse::userError('Unable to retreive animals');
        }
    }

    public function GetBreedsByAnimalId($animalId)
    {
        if (!$animalId) {
            return JsonResponse::missingParam('animalId');
        }

        if (!is_numeric($animalId)) {
            return JsonResponse::userError('Invalid animal type id '.$animalId);
        }

        // Get animals from database
        $sql = 'SELECT breedId as id, name FROM breed WHERE animaltypeid = :animaltypeid';

        $stmt = $this->app['db']->prepare($sql);
        $success = $stmt->execute(array(
            ':animaltypeid' => $animalId
        ));

        if ($success) {
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if ($result == false) {
                return JsonResponse::userError('Unable to retreive breeds for specified animal type.');
            }

            return new JsonResponse($result);
        } else {
            return JsonReponse::userError('Unable to retreive breeds for specified animal type.');
        }
    }
}
