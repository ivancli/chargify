<?php
namespace IvanCLI\Chargify;

use IvanCLI\Chargify\Controllers\AdjustmentController;
use IvanCLI\Chargify\Controllers\AllocationController;
use IvanCLI\Chargify\Controllers\ChargeController;
use IvanCLI\Chargify\Controllers\ComponentController;
use IvanCLI\Chargify\Controllers\CouponController;
use IvanCLI\Chargify\Controllers\CustomerController;
use IvanCLI\Chargify\Controllers\InvoiceController;
use IvanCLI\Chargify\Controllers\NoteController;
use IvanCLI\Chargify\Controllers\PaymentController;
use IvanCLI\Chargify\Controllers\PaymentProfileController;
use IvanCLI\Chargify\Controllers\ProductController;
use IvanCLI\Chargify\Controllers\ProductFamilyController;
use IvanCLI\Chargify\Controllers\SiteController;
use IvanCLI\Chargify\Controllers\StatementController;
use IvanCLI\Chargify\Controllers\SubscriptionController;
use IvanCLI\Chargify\Controllers\TransactionController;
use IvanCLI\Chargify\Controllers\WebhookController;

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 23/10/2016
 * Time: 1:07 AM
 */
class Chargify
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public static function adjustment()
    {
        return new AdjustmentController();
    }

    public static function allocation()
    {
        return new AllocationController();
    }

    public static function charge()
    {
        return new ChargeController();
    }

    public static function component()
    {
        return new ComponentController();
    }

    public static function coupon()
    {
        return new CouponController();
    }

    public static function customer()
    {
        return new CustomerController();
    }

    public static function invoice()
    {
        return new InvoiceController();
    }

    public static function note()
    {
        return new NoteController();
    }

    public static function payment()
    {
        return new PaymentController();
    }

    public static function paymentProfile()
    {
        return new PaymentProfileController();
    }

    public static function product()
    {
        return new ProductController();
    }

    public static function productFamily()
    {
        return new ProductFamilyController();
    }

    public static function site()
    {
        return new SiteController();
    }

    public static function statement()
    {
        return new StatementController();
    }

    public static function subscription()
    {
        return new SubscriptionController();
    }

    public static function transaction()
    {
        return new TransactionController();
    }

    public static function webhook()
    {
        return new WebhookController();
    }
}