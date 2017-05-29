<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 10/26/2016
 * Time: 5:15 PM
 */

namespace IvanCLI\Chargify\Models;


use IvanCLI\Chargify\Controllers\ProductController;
use IvanCLI\Chargify\Controllers\SubscriptionController;

/**
 * Please check
 * https://docs.chargify.com/api-adjustments
 * for related documentation provided by Chargify
 *
 * Class Adjustment
 * @package IvanCLI\Chargify\Models
 */
class Adjustment
{
    public $id;
    public $success;
    public $memo;
    public $amount_in_cents;
    public $ending_balance_in_cents;
    public $type;
    public $transaction_type;
    public $subscription_id;
    public $product_id;
    public $created_at;
    public $payment_id;

    private $productController;
    private $subscriptionController;

    public function __construct($accessPoint = 'au')
    {
        $this->productController = new ProductController($accessPoint);
        $this->subscriptionController = new SubscriptionController($accessPoint);
    }

    public function product()
    {
        return $this->productController->get($this->product_id);
    }

    public function subscription()
    {
        return $this->subscriptionController->get($this->subscription_id);
    }
}