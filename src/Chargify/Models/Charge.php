<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 10/26/2016
 * Time: 3:49 PM
 */

namespace IvanCLI\Chargify\Models;

use IvanCLI\Chargify\Controllers\ProductController;
use IvanCLI\Chargify\Controllers\SubscriptionController;
use IvanCLI\Chargify\Controllers\TransactionController;

/**
 * Same as payment, once the charge is created, it'll be converted to transaction
 *
 * Please check
 * https://docs.chargify.com/api-charges
 * for related documentation provided by Chargify
 *
 * Class Charge
 * @package IvanCLI\Chargify\Models
 */
class Charge
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

    private $subscriptionController;
    private $productController;
    private $transactionController;

    public function __construct()
    {
        $this->subscriptionController = new SubscriptionController;
        $this->productController = new ProductController;
        $this->transactionController = new TransactionController;
    }

    public function subscription()
    {
        return $this->subscriptionController->get($this->subscription_id);
    }

    public function product()
    {
        return $this->productController->get($this->product_id);
    }

    public function transaction()
    {
        return $this->transactionController->get($this->payment_id);
    }
}