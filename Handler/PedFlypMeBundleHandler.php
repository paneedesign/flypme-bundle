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
        return $this->get('currencies');
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function dataExchangeRates()
    {
        return $this->get('data/exchange_rates');
    }

    /**
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return mixed
     * @throws Exception
     */
    public function orderLimits($fromCurrency = 'BTC', $toCurrency = 'ETH')
    {
        return $this->get("order/limits/{$fromCurrency}/{$toCurrency}");
    }

    /**
     * @param string $from_currency
     * @param string $to_currency
     * @param $amount
     * @param string $destination
     * @param string $refund_address
     * @param string $type
     * @return mixed
     * @throws Exception
     */
    public function orderNew($from_currency, $to_currency, $amount, $destination = '', $refund_address = '', $type = "invoiced_amount")
    {
        $body = [
            "order" => [
                "from_currency" => $from_currency,
                "to_currency" => $to_currency,
                $type => $amount
            ]
        ];

        if (!empty($destination)) {
            $body["order"]["destination"] = $destination;
        }

        if (!empty($refund_address)) {
            $body["order"]["refund_address"] = $refund_address;
        }
        return $this->post('order/new', $body, 'json');
    }

    /**
     * @param string $uuid
     * @param string $from_currency
     * @param string $to_currency
     * @param $amount
     * @param string $destination
     * @param string $refund_address
     * @param string $type
     * @return mixed
     * @throws Exception
     */
    public function orderUpdate($uuid, $from_currency, $to_currency, $amount, $destination = '', $refund_address = '', $type = "invoiced_amount")
    {
        $body = [
            "order" => [
                "uuid" => $uuid,
                "from_currency" => $from_currency,
                "to_currency" => $to_currency,
                $type => $amount
            ]
        ];

        if (!empty($destination)) {
            $body["order"]["destination"] = $destination;
        }

        if (!empty($refund_address)) {
            $body["order"]["refund_address"] = $refund_address;
        }
        return $this->post('order/update', $body, 'json');
    }

    /**
     * @param string $uuid
     * @return mixed
     * @throws Exception
     */
    public function orderAccept($uuid)
    {
        $body = [
            "uuid" => $uuid
        ];
        return $this->post('order/accept', $body, 'json');
    }

    /**
     * @param string $uuid
     * @return mixed
     * @throws Exception
     */
    public function orderCheck($uuid)
    {
        $body = [
            "uuid" => $uuid
        ];
        return $this->post('order/check', $body, 'json');
    }

    /**
     * @param string $uuid
     * @return mixed
     * @throws Exception
     */
    public function orderInfo($uuid)
    {
        $body = [
            "uuid" => $uuid
        ];
        return $this->post('order/info', $body, 'json');
    }

    /**
     * @param string $uuid
     * @return mixed
     * @throws Exception
     */
    public function orderCancel($uuid)
    {
        $body = [
            "uuid" => $uuid
        ];
        return $this->post('order/cancel', $body, 'json');
    }

    /**
     * @param string $uuid
     * @param string $refund_address
     * @return mixed
     * @throws Exception
     */
    public function addRefund($uuid, $refund_address)
    {
        $body = [
            "uuid" => $uuid,
            "address" => $refund_address
        ];
        return $this->post('order/addrefund', $body, 'json');
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
            throw new Exception($response->body, $response->code);
        }
    }
}
