<?php

return array(
    'table'            => 'payment_transaction',

    'default_provider' => 'payline',

    'currency'         => 'rial',

    'callback_url'     => 'http://develop.dev:8080/public/return',

    'providers'        => array(

        'payline'  => array(
            'api'                => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
            'request_url'        => 'http://payline.ir/payment/gateway-send',
            'second_request_url' => 'http://payline.ir/payment/gateway-',
            'get_request_url'    => 'http://payline.ir/payment/gateway-result-second',
            'test'               => array(
                'on'                 => true,
                'api'                => 'adxcv-zzadq-polkjsad-opp13opoz-1sdf455aadzmck1244567',
                'request_url'        => 'http://payline.ir/payment-test/gateway-send',
                'second_request_url' => 'http://payline.ir/payment-test/gateway-',
                'get_request_url'    => 'http://payline.ir/payment-test/gateway-result-second',
            )
        ),

        'zarinpal' => array(
            'MerchantID'  => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',

            'payment_url' => 'https://www.zarinpal.com/pg/StartPay/',

            'server'      => 'iran',

            'servers'     => array(
                'germany' => array(
                    'request_url' => 'https://de.zarinpal.com/pg/services/WebGate/wsdl',
                ),
                'iran'    => array(
                    'request_url' => 'https://ir.zarinpal.com/pg/services/WebGate/wsdl'
                )
            ),

        ),
    ),


);
