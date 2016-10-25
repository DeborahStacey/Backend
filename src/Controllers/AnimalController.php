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

            $body = array(
                'animals' => $result,
                'success' => true
            );

            return new JsonResponse($body, 200);
        } 
        else {
            $body = array(
                'success' => false,
                'error' => "Unable to get animals."
            );

            return new JsonResponse ($body, 500);
        }
    }

    public function GetBreedsByAnimalId($animalId)
    {
        if (!$animalId) {
            return JsonResponse::missingParam('animalId');
        }
        elseif (!is_numeric($animalId)) {
            return JsonResponse::userError('Invalid animal type id '.$animalId);
        }

        // Get breeds from database
        $sql = 'SELECT breedId as id, name FROM breed WHERE animaltypeid = :animaltypeid';

        $stmt = $this->app['db']->prepare($sql);
        $success = $stmt->execute(array(
            ':animaltypeid' => $animalId
        ));

        if ($success) {
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if (!$result) {
                $body = array(
                    'success' => false,
                    'error' => "Unable to retrieve breeds for specified animal type."
                );

                return new JsonResponse ($body, 403);
            }

            $body = array(
                'breeds' => $result,
                'success' => true
            );

            return new JsonResponse($body, 200);
        } 
        else {
            $body = array(
                'success' => false,
                'error' => "Unable to retrieve breeds."
            );

            return new JsonResponse ($body, 500);
        }
    }
}
