<?php

namespace WellCat\Validators;

use DateTime;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WellCat\JsonResponse;

class PetRequestValidator
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function ValidateGenericPetCreationRequest(Request $request)
    {
        $success = true;
        $error = null;
        $parameters = null;

        // Get parameters
        $name = $request->request->get('name');
        $animalTypeID = $request->request->get('animalTypeID');
        $breed = $request->request->get('breed');
        $gender = $request->request->get('gender');
        $dateOfBirth = $request->request->get('dateOfBirth');
        $weight = $request->request->get('weight');
        $height = $request->request->get('height');
        $length = $request->request->get('length');

        // Validate parameters
        if (!$name) {
            $success = false;
            $error = JsonResponse::missingParam('name');
        }
        elseif (!$animalTypeID) {
            $success = false;
            $error = JsonResponse::missingParam('animalTypeID');
        }
        elseif (!$breed) {
            $success = false;
            $error = JsonResponse::missingParam('breed');
        }
        elseif (!$gender) {
            $success = false;
            $error = JsonResponse::missingParam('gender');
        }
        elseif (!$dateOfBirth) {
            $success = false;
            $error = JsonResponse::missingParam('dateOfBirth');
        }
        elseif (!$weight) {
            $success = false;
            $error = JsonResponse::missingParam('weight');
        }
        elseif (!$height) {
            $success = false;
            $error = JsonResponse::missingParam('height');
        }
        elseif (!$length) {
            $success = false;
            $error = JsonResponse::missingParam('length');
        }
        elseif (!DateTime::createFromFormat('Y-m-d', $dateOfBirth)) {
            $success = false;
            $error = JsonResponse::userError('Invalid date.');
        }
        elseif (!$this->app['api.animalservice']->CheckBreedExists($breed)) {
            $success = false;
            $error = JsonResponse::userError('Invalid breed.');
        }
        else {
            $parameters = Array(
                'name' => $name,
                'animalTypeID' => $animalTypeID,
                'breed' => $breed,
                'gender' => $gender,
                'dateOfBirth' => $dateOfBirth,
                'weight' => $weight,
                'height' => $height,
                'length' => $length
            );
        }

        return new RequestValidationResult($success, $parameters, $error);
    }

    public function ValidateCatPetCreationRequest(Request $request)
    {
        $success = true;
        $error = null;
        $parameters = null;

        // Get parameters
        $declawed = $request->request->get('declawed');
        $outdoor = $request->request->get('outdoor');
        $fixed = $request->request->get('fixed');

        // Validate parameters
        if (!$declawed) {
            $success = false;
            $error = JsonResponse::missingParam('declawed');
        }
        elseif (!$outdoor) {
            $success = false;
            $error = JsonResponse::missingParam('outdoor');
        }
        elseif (!$fixed) {
            $success = false;
            $error = JsonResponse::missingParam('fixed');
        }
        else {
            $parameters = Array(
                'declawed' => $declawed,
                'outdoor' => $outdoor,
                'fixed' => $fixed
            );
        }

        return new RequestValidationResult($success, $parameters, $error);
    }

    public function ValidateSetPetAccessibilityRequest(Request $request)
    {
        $success = true;
        $error = null;
        $parameters = null;

        // Get parameters
        $email = $request->request->get('email');
        $petID = $request->request->get('petID');
        $access = $request->request->get('access');

        // Validate parameters
        if (!$email) {
            $success = false;
            $error = JsonResponse::missingParam('email');
        }
        elseif (!$petID) {
            $success = false;
            $error = JsonResponse::missingParam('petID');
        }
        elseif (!$access) {
            $success = false;
            $error = JsonResponse::missingParam('access');
        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $success = false;
            $error = JsonResponse::userError('Invalid email');
        }
        elseif (!$this->app['api.dbtypes']->IsValidPetAccessibilityValue($access)) {
            $success = false;
            $error = JsonResponse::userError('Invalid accessibility value');
        }
        else {
            $parameters = Array(
                'petID' => $petID,
                'email' => $email,
                'access' => $access
            );
        }

        return new RequestValidationResult($success, $parameters, $error);
    }
}
