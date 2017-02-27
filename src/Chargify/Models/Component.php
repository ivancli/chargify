<?php
namespace IvanCLI\Chargify\Models;

use IvanCLI\Chargify\Controllers\ProductFamilyController;

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 23/10/2016
 * Time: 1:13 PM
 */

/**
 * Please check
 * https://docs.chargify.com/api-components
 * for related documentation provided by Chargify
 *
 * Class Component
 * @package IvanCLI\Chargify\Models
 */
class Component
{
    public $id;
    public $description;
    public $name;
    public $unit_name;
    public $unit_price;
    public $pricing_scheme;
    public $prices;
    public $product_family_id;
    public $kind;
    public $price_per_unit_in_cents;
    public $archived;
    public $taxable;

    private $productFamilyController;

    public function __construct()
    {
        $this->productFamilyController = new ProductFamilyController();
    }

    public function productFamily()
    {
        return $this->productFamilyController->get($this->product_family_id);
    }
}