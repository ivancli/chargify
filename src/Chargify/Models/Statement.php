<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 10/27/2016
 * Time: 9:37 AM
 */

namespace IvanCLI\Chargify\Models;


use IvanCLI\Chargify\Controllers\SubscriptionController;

class Statement
{
    public $id;
    public $basic_html_view;
    public $closed_at;
    public $created_at;
    public $customer_billing_address;
    public $customer_billing_address_2;
    public $customer_billing_city;
    public $customer_billing_country;
    public $customer_billing_state;
    public $customer_billing_zip;
    public $customer_first_name;
    public $customer_last_name;
    public $customer_organization;
    public $customer_shipping_address;
    public $customer_shipping_address_2;
    public $customer_shipping_city;
    public $customer_shipping_country;
    public $customer_shipping_state;
    public $customer_shipping_zip;
    public $ending_balance_in_cents;
    public $total_in_cents;
    public $events;
    public $future_payments;
    public $html_view;
    public $memo;
    public $opened_at;
    public $settled_at;
    public $starting_balance_in_cents;
    public $subscription_id;
    public $text_view;
    public $transactions;
    public $updated_at;

    private $subscriptionController;

    public function __construct()
    {
        $this->subscriptionController = new SubscriptionController;
    }

    public function subscription()
    {
        return $this->subscriptionController->get($this->subscription_id);
    }
}