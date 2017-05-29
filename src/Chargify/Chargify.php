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

    public static function adjustment($storeLocation = 'au')
    {
        return new AdjustmentController($storeLocation);
    }

    public static function allocation($storeLocation = 'au')
    {
        return new AllocationController($storeLocation);
    }

    public static function charge($storeLocation = 'au')
    {
        return new ChargeController($storeLocation);
    }

    public static function component($storeLocation = 'au')
    {
        return new ComponentController($storeLocation);
    }

    public static function coupon($storeLocation = 'au')
    {
        return new CouponController($storeLocation);
    }

    public static function customer($storeLocation = 'au')
    {
        return new CustomerController($storeLocation);
    }

    public static function invoice($storeLocation = 'au')
    {
        return new InvoiceController($storeLocation);
    }

    public static function note($storeLocation = 'au')
    {
        return new NoteController($storeLocation);
    }

    public static function payment($storeLocation = 'au')
    {
        return new PaymentController($storeLocation);
    }

    public static function paymentProfile($storeLocation = 'au')
    {
        return new PaymentProfileController($storeLocation);
    }

    public static function product($storeLocation = 'au')
    {
        return new ProductController($storeLocation);
    }

    public static function productFamily($storeLocation = 'au')
    {
        return new ProductFamilyController($storeLocation);
    }

    public static function site($storeLocation = 'au')
    {
        return new SiteController($storeLocation);
    }

    public static function statement($storeLocation = 'au')
    {
        return new StatementController($storeLocation);
    }

    public static function subscription($storeLocation = 'au')
    {
        return new SubscriptionController($storeLocation);
    }

    public static function transaction($storeLocation = 'au')
    {
        return new TransactionController($storeLocation);
    }

    public static function webhook($storeLocation = 'au')
    {
        return new WebhookController($storeLocation);
    }
}