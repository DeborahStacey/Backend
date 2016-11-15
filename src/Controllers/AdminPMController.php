<?php

namespace WellCat\Controllers;

use Silex\Application;
use PDO;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use WellCat\JsonResponse;

class AdminPMController
{

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->app['session']->start();
    }


    /**
     * Function tells the user they are authenticated.
     */
    public function Authenticate()
    {
        $data = array(
            'success' => true,
            'message' => 'Successfully authenticated'
        );
        return new JsonResponse($data, 200);
    }

    public function Create(Request $request)
    {
        $user = $this->app['session']->get('user');

        //$ownerid = $request->request->get('');
        $petName = $request->request->get('name');
        $dateOfBirth = $request->request->get('dateOfBirth');
        $weight = $request->request->get('weight');
        $height = $request->request->get('height');
        $length = $request->request->get('length');
        $animal = $request->request->get('animalTypeID');
        $breed = $request->request->get('breed');
        $gender = $request->request->get('gender');

        // Validate parameters
        if (!$petName) {
            return JsonResponse::missingParam('name');
        }
        elseif (!$breed) {
            return JsonResponse::missingParam('breed');
        }
        elseif (!$animal) {
            return JsonResponse::missingParam('animal');
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
        //TODO: Check if gender exists.
        
        // Add pet to database
        $sql = 'INSERT INTO pet (ownerid, name, breed, gender, dateofbirth, weight, height, length)
            VALUES (:ownerId, :name, :breed, :gender, :dateOfBirth, :weight, :height, :length)';

        $stmt = $this->app['db']->prepare($sql);
        $success = $stmt->execute(array(
            ':ownerId' => $user['userId'],
            ':name' => $petName,
            ':breed' => $breed,
            ':gender' => $gender,
            ':dateOfBirth' => $dateOfBirth,
            ':weight' => $weight,
            ':height' => $height,
            ':length' => $length
        ));

        if ($success && $animal == 1) {
            //get petID from previous entry
            $petID = $this->app['db']->lastInsertId('pet_petid_seq');

            // Add cat to database
            $sql = 'INSERT INTO pet_cat (petid, declawed, outdoor, fixed)
                    VALUES (:petID, :declawed, :outdoor, :fixed)';

            $stmt = $this->app['db']->prepare($sql);
            $success = $stmt->execute(array(
                ':petID' => $petID,
                ':declawed' => "true",
                ':outdoor' => "false",
                ':fixed' => "true"
            ));

            if ($success) {
                return new JsonResponse();
            }
            else {
                //TODO: Need to remove the pet if it fails to add it originally.
                return JsonReponse::userError('Unable to register pet.');
            }
        }
        else {          
            return JsonReponse::userError('Unable to register pet.');
        }

    }
}