<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 10/24/2016
 * Time: 11:52 AM
 */

namespace IvanCLI\Chargify\Controllers;


use Illuminate\Support\Facades\Cache;
use IvanCLI\Chargify\Models\Product;
use IvanCLI\Chargify\Traits\Curl;

class ProductController
{
    use Curl;

    protected $accessPoint;

    protected $apiDomain;

    public function __construct($accessPoint)
    {
        $this->accessPoint = $accessPoint;

        $this->apiDomain = config("chargify.{$this->accessPoint}.api_domain");
    }

    /**
     * Create a product
     *
     * @param $product_family_id
     * @param $fields
     * @return Product|mixed
     */
    public function create($product_family_id, $fields)
    {
        return $this->__create($product_family_id, $fields);
    }

    /**
     * Update a product
     *
     * @param $product_id
     * @param $fields
     * @return Product|mixed
     */
    public function update($product_id, $fields)
    {
        return $this->__update($product_id, $fields);
    }

    /**
     * Load all products
     *
     * @return array
     */
    public function all()
    {
        if (config('chargify.caching.enable') == true) {
            return Cache::remember("{$this->accessPoint}.products", config('chargify.caching.ttl'), function () {
                return $this->__all();
            });
        } else {
            return $this->__all();
        }
    }

    /**
     * Load a product by product id
     *
     * @param $product_id
     * @return Product|null
     */
    public function get($product_id)
    {
        if (config('chargify.caching.enable') == true) {
            return Cache::remember("{$this->accessPoint}.products.{$product_id}", config('chargify.caching.ttl'), function () use ($product_id) {
                return $this->___get($product_id);
            });
        } else {
            return $this->___get($product_id);
        }
    }

    /**
     * Load a product by product handle
     *
     * @param $handle
     * @return Product|null
     */
    public function getByHandle($handle)
    {
        if (config('chargify.caching.enable') == true) {
            return Cache::remember("{$this->accessPoint}.products.handle.{$handle}", config('chargify.caching.ttl'), function () use ($handle) {
                return $this->__getByHandle($handle);
            });
        } else {
            return $this->__getByHandle($handle);
        }
    }

    /**
     * Load all products by product family id
     *
     * @param $product_family_id
     * @return array
     */
    public function allByProductFamily($product_family_id)
    {
        if (config('chargify.caching.enable') == true) {
            return Cache::remember("{$this->accessPoint}.product_families.{$product_family_id}.products", config('chargify.caching.ttl'), function () use ($product_family_id) {
                return $this->__allByProductFamily($product_family_id);
            });
        } else {
            return $this->__allByProductFamily($product_family_id);
        }
    }

    /**
     * @param $product_family_id
     * @param $fields
     * @return Product|mixed
     */
    private function __create($product_family_id, $fields)
    {
        $url = $this->apiDomain . "product_families/{$product_family_id}/products.json";
        $data = array(
            "product" => $fields
        );
        $data = json_decode(json_encode($data), false);
        $product = $this->_post($url, $data);
        if (isset($product->product)) {
            $output = $this->__assign($product->product);
            Cache::forget("{$this->accessPoint}.products");
            Cache::forget("{$this->accessPoint}.product_families.{$product_family_id}.products");
            return $output;
        } else {
            return $product;
        }
    }

    /**
     * @param $product_id
     * @param $fields
     * @return Product|mixed
     */
    private function __update($product_id, $fields)
    {
        $url = $this->apiDomain . "products/{$product_id}.json";
        $data = array(
            "product" => $fields
        );
        $data = json_decode(json_encode($data), false);
        $product = $this->_put($url, $data);
        if (isset($product->product)) {
            $output = $this->__assign($product->product);
            Cache::forget("{$this->accessPoint}.products");
            Cache::forget("{$this->accessPoint}.products.{$product_id}");
            Cache::forget("{$this->accessPoint}.product_families.{$output->product_family_id}.products");
            return $output;
        } else {
            return $product;
        }
    }

    /**
     * @return array
     */
    private function __all()
    {
        $url = $this->apiDomain . "products.json";
        $products = $this->_get($url);
        if (is_array($products)) {
            $products = array_pluck($products, 'product');
            $output = array();
            foreach ($products as $product) {
                $output[] = $this->__assign($product);
            }
            return $output;
        } else {
            return $products;
        }
    }

    /**
     * @param $product_id
     * @return Product|null
     */
    private function ___get($product_id)
    {
        $url = $this->apiDomain . "products/{$product_id}.json";
        $product = $this->_get($url);
        if (!is_null($product)) {
            $product = $product->product;
            $output = $this->__assign($product);
            return $output;
        } else {
            return $product;
        }
    }

    /**
     * @param $handle
     * @return Product|null
     */
    private function __getByHandle($handle)
    {
        $url = $this->apiDomain . "products/handle/{$handle}.json";
        $product = $this->_get($url);
        if (!is_null($product)) {
            $product = $product->product;
            $output = $this->__assign($product);
            return $output;
        } else {
            return null;
        }
    }

    /**
     * @param $product_family_id
     * @return array
     */
    private function __allByProductFamily($product_family_id)
    {
        $url = $this->apiDomain . "product_families/{$product_family_id}/products.json";
        $products = $this->_get($url);
        if (is_array($products)) {
            $products = array_pluck($products, 'product');
            $output = array();
            foreach ($products as $product) {
                $output[] = $this->__assign($product);
            }
            return $output;
        } else {
            return $products;
        }
    }

    /**
     * @param $input_product
     * @return Product
     */
    private function __assign($input_product)
    {
        $product = new Product;
        foreach ($input_product as $key => $value) {
            switch ($key) {
                case "product_family":
                    if (isset($value->id)) {
                        $product->product_family_id = $value->id;
                    }
                    break;
                default:
                    if (property_exists($product, $key)) {
                        $product->$key = $value;
                    }
            }
        }
        return $product;
    }
}