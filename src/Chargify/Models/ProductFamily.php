<?php

namespace IvanCLI\Chargify\Models;

use IvanCLI\Chargify\Controllers\ComponentController;
use IvanCLI\Chargify\Controllers\ProductController;

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 23/10/2016
 * Time: 1:12 PM
 */

/**
 * Please check
 * https://docs.chargify.com/api-product-families
 * for related documentation provided by Chargify
 *
 * Class ProductFamily
 * @package IvanCLI\Chargify\Models
 */
class ProductFamily
{
    public $id;
    public $name;
    public $description;
    public $handle;
    public $accounting_code;

    private $productController;
    private $componentController;

    public function __construct($accessPoint = 'au')
    {
        $this->productController = new ProductController($accessPoint);
        $this->componentController = new ComponentController($accessPoint);
    }

    public function products()
    {
        return $this->productController->allByProductFamily($this->id);
    }

    public function components()
    {
        return $this->componentController->allByProductFamily($this->id);
    }
}