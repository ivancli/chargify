<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 10/25/2016
 * Time: 4:17 PM
 */

namespace IvanCLI\Chargify\Traits;


use Illuminate\Support\Facades\Cache;

trait CacheFlusher
{
    public function flushAll()
    {
        Cache::forget('chargify');
    }

    public function flushComponents()
    {
        Cache::forget('components');
    }

    public function flushCoupons()
    {
        Cache::forget('coupons');
    }

    public function flushCustomers()
    {
        Cache::forget('customers');
    }

    public function flushNotes(){
        Cache::forget('notes');
    }

    public function flushInvoices()
    {
        Cache::forget('invoices');
    }

    public function flushPaymentProfiles()
    {
        Cache::forget('payment_profiles');
    }

    public function flushProducts()
    {
        Cache::forget('products');
    }

    public function flushProductFamilies()
    {
        Cache::forget('product_families');
    }

    public function flushSubscriptions()
    {
        Cache::forget('subscriptions');
    }
}