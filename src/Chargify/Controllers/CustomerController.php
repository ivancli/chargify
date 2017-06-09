<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 10/24/2016
 * Time: 10:31 AM
 */

namespace IvanCLI\Chargify\Controllers;


use Illuminate\Support\Facades\Cache;
use IvanCLI\Chargify\Models\Customer;
use IvanCLI\Chargify\Traits\Curl;

class CustomerController
{
    use Curl;

    protected $accessPoint;

    protected $apiDomain;

    public function __construct($accessPoint)
    {
        $this->accessPoint = $accessPoint;

        $this->apiDomain = config("chargify.{$this->accessPoint}.api_domain");
    }

    public function create($fields)
    {
        return $this->__create($fields);
    }

    public function update($customer_id, $fields)
    {
        return $this->__update($customer_id, $fields);
    }

    public function delete($customer_id)
    {
        return $this->__delete($customer_id);
    }

    public function getLink($customer_id)
    {
        if (config('chargify.caching.enable') == true) {
            return Cache::remember("{$this->apiDomain}.customers.{$customer_id}.link", config('chargify.caching.ttl'), function () use($customer_id){
                return $this->__getLink($customer_id);
            });
        } else {
            return $this->__getLink($customer_id);
        }
    }

    public function enableBillingPortal($customer_id, $auto_invite = false)
    {
        return $this->__enableBillingPortal($customer_id, $auto_invite);
    }

    public function all()
    {
        if (config('chargify.caching.enable') == true) {
            return Cache::remember("{$this->apiDomain}.customers", config('chargify.caching.ttl'), function () {
                return $this->__all();
            });
        } else {
            return $this->__all();
        }
    }

    public function get($customer_id)
    {
        if (config('chargify.caching.enable') == true) {
            return Cache::remember("{$this->apiDomain}.customers.{$customer_id}", config('chargify.caching.ttl'), function () use ($customer_id) {
                return $this->___get($customer_id);
            });
        } else {
            return $this->___get($customer_id);
        }
    }

    public function getByReference($reference)
    {
        return $this->__getByReference($reference);
    }

    public function getByQuery($query)
    {
        return $this->__getByQuery($query);
    }

    private function __create($fields)
    {
        $url = $this->apiDomain . "customers.json";
        $data = array(
            "customer" => $fields
        );
        $data = json_decode(json_encode($data), false);
        $customer = $this->_post($this->accessPoint, $url, $data);
        if (isset($customer->customer)) {
            $customer = $this->__assign($customer->customer);
        }
        return $customer;
    }

    private function __update($customer_id, $fields)
    {
        $url = $this->apiDomain . "customers/{$customer_id}.json";
        $data = array(
            "customer" => $fields
        );
        $data = json_decode(json_encode($data), false);
        $customer = $this->_put($url, $data);
        if (isset($customer->customer)) {
            $customer = $this->__assign($customer->customer);
            Cache::forget("{$this->apiDomain}.customers.{$customer_id}.link");
        }
        return $customer;
    }

    private function __delete($customer_id)
    {
        $url = $this->apiDomain . "customers/{$customer_id}.json";
        $customer = $this->_delete($this->accessPoint, $url);
        if (is_null($customer)) {
            $customer = true;
            Cache::forget("{$this->apiDomain}.customers.{$customer_id}.link");
        }
        return $customer;
    }

    private function __getLink($customer_id)
    {
        $url = $this->apiDomain . "portal/customers/{$customer_id}/management_link.json";
        $billingPortal = $this->_get($this->accessPoint, $url);
        return $billingPortal;
    }

    private function __enableBillingPortal($customer_id, $auto_invite)
    {
        $url = $this->apiDomain . "portal/customers/{$customer_id}/enable.json";
        if ($auto_invite == true) {
            $url .= "?auto_invite=1";
        }
        $customer = $this->_post($this->accessPoint, $url);
        if (isset($customer->customer)) {
            $customer = $this->__assign($customer->customer);
        }
        return $customer;
    }

    private function __all()
    {
        $url = $this->apiDomain . "customers.json";
        $customers = $this->_get($this->accessPoint, $url);
        if (is_array($customers)) {
            $customers = array_pluck($customers, 'customer');
            $output = array();
            foreach ($customers as $customer) {
                $output[] = $this->__assign($customer);
            }
            return $output;
        } else {
            return $customers;
        }
    }

    private function ___get($customer_id)
    {
        $url = $this->apiDomain . "customers/{$customer_id}.json";
        $customer = $this->_get($this->accessPoint, $url);
        if (!is_null($customer)) {
            $customer = $customer->customer;
            $output = $this->__assign($customer);
            return $output;
        } else {
            return $customer;
        }
    }

    private function __getByReference($reference)
    {
        $reference = urlencode($reference);
        $url = $this->apiDomain . "customers/lookup.json?reference={$reference}";
        $customer = $this->_get($this->accessPoint, $url);
        if (!is_null($customer)) {
            $customer = $customer->customer;
            $output = $this->__assign($customer);
            return $output;
        } else {
            return $customer;
        }
    }

    private function __getByQuery($query)
    {
        $query = urlencode($query);
        $url = $this->apiDomain . "customers.json?q={$query}";
        $customers = $this->_get($this->accessPoint, $url);
        if (is_array($customers)) {
            $customers = array_pluck($customers, 'customer');
            $output = array();
            foreach ($customers as $customer) {
                $output[] = $this->__assign($customer);
            }
            return $output;
        } else {
            return $customers;
        }
    }

    private function __assign($input_customer)
    {
        $customer = new Customer($this->accessPoint);
        foreach ($input_customer as $key => $value) {
            if (property_exists($customer, $key)) {
                $customer->$key = $value;
            }
        }
        return $customer;
    }
}