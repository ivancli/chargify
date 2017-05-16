<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 10/26/2016
 * Time: 3:50 PM
 */

namespace IvanCLI\Chargify\Controllers;


use IvanCLI\Chargify\Models\Charge;
use IvanCLI\Chargify\Traits\Curl;

class ChargeController
{
    use Curl;

    protected $accessPoint;

    protected $apiDomain;

    public function __construct($accessPoint)
    {
        $this->accessPoint = $accessPoint;

        $this->apiDomain = config("chargify.{$this->accessPoint}.api_domain");
    }

    public function create($subscription_id, $fields)
    {
        return $this->__create($subscription_id, $fields);
    }

    public function createInvoiceCharge($invoice_id, $fields)
    {
        return $this->__createInvoiceCharge($invoice_id, $fields);
    }

    private function __create($subscription_id, $fields)
    {
        $url = $this->apiDomain . "subscriptions/{$subscription_id}/charges.json";
        $data = array(
            "charge" => $fields
        );
        $data = json_decode(json_encode($data), false);
        $charge = $this->_post($this->accessPoint, $url, $data);
        if (isset($charge->charge)) {
            $charge = $this->__assign($charge->charge);
        }
        return $charge;
    }

    private function __createInvoiceCharge($invoice_id, $fields)
    {
        $url = $this->apiDomain . "invoices/{$invoice_id}/charges.json";
        $data = array(
            "charge" => $fields
        );
        $data = json_decode(json_encode($data), false);
        $charge = $this->_post($this->accessPoint, $url, $data);
        if (isset($charge->charge)) {
            $charge = $this->__assign($charge->charge);
        }
        return $charge;
    }

    private function __assign($input_charge)
    {
        $charge = new Charge;
        foreach ($input_charge as $key => $value) {
            if (property_exists($charge, $key)) {
                $charge->$key = $value;
            }
        }
        return $charge;
    }
}