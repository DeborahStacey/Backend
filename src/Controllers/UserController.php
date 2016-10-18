<?php

namespace WellCat\Controllers;

use Silex\Application;
use PDO;
use WellCat\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController
{

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->app['session']->start();
    }

    public function register(Request $request)
    {
        $user = $request->request->get('email');
        $password = $request->request->get('password');
        $first = $request->request->get('firstName');
        $last = $request->request->get('lastName');

        if (!$user) {
            return JsonResponse::missingParam('email');
        } elseif (!$password) {
            return JsonResponse::missingParam('password');
        } elseif (!$first) {
            return JsonResponse::missingParam('firstName');
        } elseif (!$last) {
            return JsonResponse::missingParam('lastName');
        } elseif (!filter_var($user, FILTER_VALIDATE_EMAIL)) {
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
            ':email' => $user
        ));

        /**
         * if success is not good the querry failed.
         */
        if (!$success) {
            return JsonReponse::userError('Unable to register');
        } 
        /**
         * Checks to see if there were any returned values and if so the email already exists
         */
        else if ($stmt->fetch(PDO::FETCH_ASSOC) != false) {
            $body = array(
                'success' => 'false',
                'error' => 'Email already in use'
            );
            return new JsonResponse($body, 404);
        }


        $encryptedPassword = $this->app['api.auth']->encryptPass($password);
        
        $sql = 'INSERT INTO account (email, password, firstname, lastname)
            VALUES (:email, :password, :first, :last)';
        $stmt = $this->app['db']->prepare($sql);
        $success = $stmt->execute(array(
            ':email' => $user,
            ':password' => $encryptedPassword,
            ':first' => $first,
            ':last' => $last
        ));

        if ($success) {
            return new JsonResponse();
        } else {
            return JsonReponse::userError('Unable to register');
        }
    }

}