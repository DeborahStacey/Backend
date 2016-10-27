<?php

namespace WellCat\Controllers;

use Silex\Application;
use PDO;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use WellCat\JsonResponse;

class PetController
{
    protected $app;

    public function __construct(\Silex\Application $app)
    {
        $this->app = $app;
        $this->app['session']->start();
    }

    public function Create(Request $request)
    {
        // Get parameters
        $petName = $request->request->get('name');
        $breed = $request->request->get('breed');
        $gender = $request->request->get('gender');
        $dateOfBirth = $request->request->get('dateOfBirth');
        $weight = $request->request->get('weight');
        $height = $request->request->get('height');
        $length = $request->request->get('length');

        // Validate parameters
        if (!$petName) {
            return JsonResponse::missingParam('name');
        }
        elseif (!$breed) {
            return JsonResponse::missingParam('breed');
        }
        elseif (!$gender) {
            return JsonResponse::missingParam('gender');
        }
        elseif (!$dateOfBirth) {
            return JsonResponse::missingParam('dateOfBirth');
        }
        elseif (!$weight) {
            return JsonResponse::missingParam('weight');
        }
        elseif (!$height) {
            return JsonResponse::missingParam('height');
        }
        elseif (!$length) {
            return JsonResponse::missingParam('length');
        }
        elseif (!DateTime::createFromFormat('Y-m-d', $dateOfBirth)) {
            return JsonResponse::userError('Invalid date.');
        }
        elseif (!$this->app['api.animalservice']->CheckBreedExists($breed)) {
            return JsonResponse::userError('Invalid breed.');
        }

        // Add pet to database
        $sql = 'INSERT INTO pet (ownerid, name, breedId, gender, dateofbirth, weight, height, length)
            VALUES (:ownerId, :name, :breed, :gender, :dateOfBirth, :weight, :height, :length)';

        $stmt = $this->app['db']->prepare($sql);
        $success = $stmt->execute(array(
            ':ownerId' => $this->app['session']->get('user')['userId'],
            ':name' => $petName,
            ':breed' => $breed,
            ':gender' => $gender,
            ':dateOfBirth' => $dateOfBirth,
            ':weight' => $weight,
            ':height' => $height,
            ':length' => $length
        ));

        if ($success) {
            return new JsonResponse();
        } 
        else {          
            return JsonReponse::userError('Unable to register pet.');
        }
    }
}
