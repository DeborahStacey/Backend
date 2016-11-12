<?php
namespace WellCat\Controllers;

use PDO;
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
    	$petid = $request->request->get('petid');
    	$date= $request->request->get('date');
        $amount = $request->request->get('amount');
		
        // Validate parameters
        if (!$petid) {
            return JsonResponse::missingParam('petid');
        }
 	    elseif (!$date) {
            return JsonResponse::missingParam('date');
        }       
        elseif (!$amount) {
            return JsonResponse::missingParam('amount');
        }

    	$sql = 'UPDATE pet SET weight = :amount, lastupdated = :date WHERE petid = :petid';
    	$stmt = $this->app['db']->prepare($sql);
    	$success = $stmt->execute(array(
        	':amount' => $amount,
            ':date' => $date,
            ':petid' => $petid
        ));

        // Check if a row exists in the table for the day, for that specific pet
        $sql ='SELECT * FROM fitcat WHERE petid = :petid AND date = :date';
        $stmt= $this->app['db']->prepare($sql);
        $stmt->execute(array(
            ':petid' => $petid,
            ':date' => $date
        ));

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if ($result) {
            $sql = 'UPDATE fitcat SET weight = :amount WHERE petid = :petid AND date = :date';
            $stmt = $this->app['db']->prepare($sql);
            $success = $stmt->execute(array(
                ':amount' => $amount,
                ':date' => $date,
                ':petid' => $petid
            ));
        }
        else {
            $sql = 'INSERT INTO fitcat (petid, weight, date) VALUES (:petid, :amount, :date)';
            $stmt = $this->app['db']->prepare($sql);
            $success = $stmt->execute(array(
                ':petid' => $petid,
                ':amount' => $amount,
                ':date' => $date
            ));
        }

        if ($success) {
            return new JsonResponse();
        } 
        else {
            return JsonReponse::userError('Unable to update weight');
        }		

        //return some sort of JsonResponse If you want to know more review the JsonResponse Wiki
        return new JsonResponse();
    }
    
    public function Steps(Request $request)
    {
    	$petid = $request->request->get('petid');
    	$date= $request->request->get('date');
        $amount = $request->request->get('amount');
		
        // Validate parameters
    	if (!$petid) {
            return JsonResponse::missingParam('petid');
        }
     	elseif (!$date) {
            return JsonResponse::missingParam('date');
        }       
    	elseif (!$amount) {
            return JsonResponse::missingParam('amount');
        }
    	
    	// Check if a row exists in the table for the day, for that specific pet 
    	$sql ='SELECT * FROM fitcat WHERE petid = :petid AND date = :date';
        $stmt= $this->app['db']->prepare($sql);
        $stmt->execute(array( 
            ':petid' => $petid,
            ':date' => $date
        ));

    	$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    		
        if ($result) {
            $sql = 'UPDATE fitcat SET steps = :amount WHERE petid = :petid AND date = :date';
            $stmt = $this->app['db']->prepare($sql);
            $success = $stmt->execute(array(
                ':amount' => $amount,
                ':date' => $date,
                ':petid' => $petid
            ));
        }
        else {		
            $sql = 'INSERT INTO fitcat (petid, steps, date) VALUES (:petid, :amount, :date)';
            $stmt = $this->app['db']->prepare($sql);
            $success = $stmt->execute(array(
                ':petid' => $petid,
                ':amount' => $amount,
                ':date' => $date
            ));	
        }

        if ($success) {
            return new JsonResponse();
        } 
        else {
            return JsonReponse::userError('Unable to update steps');
       	}			

        //return some sort of JsonResponse If you want to know more review the JsonResponse Wiki
        return new JsonResponse();
    }
    
    public function Water(Request $request)
    {
    	$petid = $request->request->get('petid');
    	$date= $request->request->get('date');
        $amount = $request->request->get('amount');
		
        // Validate parameters
    	if (!$petid) {
            return JsonResponse::missingParam('petid');
        }
     	elseif (!$date) {
            return JsonResponse::missingParam('date');
        }       
    	elseif (!$amount) {
            return JsonResponse::missingParam('amount');
        }
    
        // Check if a row exists in the table for the day, for that specific pet 
        $sql ='SELECT * FROM fitcat WHERE petid = :petid AND date = :date';
        $stmt= $this->app['db']->prepare($sql);
        $stmt->execute(array(
            ':petid' => $petid,
            ':date' => $date
        ));

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		
        if ($result) {
            $sql = 'UPDATE fitcat SET waterconsumption = :amount WHERE petid = :petid AND date = :date';
            $stmt = $this->app['db']->prepare($sql);
            $success = $stmt->execute(array(
                ':amount' => $amount,
                ':date' => $date,
                ':petid' => $petid
            ));
        }
        else {		
            $sql = 'INSERT INTO fitcat (petid, waterconsumption, date) VALUES (:petid, :amount, :date)';
            $stmt = $this->app['db']->prepare($sql);
            $success = $stmt->execute(array(
                ':petid' => $petid,
                ':amount' => $amount,
                ':date' => $date
            ));	   
        }

        if ($success) {
            return new JsonResponse();
        } 
        else {
            return JsonReponse::userError('Unable to update steps');
       	}			

        //return some sort of JsonResponse If you want to know more review the JsonResponse Wiki
        return new JsonResponse();
    }
  
    public function Food(Request $request)
    {
    	$petid = $request->request->get('petid');
    	$date= $request->request->get('date');
        $amount = $request->request->get('amount');
    	$brand = $request->request->get('brand');
    	$description = $request->request->get('description');
		
        // Validate parameters
    	if (!$petid) {
            return JsonResponse::missingParam('petid');
        }
     	elseif (!$date) {
            return JsonResponse::missingParam('date');
        }       
    	elseif (!$amount) {
            return JsonResponse::missingParam('amount');
        }
     	elseif (!$brand) {
            return JsonResponse::missingParam('brand');
        }       
    	elseif (!$description) {
            return JsonResponse::missingParam('description');
        }
        
	// Check if a row exists in the table for the day, for that specific pet 
        $sql ='SELECT * FROM fitcat WHERE petid = :petid AND date = :date';
        $stmt= $this->app['db']->prepare($sql);
        $stmt->execute(array(
            ':petid' => $petid,
            ':date' => $date
        ));

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		
        if ($result) {
            $sql = 'UPDATE fitcat SET foodconsumption = :amount, foodbrand = :brand, description = :description WHERE petid = :petid AND date = :date';
            $stmt = $this->app['db']->prepare($sql);
            $success = $stmt->execute(array(
                ':amount' => $amount,
                ':brand' => $brand,
                ':description' => $description,
                ':date' => $date,
                ':petid' => $petid
            ));
        }
        else {		
            $sql = 'INSERT INTO fitcat (petid, steps, date) VALUES (:petid, :amount, :date)';
            $stmt = $this->app['db']->prepare($sql);
            $success = $stmt->execute(array(
                ':petid' => $petid,
                ':amount' => $amount,
                ':brand' => $brand,
                ':description' => $description,
                ':date' => $date
            ));	
        }

        if ($success) {
            return new JsonResponse();
        } 
        else {
            return JsonReponse::userError('Unable to update steps');
       	}		

        //return some sort of JsonResponse If you want to know more review the JsonResponse Wiki
        return new JsonResponse();
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
                'message' => 'No pets found'
            );
            return new JsonResponse($body, 404);
        }
    }

    public function View($petID)
    {
        //first checks to see if petID is accessable by the user.
        if (!$this->CheckPetOwnership($petID)) {
            $body = array(
                'success' => false,
                'message' => 'Pet not found'
            );
            return new JsonResponse($body, 404);
        }

        //
        //f.steps, f.activerhours, f.inactivehours, f.waterconsumption, f.foodconsumption, f.foodbrand, f.description, f.date, f.weight
        //
        
        $sql ='SELECT p.petid, p.name, p.gender, p.breed, p.lastupdated FROM pet p WHERE p.petid = :petID';
        $stmt= $this->app['db']->prepare($sql);
        $stmt->execute(array( 
            ':petID' => $petID
        ));

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        $body = array(
            'success' => true,
            'pet' => $result
        );

        return new JsonResponse($body,200);

    }

    public function Register(Request $request)
    {
        //gets parameters
        $petID = $request->request->get('petID');

        //validates parameters
        if (!$petID) {
            return JsonResponse::missingParam('petID');
        }
        elseif ($this->CheckPetOwnership($petID) != 3) {
            $body = array(
                'success' => false,
                'message' => 'Pet not found'
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
        elseif ($this->CheckPetOwnership($petID) != 3) {
            $body = array(
                'success' => false,
                'message' => 'Pet not found'
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
     */
    private function CheckPetOwnership($petID)
    {
        $user = $this->app['session']->get('user');

        $sql ='SELECT NULL FROM pet WHERE petid = :petID AND ownerid = :userID';
        $stmt= $this->app['db']->prepare($sql);
        $stmt->execute(array( 
            ':petID' => $petID,
            ':userID' => $user['userId']
        ));

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($result) {
            return 3;
        }
        else {
            $sql ='SELECT access FROM accessibility WHERE petid = :petID AND userid = :userID';
            $stmt= $this->app['db']->prepare($sql);
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





