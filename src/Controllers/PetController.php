<?php

namespace WellCat\Controllers;

use Silex\Application;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use WellCat\JsonResponse;

class PetController
{
    protected $app;

    public function __construct(\Silex\Application $app)
    {
        $this->app = $app;
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
        if (!$petName)
        {
            return JsonResponse::missingParam('name');
        }
        else if (!$breed)
        {
            return JsonResponse::missingParam('breed');
        }
        else if (!$gender)
        {
            return JsonResponse::missingParam('gender');
        }
        else if (!$dateOfBirth) // TODO: validate date is in correct format
        {
            return JsonResponse::missingParam('dateOfBirth');
        }
        else if (!$weight)
        {
            return JsonResponse::missingParam('weight');
        }
        else if (!$height)
        {
            return JsonResponse::missingParam('height');
        }
        else if (!$length)
        {
            return JsonResponse::missingParam('length');
        }

        // Add pet to database
        $sql = 'INSERT INTO pet (ownerid, animalId, name, breedId, gender, dateOfBirth, weight, height, length) 
            VALUES (:ownerId, :animalId, :name, :breed, :gender, :dateOfBirth, :weight, :height, :length)';

        $stmt = $this->app['db']->prepare($sql);
        $success = $stmt->execute(array(
            ':ownerId' => $this->app['session']->get('user')['userId'],
            ':animalId' => 1, // hardcode to Cat for now 
            ':name' => $petName,
            ':breed' => $breed,
            ':gender' => $gender,
            ':dateOfBirth' => $dateOfBirth,
            ':weight' => $weight,
            ':height' => $height,
            ':length' => $length
        ));

        if ($success) 
        {
            return new JsonResponse();
        } else 
        {
            return JsonReponse::userError('Unable to register pet');
        }
    }
}