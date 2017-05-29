<?php

namespace IvanCLI\Chargify\Models;

use Illuminate\Support\Facades\Cache;
use IvanCLI\Chargify\Controllers\ComponentController;
use IvanCLI\Chargify\Controllers\CustomerController;
use IvanCLI\Chargify\Controllers\NoteController;
use IvanCLI\Chargify\Controllers\PaymentProfileController;
use IvanCLI\Chargify\Controllers\ProductController;
use IvanCLI\Chargify\Controllers\SubscriptionController;
use IvanCLI\Chargify\Traits\Curl;

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 23/10/2016
 * Time: 1:10 PM
 */

/**
 * Please check
 * https://docs.chargify.com/api-subscriptions
 * for related documentation provided by Chargify
 *
 * Class Subscription
 * @package IvanCLI\Chargify\Models
 */
class Subscription
{
    use Curl;

    public $id;
    public $activated_at;
    public $balance_in_cents;
    public $cancel_at_end_of_period;
    public $canceled_at;
    public $cancellation_message;
    public $coupon_code;
    public $created_at;
    public $current_period_started_at;
    public $current_period_ends_at;
    public $delayed_cancel_at;
    public $expires_at;
    public $next_assessment_at;
    public $payment_type;
    public $previous_state;
    public $product_price_in_cents;
    public $product_version_number;
    public $signup_payment_id;
    public $signup_revenue;
    public $state;
    public $total_revenue_in_cents;
    public $trial_started_at;
    public $trial_ended_at;
    public $updated_at;
    public $referral_code;
    public $current_billing_amount_in_cents;
    public $next_product_id;
    public $cancellation_method;
    public $payment_collection_method;
    public $snap_day;

    public $customer_id;
    public $product_id;
    public $credit_card_id;
    public $bank_account_id;

    private $customerController;
    private $productController;
    private $paymentProfileController;
    private $componentController;
    private $noteController;

    public function __construct($accessPoint = 'au')
    {
        $this->customerController = new CustomerController($accessPoint);
        $this->productController = new ProductController($accessPoint);
        $this->paymentProfileController = new PaymentProfileController($accessPoint);
        $this->componentController = new ComponentController($accessPoint);
        $this->noteController = new NoteController($accessPoint);
    }

    /**
     * @return PaymentProfile|null
     */
    public function paymentProfile()
    {
        if (isset($this->credit_card_id)) {
            return $this->paymentProfileController->get($this->credit_card_id);
        } elseif (isset($this->bank_account_id)) {
            return $this->paymentProfileController->get($this->bank_account_id);
        } else {
            return null;
        }
    }

    /**
     * @return Customer|mixed
     */
    public function customer()
    {
        return $this->customerController->get($this->customer_id);
    }

    /**
     * @return Product|null
     */
    public function product()
    {
        return $this->productController->get($this->product_id);
    }

    /**
     * @return array|mixed
     */
    public function components()
    {
        return $this->componentController->allBySubscription($this->id);
    }

    /**
     * @return array|mixed
     */
    public function notes()
    {
        return $this->noteController->allBySubscription($this->id);
    }
}