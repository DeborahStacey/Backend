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
    }

    public function Weight()
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

		$sql = 'UPDATE pet SET weight = :amount WHERE petid = :petid'
		$stmt = $this->app['db']->prepare($sql);
		$success = $stmt->execute(array(
                ':amount' => $amount,
                ':petid' => $petid
        ));

        if ($success) {
            return new JsonResponse();
        } 
        else {
            return JsonReponse::userError('Unable to update weight');
        }		

        //return some sort of JsonResponse If you want to know more review the JsonResponse Wiki
        return new JsonResponse();
    }
    
    public function Steps()
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
        $stmt->execute(array( ':date' => $date));

		$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		
        if ($result) {
			$sql = 'UPDATE fitcat SET steps = :amount WHERE petid = :petid'
			$stmt = $this->app['db']->prepare($sql);
			$success = $stmt->execute(array(
            	':amount' => $amount,
            	':petid' => $petid
        	));
        }
        else {		
			$sql = 'INSERT INTO fitcat (petid, steps, activerhours, inactivehours, waterconsumption, foodconsumption, foodbrand, description, date)
				VALUES (:petid, :amount, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, :date)';
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
    
    public function Water()
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
        $stmt->execute(array( ':date' => $date));
        
		$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		
        if ($result) {
			$sql = 'UPDATE fitcat SET waterconsumption = :amount WHERE petid = :petid'
			$stmt = $this->app['db']->prepare($sql);
			$success = $stmt->execute(array(
            	':amount' => $amount,
                ':petid' => $petid
        	));
        }
        else {		
			$sql = 'INSERT INTO fitcat (petid, steps, activerhours, inactivehours, waterconsumption, foodconsumption, foodbrand, description, date)
				VALUES (:petid, DEFAULT, DEFAULT, DEFAULT, :amount, DEFAULT, DEFAULT, DEFAULT, :date)';
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
  
    public function Food()
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
        $stmt->execute(array( ':date' => $date));
        
		$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		
        if ($result) {
			$sql = 'UPDATE fitcat SET foodconsumption = :amount, brand = :brand, description = :description WHERE petid = :petid'
			$stmt = $this->app['db']->prepare($sql);
			$success = $stmt->execute(array(
                ':amount' => $amount,
                ':brand' => $brand,
                ':description' => $description,
                ':petid' => $petid
			));
        }
        else {		
			$sql = 'INSERT INTO fitcat (petid, steps, activerhours, inactivehours, waterconsumption, foodconsumption, foodbrand, description, date)
				VALUES (:petid, DEFAULT, DEFAULT, DEFAULT, , :amount, :brand, :description, :date)';
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
}





