<?php
/**
 * Created by PhpStorm.
 * User: vittominacori
 * Date: 26/04/18
 * Time: 12:52
 */

namespace PaneeDesign\FlypMeBundle\Handler;

use Exception;
use Unirest;

class PedFlypMeBundleHandler
{
    private static $endpoint = '';
    private static $headers = [];

    /**
     * PedFlypMeBundleHandler constructor.
     * @param string $endpoint
     * @param string $contentType
     */
    public function __construct($endpoint = '', $contentType = '')
    {
        self::$endpoint = $endpoint;
        self::$headers = [
            'Accept' => $contentType,
            'Content-Type' => $contentType
        ];
    }

    // public methods

    /**
     * @return mixed
     * @throws Exception
     */
    public function currencies()
    {
        try {
            return $this->get('currencies');
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function dataExchangeRates()
    {
        try {
            return $this->get('data/exchange_rates');
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function orderLimits()
    {
        try {
            return $this->post('order/limits');
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $from_currency
     * @param $to_currency
     * @param $amount
     * @param $destination
     * @param string $type
     * @return mixed
     * @throws Exception
     */
    public function orderCreate($from_currency, $to_currency, $amount, $destination, $type = "invoiced_amount")
    {
        try {
            $body = [
                "order" => [
                    "from_currency" => $from_currency,
                    "to_currency" => $to_currency,
                    $type => $amount,
                    "destination" => $destination
                ]
            ];
            return $this->post('order/create', $body, 'json');
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $uuid
     * @return mixed
     * @throws Exception
     */
    public function orderCheck($uuid)
    {
        try {
            $body = [
                "uuid" => $uuid
            ];
            return $this->post('order/check', $body, 'json');
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $uuid
     * @return mixed
     * @throws Exception
     */
    public function orderInfo($uuid)
    {
        try {
            $body = [
                "uuid" => $uuid
            ];
            return $this->post('order/info', $body, 'json');
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $uuid
     * @return mixed
     * @throws Exception
     */
    public function orderCancel($uuid)
    {
        try {
            $body = [
                "uuid" => $uuid
            ];
            return $this->post('order/cancel', $body, 'json');
        } catch (Exception $e) {
            throw $e;
        }
    }

    // private methods

    /**
     * @param string $method
     * @param array $parameters
     * @return mixed
     * @throws Exception
     */
    private function get($method, $parameters = [])
    {
        $apiCall = self::$endpoint . $method;
        $response = Unirest\Request::get($apiCall, self::$headers, $parameters);
        if ($response->code == 200) {
            return $response->body;
        } else {
            throw new Exception($response->body, $response->code);
        }
    }

    /**
     * @param string $method
     * @param array $body
     * @param string $type
     * @return mixed
     * @throws Exception
     */
    private function post($method, $body = [], $type = '')
    {
        $apiCall = self::$endpoint . $method;
        if ($type == 'json') {
            $body = Unirest\Request\Body::json($body);
        }
        $response = Unirest\Request::post($apiCall, self::$headers, $body);
        if ($response->code == 200) {
            return $response->body;
        } else {
            throw new \Exception($response->body, $response->code);
        }
    }
}
