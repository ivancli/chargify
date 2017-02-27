<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 10/26/2016
 * Time: 3:49 PM
 */

namespace IvanCLI\Chargify\Controllers;


use IvanCLI\Chargify\Models\Payment;
use IvanCLI\Chargify\Traits\CacheFlusher;
use IvanCLI\Chargify\Traits\Curl;

class PaymentController
{
    use Curl, CacheFlusher;

    public function create($subscription_id, $fields)
    {
        return $this->__create($subscription_id, $fields);
    }

    public function createInvoicePayment($invoice_id, $fields)
    {
        return $this->__createInvoicePayment($invoice_id, $fields);
    }

    private function __create($subscription_id, $fields)
    {
        $url = config('chargify.api_domain') . "subscriptions/{$subscription_id}/payments.json";
        $data = array(
            "payment" => $fields
        );
        $data = json_decode(json_encode($data), false);
        $payment = $this->_post($url, $data);
        if (isset($payment->payment)) {
            $payment = $this->__assign($payment->payment);
        }
        return $payment;
    }

    private function __createInvoicePayment($invoice_id, $fields)
    {
        $url = config('chargify.api_domain') . "invoices/{$invoice_id}/payments.json";
        $data = array(
            "payment" => $fields
        );
        $data = json_decode(json_encode($data), false);
        $payment = $this->_post($url, $data);
        if (isset($payment->payment)) {
            $payment = $this->__assign($payment->payment);
            $this->flushInvoices();
        }
        return $payment;
    }

    /**
     * @param $input_payment
     * @return Payment
     */
    private function __assign($input_payment)
    {
        $payment = new Payment;
        foreach ($input_payment as $key => $value) {
            if (property_exists($payment, $key)) {
                $payment->$key = $value;
            }
        }
        return $payment;
    }
}