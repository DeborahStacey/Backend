<?php

namespace WellCat\Controllers;

use Silex\Application;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use WellCat\JsonResponse;
use WellCat\Validators\PetRequestValidator;

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
        $validationResult = $this->app['api.petrequestvalidator']->ValidateGenericPetCreationRequest($request);

        if (!$validationResult->GetSuccess()) {
            return $validationResult->GetError();
        }

        if ($validationResult->GetParameter('animalTypeID') == 1) {
            $catValidationResult = $this->app['api.petrequestvalidator']->ValidateCatPetCreationRequest($request);

            if (!$catValidationResult->GetSuccess()) {
                return $catValidationResult->GetError();
            }

            return $this->CreateCatPet(
                $validationResult->GetParameter('name'),
                $validationResult->GetParameter('breed'),
                $validationResult->GetParameter('gender'),
                $validationResult->GetParameter('dateOfBirth'),
                $validationResult->GetParameter('weight'),
                $validationResult->GetParameter('height'),
                $validationResult->GetParameter('length'),
                $catValidationResult->GetParameter('declawed'),
                $catValidationResult->GetParameter('outdoor'),
                $catValidationResult->GetParameter('fixed')
            );
        }
        else {
            return $this->CreateGenericPet(
                $validationResult->GetParameter('name'),
                $validationResult->GetParameter('breed'),
                $validationResult->GetParameter('gender'),
                $validationResult->GetParameter('dateOfBirth'),
                $validationResult->GetParameter('weight'),
                $validationResult->GetParameter('height'),
                $validationResult->GetParameter('length')
            );
        }
    }

    public function SetAccessibility(Request $request)
    {
        // Get parameters
        $email = $request->request->get('email');
        $petID = $request->request->get('petID');
        $access = $request->request->get('access');

        // Validate parameters
        if (!$email) {
            return JsonResponse::missingParam('email');
        }
        elseif (!$petID) {
            return JsonResponse::missingParam('petID');
        }
        elseif (!$access) {
            return JsonResponse::missingParam('access');
        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return JsonResponse::userError('Invalid email');
        }
        elseif (!$this->app['api.dbtypes']->IsValidPetAccessibilityValue($access)) {
            return JsonResponse::userError('Invalid accessibility value');
        }

        // Get userID from email
        $userID = $this->app['api.auth']->GetUserIDByEmail($email);

        if (!$userID) {
            return JsonResponse::userError('Email provided is not associated with an existing WellCat account');
        }

        // Check to see if user already has accessibility with pet
        $currentAccess = $this->GetPetAccessibility($userID, $petID);

        // If no accessibility found, insert
        if (!$currentAccess) {
            $sql = 'INSERT INTO accessibility (userid, petid, access)
                        VALUES (:userid, :petid, :access)';

            $stmt = $this->app['db']->prepare($sql);
            $success = $stmt->execute(array(
                ':userid' => $userID,
                ':petid' => $petID,
                ':access' => $access
            ));

            if ($success) {
                return new JsonResponse(null, 201);
            }
            else {
                return JsonReponse::userError('Unable to set pet accessibility.');
            }
        }
        // else update if current accessibility is not the same as the one trying to be set
        elseif ($currentAccess != $access) {
            $sql = 'UPDATE accessibility
                    SET access = :access
                    WHERE userid = :userid
                        AND petid = :petid';

            $stmt = $this->app['db']->prepare($sql);
            $success = $stmt->execute(array(
                ':userid' => $userID,
                ':petid' => $petID,
                ':access' => $access
            ));

            if ($success) {
                return new JsonResponse(null, 201);
            }
            else {
                return JsonReponse::userError('Unable to update pet accessibility.');
            }
        }
        // else just return success
        else {
            return new JsonResponse(null, 201);
        }
    }

    private function CreateCatPet($name, $breed, $gender, $dateOfBirth, $weight, $height, $length, $declawed, $outdoor, $fixed)
    {
        // Add pet to database
        $sql = 'INSERT INTO pet_cat (ownerid, name, breedId, gender, dateofbirth, weight, height, length, declawed, outdoor, fixed)
            VALUES (:ownerId, :name, :breed, :gender, :dateOfBirth, :weight, :height, :length, :declawed, :outdoor, :fixed)';

        $stmt = $this->app['db']->prepare($sql);
        $success = $stmt->execute(array(
            ':ownerId' => $this->app['session']->get('user')['userId'],
            ':name' => $name,
            ':breed' => $breed,
            ':gender' => $gender,
            ':dateOfBirth' => $dateOfBirth,
            ':weight' => $weight,
            ':height' => $height,
            ':length' => $length,
            ':declawed' => $declawed,
            ':outdoor' => $outdoor,
            ':fixed' => $fixed
        ));

        if ($success) {
            return new JsonResponse();
        }
        else {
            return JsonReponse::userError('Unable to register cat.');
        }
    }

    private function CreateGenericPet($name, $breed, $gender, $dateOfBirth, $weight, $height, $length)
    {
        // Add pet to database
        $sql = 'INSERT INTO pet (ownerid, name, breedId, gender, dateofbirth, weight, height, length)
            VALUES (:ownerId, :name, :breed, :gender, :dateOfBirth, :weight, :height, :length)';

        $stmt = $this->app['db']->prepare($sql);
        $success = $stmt->execute(array(
            ':ownerId' => $this->app['session']->get('user')['userId'],
            ':name' => $name,
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

    private function GetPetAccessibility($userID, $petID)
    {
        // TODO: validate parameters and throw exception if null
        // For now, this function is only being called in a state where parameters have already been validated

        $sql = 'SELECT access FROM accessibility WHERE userid = :userid AND petid = :petid';

        $stmt= $this->app['db']->prepare($sql);
        $stmt->execute(array(
            ':userid' => $userID,
            ':petid' => $petID
        ));

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result) {
            return $result['access'];
        }
        else {
            return null;
        }
    }
}
