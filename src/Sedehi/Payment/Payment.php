<?php
/**
 * Created by PhpStorm.
 * User: Navid Sedehi
 * Date: 6/1/2015
 * Time: 4:53 PM
 */

namespace Sedehi\Payment;

use Config,
    DB,
    Redirect,
    Exception,
    Input,
    SoapClient;

use Sedehi\Payment\Exceptions\PaylineException;
use Sedehi\Payment\Exceptions\ZarinpalException;

class Payment {

    private $provider;
    private $callBackUrl;
    private $amount;
    private $description = null;
    private $orderId     = null;

    public function __construct() {

        if(!extension_loaded('soap')) {
            throw new Exception('soap در سرور شما فعال نمی باشد');
        }
    }

    public function make() {

        $this->provider    = Config::get('payment::default_provider');
        $this->callBackUrl = Config::get('payment::callback_url');

        return $this;
    }

    public function request() {

        $config = PaymentConfig::get($this->provider);
        switch($this->provider) {
            case'payline':

                $provider = new PaylineProvider($config['api']);
                $request  = $provider->request($this->amount,
                                               $config['request_url'],
                                               $this->callBackUrl
                );
                if($request > 0 && is_numeric($request)) {
                    DB::table(Config::get('payment::table'))->insert(
                        array(
                            'amount'      => $this->amount,
                            'provider'    => $this->provider,
                            'order_id'    => $this->orderId,
                            'authority'   => $request,
                            'description' => $this->description,
                            'updated_at'  => time(),
                            'created_at'  => time()
                        )
                    );

                    return Redirect::to($config['second_request_url'].$request);
                } else {
                    throw new PaylineException($request, 'request');
                }

                break;
            case'zarinpal':

                $provider = new ZarinpalProvider($config['MerchantID']);
                $request  = $provider->request($this->amount,
                                               $config['request_url'],
                                               $this->callBackUrl,
                                               $this->description
                );
                if($request->Status == 100) {
                    DB::table(Config::get('payment::table'))->insert(
                        array(
                            'amount'      => $this->amount,
                            'provider'    => $this->provider,
                            'order_id'    => $this->orderId,
                            'authority'   => $request,
                            'description' => $this->description,
                            'updated_at'  => time(),
                            'created_at'  => time()
                        )
                    );

                    return Redirect::to($config['payment_url'].$request->Authority);
                } else {
                    throw new ZarinpalException($request->Status);
                }

                break;
            default:
                throw new Exception('سرویس دهنده انتخاب شده وجود ندارد');
        }

    }

    public function verify() {

        $config = PaymentConfig::get($this->provider);
        switch($this->provider) {
            case'payline':

                $provider = new PaylineProvider($config['api']);
                $request  = $provider->verify($config['verify_request_url'],
                                              Input::get('trans_id'),
                                              Input::get('id_get')
                );
                if($request == 1) {

                    DB::table(Config::get('payment::table'))
                      ->where('authority', '=', Input::get('id_get'))
                      ->where('status', '=', 0)
                      ->update(array(
                                   'ref_id'     => Input::get('trans_id'),
                                   'status'     => $request,
                                   'updated_at' => time()
                               ));

                    $data = DB::table(Config::get('payment::table'))
                              ->where('authority', '=', Input::get('id_get'))
                              ->where('ref_id', '=', Input::get('trans_id'))
                              ->where('status', '=', 1)
                              ->first();

                    return $data;

                } else {
                    throw new PaylineException($request, 'verify');
                }

                break;
            case'zarinpal':

                $provider = new PaylineProvider($config['api']);
                $result   = $provider->verify($config['verify_request_url'],
                                              Input::get('trans_id'),
                                              Input::get('id_get')
                );


                if($result == 1) {

                    DB::table(Config::get('payment::table'))
                      ->where('authority', '=', Input::get('id_get'))
                      ->where('status', '=', 0)
                      ->update(array(
                                   'ref_id'     => Input::get('trans_id'),
                                   'status'     => $request,
                                   'updated_at' => time()
                               ));

                    $data = DB::table(Config::get('payment::table'))
                              ->where('authority', '=', Input::get('id_get'))
                              ->where('ref_id', '=', Input::get('trans_id'))
                              ->where('status', '=', 1)
                              ->first();

                    return $data;

                } else {
                    throw new PaylineException($request, 'verify');
                }

                break;
            default:
                throw new Exception('سرویس دهنده انتخاب شده وجود ندارد');
        }
    }

    public function provider($provider) {

        $this->provider = $provider;

        return $this;
    }

    public function callBackUrl($callBackUrl) {

        $this->callBackUrl = $callBackUrl;

        return $this;
    }

    public function description($description) {

        $this->description = $description;

        return $this;
    }

    public function amount($amount) {

        $this->amount = $amount;

        return $this;
    }

    public function orderId($orderId) {

        $this->orderId = $orderId;

        return $this;
    }

}