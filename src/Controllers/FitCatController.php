<?php
namespace WellCat\Controllers;

use PDO;
use DateTime;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WellCat\JsonResponse;

class FitCatController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->app['session']->start();
    }

    public function Weight(Request $request)
    {
        $petID = $request->request->get('petID');
        $weight = $request->request->get('weight');
        $date = $request->request->get('date');
        
        // Validate parameters
        if (!$petID) {
            return JsonResponse::missingParam('petID');
        }
        elseif (!$weight) {
            return JsonResponse::missingParam('weight');
        }
        elseif (!$date) {
            return JsonResponse::missingParam('date');
        }
        elseif (!is_int($petID)) {
            return JsonResponse::userError('Invalid petID');
        }
        elseif (!is_real($weight) && !is_int($weight)) {
            return JsonResponse::userError('Invalid weight');
        }
        elseif (!DateTime::createFromFormat('Y-m-d', $date)) {
            return JsonResponse::userError('Invalid date');
        }
        //first checks to see if petID is accessable by the user (write-access).
        elseif ($this->CheckPetOwnership($petID,TRUE) < 2) {
            $body = array(
                'success' => false,
                'error' => 'Pet not accessible'
            );
            return new JsonResponse($body, 404);
        }

        $sql = 'UPDATE pet SET weight = :weight, lastupdated = now() WHERE petid = :petID';
        $stmt = $this->app['db']->prepare($sql);
        $success = $stmt->execute(array(
            ':weight' => $weight,
            ':petID' => $petID
        ));

        // Check if a row exists in the table for the day, for that specific pet
        $sql = 'SELECT NULL FROM fitcat WHERE petid = :petID AND date = :date';
        $stmt = $this->app['db']->prepare($sql);
        $stmt->execute(array(
            ':petID' => $petID,
            ':date' => $date
        ));

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($result) {
            $sql = 'UPDATE fitcat SET weight = :weight WHERE petid = :petID AND date = :date';
        }
        else {
            $sql = 'INSERT INTO fitcat (petid, weight, date) VALUES (:petID, :weight, :date)';
        }
        $stmt = $this->app['db']->prepare($sql);
        $success = $stmt->execute(array(
            ':petID' => $petID,
            ':weight' => $weight,
            ':date' => $date
        ));

        if ($success) {
            return new JsonResponse();
        } 
        else {
            return JsonReponse::userError('Unable to update weight');
        }
    }
    
    public function Steps(Request $request)
    {
        $petID = $request->request->get('petID');
        $steps = $request->request->get('steps');
        $date = $request->request->get('date');
        
        // Validate parameters
        if (!$petID) {
            return JsonResponse::missingParam('petID');
        }
        elseif (!$steps) {
            return JsonResponse::missingParam('steps');
        }
        elseif (!$date) {
            return JsonResponse::missingParam('date');
        }
        elseif (!is_int($petID)) {
            return JsonResponse::userError('Invalid petID');
        }
        elseif (!is_int($steps)) {
            return JsonResponse::userError('Invalid steps');
        }
        elseif (!DateTime::createFromFormat('Y-m-d', $date)) {
            return JsonResponse::userError('Invalid date');
        }
        //first checks to see if petID is accessable by the user (write-access).
        elseif ($this->CheckPetOwnership($petID,TRUE) < 2) {
            $body = array(
                'success' => false,
                'error' => 'Pet not accessible'
            );
            return new JsonResponse($body, 404);
        }

        // Check if a row exists in the table for the day, for that specific pet 
        $sql = 'SELECT NULL FROM fitcat WHERE petid = :petID AND date = :date';
        $stmt = $this->app['db']->prepare($sql);
        $stmt->execute(array( 
            ':petID' => $petID,
            ':date' => $date
        ));

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
        if ($result) {
            $sql = 'UPDATE fitcat SET steps = :steps WHERE petid = :petID AND date = :date';
        }
        else {      
            $sql = 'INSERT INTO fitcat (petid, steps, date) VALUES (:petID, :steps, :date)';
        }
        $stmt = $this->app['db']->prepare($sql);
        $success = $stmt->execute(array(
            ':petID' => $petID,
            ':steps' => $steps,
            ':date' => $date
        ));

        if ($success) {
            return new JsonResponse();
        } 
        else {
            return JsonReponse::userError('Unable to update steps');
        }
    }
    
    public function Water(Request $request)
    {
        $petID = $request->request->get('petID');
        $amount = $request->request->get('amount');
        $date = $request->request->get('date');
        
        // Validate parameters
        if (!$petID) {
            return JsonResponse::missingParam('petID');
        }
        elseif (!$amount) {
            return JsonResponse::missingParam('amount');
        }
        elseif (!$date) {
            return JsonResponse::missingParam('date');
        }
        elseif (!is_int($petID)) {
            return JsonResponse::userError('Invalid petID');
        }
        elseif (!is_real($amount) && !is_int($amount)) {
            return JsonResponse::userError('Invalid water amount');
        }
        elseif (!DateTime::createFromFormat('Y-m-d', $date)) {
            return JsonResponse::userError('Invalid date');
        }
        //first checks to see if petID is accessable by the user (write-access).
        elseif ($this->CheckPetOwnership($petID,TRUE) < 2) {
            $body = array(
                'success' => false,
                'error' => 'Pet not accessible'
            );
            return new JsonResponse($body, 404);
        }

        // Check if a row exists in the table for the day, for that specific pet 
        $sql = 'SELECT NULL FROM fitcat WHERE petid = :petID AND date = :date';
        $stmt = $this->app['db']->prepare($sql);
        $stmt->execute(array(
            ':petID' => $petID,
            ':date' => $date
        ));

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($result) {
            $sql = 'UPDATE fitcat SET waterconsumption = :amount WHERE petid = :petID AND date = :date';
        }
        else {      
            $sql = 'INSERT INTO fitcat (petid, waterconsumption, date) VALUES (:petID, :amount, :date)';
        }
        $stmt = $this->app['db']->prepare($sql);
        $success = $stmt->execute(array(
            ':petID' => $petID,
            ':amount' => $amount,
            ':date' => $date
        )); 

        if ($success) {
            return new JsonResponse();
        } 
        else {
            return JsonReponse::userError('Unable to update water');
        }
    }
  
    public function Food(Request $request)
    {
        $petID = $request->request->get('petID');
        $brand = $request->request->get('brand');
        $amount = $request->request->get('amount');
        $description = $request->request->get('description');
        $date = $request->request->get('date');
        
        // Validate parameters
        if (!$petID) {
            return JsonResponse::missingParam('petID');
        }
        elseif (!$brand) {
            return JsonResponse::missingParam('brand');
        }
        elseif (!$amount) {
            return JsonResponse::missingParam('amount');
        }
        elseif (!$description) {
            return JsonResponse::missingParam('description');
        }
        elseif (!$date) {
            return JsonResponse::missingParam('date');
        }
        elseif (!is_int($petID)) {
            return JsonResponse::userError('Invalid petID');
        }
        elseif (!is_real($amount) && !is_int($amount)) {
            return JsonResponse::userError('Invalid food amount');
        }
        elseif (!DateTime::createFromFormat('Y-m-d', $date)) {
            return JsonResponse::userError('Invalid date');
        }
        //first checks to see if petID is accessable by the user (write-access).
        elseif ($this->CheckPetOwnership($petID,TRUE) < 2) {
            $body = array(
                'success' => false,
                'error' => 'Pet not accessible'
            );
            return new JsonResponse($body, 404);
        }

        // Check if a row exists in the table for the day, for that specific pet 
        $sql = 'SELECT NULL FROM fitcat WHERE petid = :petID AND date = :date';
        $stmt = $this->app['db']->prepare($sql);
        $stmt->execute(array(
            ':petID' => $petID,
            ':date' => $date
        ));

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($result) {
            $sql = 'UPDATE fitcat SET foodconsumption = :amount, foodbrand = :brand, description = :description WHERE petid = :petID AND date = :date';
        }
        else {      
            $sql = 'INSERT INTO fitcat (petid, foodconsumption, foodbrand, description, date) VALUES (:petID, :amount, :brand, :description, :date)';
        }
        $stmt = $this->app['db']->prepare($sql);
        $success = $stmt->execute(array(
            ':petID' => $petID,
            ':amount' => $amount,
            ':brand' => $brand,
            ':description' => $description,
            ':date' => $date
        ));

        if ($success) {
            return new JsonResponse();
        } 
        else {
            return JsonReponse::userError('Unable to update food');
        }
    }   

    public function Pets()
    {
        $user = $this->app['session']->get('user');
        
        //Get all pet/fitcat data for personal cats
        $sql = 'SELECT p.petid, p.name, p.gender, p.breed, a.firstname, a.lastname, p.lastupdated FROM pet p INNER JOIN account a ON p.ownerid = a.userid WHERE p.ownerid = :user AND p.fitcat=true';
        $stmt = $this->app['db']->prepare($sql);
        $stmt->execute(array(
            ':user' => $user['userId']
        ));
        $personal = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        //Get all pet/fitcat data for shared cats
        $sql = 'SELECT p.petid, p.name, p.gender, p.breed, a.firstname, a.lastname, p.lastupdated FROM pet p INNER JOIN account a ON p.ownerid = a.userid WHERE p.fitcat=true AND p.petid IN (SELECT f.petid FROM accessibility f WHERE f.userid = :user)';
        $stmt = $this->app['db']->prepare($sql);
        $stmt->execute(array(
            ':user' => $user['userId']
        ));
        $shared = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if ($personal || $shared) {
            $body = array(
                'success' => true,
                'personal' => $personal,
                'shared' => $shared,
            );

            return new JsonResponse($body, 200);
        }
        else {
            $body = array(
                'success' => false,
                'error' => 'No pets found'
            );
            return new JsonResponse($body, 404);
        }
    }

    public function View($petID)
    {
        //first checks to see if petID is accessable by the user.
        if (!$this->CheckPetOwnership($petID,TRUE)) {
            $body = array(
                'success' => false,
                'error' => 'Pet not found'
            );
            return new JsonResponse($body, 404);
        }
        
        $sql = 'SELECT weight, steps, waterconsumption, foodconsumption, foodbrand, description, date FROM fitcat WHERE petid = :petID';
        $stmt = $this->app['db']->prepare($sql);
        $stmt->execute(array( 
            ':petID' => $petID
        ));

        $fitcatResults = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        $sql = 'SELECT petid, name, gender, breed, weight, lastupdated FROM pet WHERE petid = :petID AND fitcat = TRUE';
        $stmt = $this->app['db']->prepare($sql);
        $stmt->execute(array( 
            ':petID' => $petID
        ));

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($result) {
            $body = array(
                'success' => true,
                'pet' => $result,
                'fitcat' => $fitcatResults
            );

            return new JsonResponse($body,200);
        }
        else {
            $body = array(
                'success' => false,
                'error' => 'Pet not found'
            );
            return new JsonResponse($body, 404);
        }

    }

    public function Register(Request $request)
    {
        //gets parameters
        $petID = $request->request->get('petID');

        //validates parameters
        if (!$petID) {
            return JsonResponse::missingParam('petID');
        }
        elseif (!is_int($petID)) {
            return JsonResponse::userError('Invalid petID');
        }
        elseif ($this->CheckPetOwnership($petID,FALSE) != 3) {
            $body = array(
                'success' => false,
                'error' => 'Pet not found'
            );
            return new JsonResponse($body, 404);
        }

        $sql = 'UPDATE pet SET fitcat = TRUE WHERE petid = :petID';
        $stmt = $this->app['db']->prepare($sql);
        $success = $stmt->execute(array(
            ':petID' => $petID
        ));

        if ($success) {
            return new JsonResponse(null,201);
        }
        else {
            return JsonResponse::serverError();
        }

    }


    public function DeRegister(Request $request)
    {
        //gets parameters
        $petID = $request->request->get('petID');

        //validates parameters
        if (!$petID) {
            return JsonResponse::missingParam('petID');
        }
        elseif (!is_int($petID)) {
            return JsonResponse::userError('Invalid petID');
        }
        elseif ($this->CheckPetOwnership($petID,TRUE) != 3) {
            $body = array(
                'success' => false,
                'error' => 'Pet not found'
            );
            return new JsonResponse($body, 404);
        }

        $sql = 'UPDATE pet SET fitcat = FALSE WHERE petid = :petID';
        $stmt = $this->app['db']->prepare($sql);
        $success = $stmt->execute(array(
            ':petID' => $petID
        ));

        if ($success) {
            return new JsonResponse(null,201);
        }
        else {
            return JsonResponse::serverError();
        }

    }

    /**
     * Function checks the ownership of a pet based on the current user
     * returns 3 if owner, 2 if writeable access, 1 if read only, and false if not accessable.
     * @param [int] $petID holds the id value of a pet.
     * @param [bool] $fitcat holds a true or false value based on if the search should be limited to fitcats or not.
     */
    private function CheckPetOwnership($petID, $fitcat)
    {
        $addition = ' ';
        if ($fitcat) {
            $addition = " AND p.fitcat = true";
        }

        $user = $this->app['session']->get('user');

        $sql = 'SELECT NULL FROM pet p WHERE p.petid = :petID AND p.ownerid = :userID'.$addition;
        $stmt = $this->app['db']->prepare($sql);
        $stmt->execute(array( 
            ':petID' => $petID,
            ':userID' => $user['userId']
        ));

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($result) {
            return 3;
        }
        else {
            $sql = 'SELECT a.access FROM accessibility a INNER JOIN pet p ON a.petid = p.petid WHERE a.petid = :petID AND a.userid = :userID'.$addition;
            $stmt = $this->app['db']->prepare($sql);
            $stmt->execute(array( 
                ':petID' => $petID,
                ':userID' => $user['userId']
            ));
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            //if a result is found deal with it accordingly otherwise return 0;
            if ($result) {
                if($result['access'] == 'write') {
                    return 2;
                }
                else {
                    return 1;
                }
            }
            else {
                return 0;
            }
        }
    }
}





