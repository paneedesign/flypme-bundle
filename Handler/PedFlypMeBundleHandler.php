<?php
/**
 * Created by PhpStorm.
 * User: vittominacori
 * Date: 26/04/18
 * Time: 12:52
 */

namespace PaneeDesign\FlypMeBundle\Handler;

use Unirest;

class PedFlypMeBundleHandler
{
    private static $endpoint = '';
    private static $headers = [];

    public function __construct($endpoint = '', $contentType = '')
    {
        self::$endpoint = $endpoint;
        self::$headers = [
            'Accept' => $contentType,
            'Content-Type' => $contentType
        ];
    }
    // public methods
    public function currencies()
    {
        return $this->get('currencies');
    }
    public function dataExchangeRates()
    {
        return $this->get('data/exchange_rates');
    }
    public function orderLimits()
    {
        return $this->post('order/limits');
    }
    public function orderCreate($from_currency, $to_currency, $amount, $destination, $type = "invoiced_amount")
    {
        $body = [
            "order" => [
                "from_currency" => $from_currency,
                "to_currency" => $to_currency,
                $type => $amount,
                "destination" => $destination
            ]
        ];
        return $this->post('order/create', $body, 'json');
    }
    public function orderCheck($uuid)
    {
        $body = [
            "uuid" => $uuid
        ];
        return $this->post('order/check', $body, 'json');
    }
    public function orderInfo($uuid)
    {
        $body = [
            "uuid" => $uuid
        ];
        return $this->post('order/info', $body, 'json');
    }
    public function orderCancel($uuid)
    {
        $body = [
            "uuid" => $uuid
        ];
        return $this->post('order/cancel', $body, 'json');
    }
    // private methods
    private function get($method, $parameters = [])
    {
        $apiCall = self::$endpoint . $method;
        $response = Unirest\Request::get($apiCall, self::$headers, $parameters);
        if ($response->code == 200) {
            return $response->body;
        }
        return $response;
    }
    private function post($method, $body = [], $type = '')
    {
        $apiCall = self::$endpoint . $method;
        if ($type == 'json') {
            $body = Unirest\Request\Body::json($body);
        }
        $response = Unirest\Request::post($apiCall, self::$headers, $body);
        if ($response->code == 200) {
            return $response->body;
        }
        return $response;
    }
}
