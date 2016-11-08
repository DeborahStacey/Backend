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
}





