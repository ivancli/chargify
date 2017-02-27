<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 10/25/2016
 * Time: 11:43 AM
 */

namespace IvanCLI\Chargify\Controllers;


use Illuminate\Support\Facades\Cache;
use IvanCLI\Chargify\Models\Invoice;
use IvanCLI\Chargify\Traits\Curl;

/**
 * Class InvoiceController
 * @package IvanCLI\Chargify\Controllers
 */
class InvoiceController
{
    use Curl;

    /**
     * Load all invoices
     *
     * @param $queryString
     * available queries are as follow:
     *      1. start_date=<YYYY-MM-DD>
     *      2. end_date=<YYYY-MM-DD>
     *      3. status[]=<paid, unpaid, partial, archived>
     *      4. invoice_id=<subscription_id>
     * @return array|mixed
     */
    public function all($queryString = null)
    {
        return $this->__all($queryString);
    }

    /**
     * Load an invoice
     *
     * @param $invoice_id
     * @return Invoice
     */
    public function get($invoice_id)
    {
        if (config('chargify.caching.enable') == true) {
            return Cache::remember("invoices.{$invoice_id}", config('chargify.caching.ttl'), function () use($invoice_id){
                return $this->___get($invoice_id);
            });
        } else {
            return $this->___get($invoice_id);
        }
    }

    /**
     * @param null $queryString
     * @return array|mixed
     */
    private function __all($queryString = null)
    {
        $url = config('chargify.api_domain') . "invoices.json";
        if (!is_null($queryString)) {
            $url .= "?" . $queryString;
        }
        $invoices = $this->_get($url);
        if (is_array($invoices)) {
            $invoices = array_pluck($invoices, 'invoice');
            $output = array();
            foreach ($invoices as $invoice) {
                $output[] = $this->__assign($invoice);
            }
            return $output;
        } else {
            return $invoices;
        }
    }

    /**
     * @param $invoice_id
     * @return Invoice|mixed
     */
    private function ___get($invoice_id)
    {
        $url = config('chargify.api_domain') . "invoices/{$invoice_id}.json";
        $invoice = $this->_get($url);
        if (isset($invoice->invoice)) {
            $invoice = $invoice->invoice;
            $invoice = $this->__assign($invoice);
        }
        return $invoice;
    }

    /**
     * @param $input_invoice
     * @return Invoice
     */
    private function __assign($input_invoice)
    {
        $invoice = new Invoice;
        foreach ($input_invoice as $key => $value) {
            if (property_exists($invoice, $key)) {
                $invoice->$key = $value;
            }
        }
        return $invoice;
    }
}