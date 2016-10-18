<?php
namespace WellCat\Services;

use Silex\Application;

class AuthenticationService
{
    protected $app;
    private $salt;

    public function __construct(Application $app, string $salt)
    {
        $this->app = $app;
        $this->salt = $salt;
    }

    public function passwordRequirements(string $password)
    {
        //TODO:
        //need to input a regular expression formula for passwords we accept and confirm with the rest of
        //the teams the requirements. (need one of 4 or all? caps, numbers, lowercase, special character)
        if (!preg_match('', $password)) {
            return false;
        }
        return true;
    }

    public function encryptPass(string $password)
    {
        $password = $password.$this->salt;
        return password_hash($password, PASSWORD_DEFAULT);
    }
}