<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 10/25/2016
 * Time: 11:38 AM
 */

namespace IvanCLI\Chargify\Models;


use IvanCLI\Chargify\Controllers\CustomerController;

/**
 * Please check
 * https://docs.chargify.com/api-payment-profiles
 * for related documentation provided by Chargify
 *
 * Class PaymentProfile
 * @package IvanCLI\Chargify\Models
 */
class PaymentProfile
{
    public $id;
    public $first_name;
    public $last_name;
    public $billing_address;
    public $billing_address_2;
    public $billing_city;
    public $billing_country;
    public $billing_state;
    public $billing_zip;
    public $current_vault;
    public $customer_id;
    public $customer_vault_token;
    public $vault_token;
    public $payment_type;

    /*bank account details*/
    public $bank_account_holder_type;
    public $bank_account_type;
    public $bank_name;
    public $masked_bank_account_number;
    public $masked_bank_routing_number;

    /*credit card details*/
    public $card_type;
    public $expiration_month;
    public $expiration_year;
    public $masked_card_number;

    private $customerController;

    public function __construct($accessPoint = 'au')
    {
        $this->customerController = new CustomerController($accessPoint);
    }

    public function customer()
    {
        return $this->customerController->get($this->customer_id);
    }

}