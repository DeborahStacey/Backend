<?php
namespace WellCat\Services;

use Silex\Application;

class AddressService
{

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function Register($address)
    {
        $sql = 'INSERT INTO address (locationid, city, street, unit, postalcode) 
            VALUES (:locationID, :city, :street, :unit, :postalCode)';
        $stmt = $this->app['db']->prepare($sql);
        $success = $stmt->execute(array(
            ':locationID' => $address['locationID'],
            ':city' => $address['city'],
            ':street' => $address['street'],
            ':unit' => $address['unit'],
            ':postalCode' => $address['postalCode']
        ));

        //if successfull returns the last added id to the caller. If it fails returns -1 because id should be a sequence incrementing from either 0, or 1 therefore will never be -1.
        if ($success) {
            return $this->app['db']->lastInsertId('address_addressid_seq');
        } else {
            return -1;
        }
    }
}