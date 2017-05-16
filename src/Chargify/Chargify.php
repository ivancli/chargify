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

    public static function adjustment($accessPoint = 'au')
    {
        return new AdjustmentController($accessPoint);
    }

    public static function allocation($accessPoint = 'au')
    {
        return new AllocationController($accessPoint);
    }

    public static function charge($accessPoint = 'au')
    {
        return new ChargeController($accessPoint);
    }

    public static function component($accessPoint = 'au')
    {
        return new ComponentController($accessPoint);
    }

    public static function coupon($accessPoint = 'au')
    {
        return new CouponController($accessPoint);
    }

    public static function customer($accessPoint = 'au')
    {
        return new CustomerController($accessPoint);
    }

    public static function invoice($accessPoint = 'au')
    {
        return new InvoiceController($accessPoint);
    }

    public static function note($accessPoint = 'au')
    {
        return new NoteController($accessPoint);
    }

    public static function payment($accessPoint = 'au')
    {
        return new PaymentController($accessPoint);
    }

    public static function paymentProfile($accessPoint = 'au')
    {
        return new PaymentProfileController($accessPoint);
    }

    public static function product($accessPoint = 'au')
    {
        return new ProductController($accessPoint);
    }

    public static function productFamily($accessPoint = 'au')
    {
        return new ProductFamilyController($accessPoint);
    }

    public static function site($accessPoint = 'au')
    {
        return new SiteController($accessPoint);
    }

    public static function statement($accessPoint = 'au')
    {
        return new StatementController($accessPoint);
    }

    public static function subscription($accessPoint = 'au')
    {
        return new SubscriptionController($accessPoint);
    }

    public static function transaction($accessPoint = 'au')
    {
        return new TransactionController($accessPoint);
    }

    public static function webhook($accessPoint = 'au')
    {
        return new WebhookController($accessPoint);
    }
}