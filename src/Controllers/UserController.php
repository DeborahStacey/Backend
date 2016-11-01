<?php

namespace WellCat\Controllers;

use Silex\Application;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use WellCat\JsonResponse;

class UserController
{

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->app['session']->start();
    }

    public function Authenticate()
    {
        $data = array(
            'success' => true,
            'message' => 'Successfully authenticated'
        );
        return new JsonResponse($data, 200);
    }

    public function Register(Request $request)
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $first = $request->request->get('firstName');
        $last = $request->request->get('lastName');
        $address = $request->request->get('address');

        if (!$email) {
            return JsonResponse::missingParam('email');
        }
        elseif (!$password) {
            return JsonResponse::missingParam('password');
        }
        elseif (!$first) {
            return JsonResponse::missingParam('firstName');
        }
        elseif (!$last) {
            return JsonResponse::missingParam('lastName');
        }
        elseif (!$address) {
            return JsonResponse::missingParam('address');
        }
        elseif (!$address['street']) {
            return JsonResponse::missingParam('address(street)');
        }
        elseif (!$address['unit']) {
            return JsonResponse::missingParam('address(unit)');
        }
        elseif (!$address['city']) {
            return JsonResponse::missingParam('address(city)');
        }
        elseif (!$address['postalCode']) {
            return JsonResponse::missingParam('address(postalCode)');
        }
        elseif (!$address['locationID']) {
            return JsonResponse::missingParam('address(locationID)');
        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return JsonResponse::userError('Invalid email');
        } 

        /*
        Will add in later.

        elseif (!$this->app['api.auth']->passwordRequirements($pass)) {
            return JsonResponse::userError('Password requirements not met');
        }
        */
       
        $sql = 'SELECT email FROM account WHERE email = :email';
        $stmt = $this->app['db']->prepare($sql);
        $success = $stmt->execute(array(
            ':email' => $email
        ));

        //if success is not good the querry failed.
        if (!$success) {
            return JsonReponse::userError('Unable to register');
        } 
        //Checks to see if there were any returned values and if so the email already exists
        else if ($stmt->fetch(\PDO::FETCH_ASSOC) != false) {
            $body = array(
                'success' => false,
                'error' => 'Email already in use'
            );
            return new JsonResponse($body, 404);
        }

        //attempts to register given user address if fails returns -1. If successful returns a value >= 0
        $addressID = $this->app['api.address']->Register($address);
        if ($addressID == -1) {
            return JsonResponse::userError('Unable to register address');
        }

        $encryptedPassword = $this->app['api.auth']->EncryptPassword($password);
        
        $sql = 'INSERT INTO account (addressid, email, password, firstname, lastname)
            VALUES (:addressid, :email, :password, :first, :last)';
        $stmt = $this->app['db']->prepare($sql);
        $success = $stmt->execute(array(
            ':addressid' => (int)$addressID,
            ':email' => $email,
            ':password' => $encryptedPassword,
            ':first' => $first,
            ':last' => $last
        ));

        if ($success) {
            return new JsonResponse();
        } 
        else {
            return JsonReponse::userError('Unable to register');
        }
    }


    public function Login(Request $request)
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        if (!$email) {
            return JsonResponse::missingParam('email');
        } 
        elseif (!$password) {
            return JsonResponse::missingParam('password');
        } 
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return JsonResponse::userError('Invalid email');
        }

        $success = $this->app['api.auth']->Authenticate($email, $password);

        if ($success) {
            return new JsonResponse();
        } 
        else {
            return JsonResponse::authError('Incorrect email or password');
        }
    }

    public function ChangePassword(Request $request)
    {
        $oldPassword = $request->request->get('currentPassword');
        $newPassword = $request->request->get('newPassword');
        $user = $this->app['session']->get('user');
        
        if (!$oldPassword) {
            return JsonResponse::missingParam('currentPassword');
        }
        elseif (!$newPassword) {
            return JsonResponse::missingParam('newPassword');
        }

        /*
        Will add in later.

        elseif (!$this->app['api.auth']->passwordRequirements($newPassword)) {
            return JsonResponse::userError('Password requirements not met');
        }
        */
        
        //Checks password to make sure it is valid for current user.
        $success = $this->app['api.auth']->CheckPassword($user['email'], $oldPassword);

        if ($success) {
            $encryptedPassword = $this->app['api.auth']->EncryptPassword($newPassword);

            $sql = 'UPDATE account SET password = :pass WHERE email = :email';
            $stmt = $this->app['db']->prepare($sql);
            $success = $stmt->execute(array(
                ':pass' => $encryptedPassword,
                ':email' => $user['email']
            ));

            return new JsonResponse();
        } 
        else {
            return JsonResponse::authError('Invalid User or Password');
        }
    }

}