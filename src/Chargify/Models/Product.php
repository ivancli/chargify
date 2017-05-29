<?php

namespace IvanCLI\Chargify\Models;

use IvanCLI\Chargify\Controllers\ProductFamilyController;

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 23/10/2016
 * Time: 1:12 PM
 */

/**
 * Please check
 * https://docs.chargify.com/api-products
 * for related documentation provided by Chargify
 *
 * Class Product
 * @package IvanCLI\Chargify\Models
 */
class Product
{
    public $id;
    public $price_in_cents;
    public $name;
    public $handle;
    public $description;
    public $accounting_code;
    public $interval_unit;
    public $interval;
    public $initial_charge_in_cents;
    public $trial_price_in_cents;
    public $trial_interval;
    public $trial_interval_unit;
    public $expiration_interval;
    public $expiration_interval_unit;
    public $version_number;
    public $update_return_url;
    public $update_return_params;
    public $require_credit_card;
    public $request_credit_card;
    public $created_at;
    public $updated_at;
    public $archived_at;
    public $public_signup_pages;

    public $product_family_id;

    private $productFamilyController;

    public function __construct($accessPoint = 'au')
    {
        $this->productFamilyController = new ProductFamilyController($accessPoint);
    }

    public function productFamily()
    {
        return $this->productFamilyController->get($this->product_family_id);
    }
}