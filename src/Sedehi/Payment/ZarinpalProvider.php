<?php
/**
 * Created by PhpStorm.
 * User: Navid Sedehi
 * Date: 6/1/2015
 * Time: 7:25 PM
 */

namespace Sedehi\Payment;

use SoapClient;

class ZarinpalProvider {

    private $merchantID;

    public function __construct($merchantID) {

        $this->merchantID = $merchantID;
    }

    public function request($amount, $url, $callbackURL, $description) {

        $client = new SoapClient($url, array('encoding' => 'UTF-8'));

        $result = $client->PaymentRequest(
            array(
                'MerchantID'  => $this->merchantID,
                'Amount'      => $amount,
                'CallbackURL' => $callbackURL,
                'Description' => $description,
                'Mobile'      => '',
                'Email'       => ''
            )
        );

        return $result;

    }

    public function verify($url, $trans_id, $id_get) {

    }
}