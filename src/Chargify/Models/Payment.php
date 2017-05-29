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
 * A non-readable object from Chargify, used for submitting payment.
 * After payment is created, it'll be converted to Transaction
 *
 * Please check
 * https://docs.chargify.com/api-payments
 * for related documentation provided by Chargify
 *
 * Class Payment
 * @package IvanCLI\Chargify\Models
 */
class Payment
{
    public $id;
    public $amount_in_cents;
    public $created_at;
    public $ending_balance_in_cents;
    public $kind;
    public $memo;
    public $payment_id;
    public $product_id;
    public $starting_balance_in_cents;
    public $subscription_id;
    public $success;
    public $type;
    public $transaction_type;
    public $gateway_transaction_id;

    private $subscriptionController;
    private $productController;
    private $transactionController;

    public function __construct($accessPoint = 'au')
    {
        $this->subscriptionController = new SubscriptionController($accessPoint);
        $this->productController = new ProductController($accessPoint);
        $this->transactionController = new TransactionController($accessPoint);
    }

    /**
     * Load the subscription this payment is for
     *
     * @return Subscription|null
     */
    public function subscription()
    {
        return $this->subscriptionController->get($this->subscription_id);
    }

    /**
     * Load the product this payment is for
     *
     * @return Product|null
     */
    public function product()
    {
        return $this->productController->get($this->product_id);
    }

    /**
     * Load a transaction
     *
     * After payment is created, you can load transaction by giving payment ID (same as transaction ID)
     *
     * @return Transaction|mixed
     */
    public function transaction()
    {
        return $this->transactionController->get($this->id);
    }
}