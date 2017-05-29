<?php

namespace IvanCLI\Chargify\Models;

use IvanCLI\Chargify\Controllers\ProductFamilyController;

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 23/10/2016
 * Time: 1:18 PM
 */

/**
 * Please check
 * https://docs.chargify.com/api-coupons
 * for related documentation provided by Chargify
 *
 * Class Coupon
 * @package IvanCLI\Chargify\Models
 */
class Coupon
{
    public $id;
    public $name;
    public $code;
    public $description;
    public $percentage;
    public $amount;
    public $allow_negative_balance;
    public $recurring;
    public $duration_period_count;
    public $duration_interval_unit;
    public $end_date;
    public $conversion_limit;
    public $product_family_id;
    public $created_at;
    public $start_date;
    public $updated_at;
    public $archived_at;

    private $productFamilyController;

    public function __construct($accessPoint = 'au', $id = null)
    {
        $this->productFamilyController = new ProductFamilyController($accessPoint);
    }

    public function productFamily()
    {
        return $this->productFamilyController->get($this->product_family_id);
    }
}