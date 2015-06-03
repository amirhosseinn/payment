<?php
/**
 * Created by PhpStorm.
 * User: Navid Sedehi
 * Date: 6/2/2015
 * Time: 1:02 AM
 */

namespace Sedehi\Payment;

use Config,
    Exception;

class PaymentConfig {

    public static function get($provider) {

        $config = array();
        switch($provider) {
            case 'payline':

                $config['api']                = Config::get('payment::providers.payline.api');
                $config['request_url']        = Config::get('payment::providers.payline.request_url');
                $config['second_request_url'] = Config::get('payment::providers.payline.second_request_url');
                $config['verify_request_url'] = Config::get('payment::providers.payline.get_request_url');
                if(Config::get('payment::providers.payline.test.on')) {
                    $config['api']                = Config::get('payment::providers.payline.test.api');
                    $config['request_url']        = Config::get('payment::providers.payline.test.request_url');
                    $config['second_request_url'] = Config::get('payment::providers.payline.test.second_request_url');
                    $config['verify_request_url'] = Config::get('payment::providers.payline.test.get_request_url');
                }
                break;
            case 'zarinpal':
                $config['server']      = Config::get('payment::providers.zarinpal.server');
                $config['payment_url'] = Config::get('payment::providers.zarinpal.payment_url');
                $config['MerchantID']  = Config::get('payment::providers.zarinpal.MerchantID');
                $config['request_url'] = Config::get('payment::providers.zarinpal.servers.'.$config["server"].'.request_url');

                break;
            default:
                throw new Exception('تنظیمات سرویس دهندی موردنظر یافت نشد');
        }

        return $config;
    }
}