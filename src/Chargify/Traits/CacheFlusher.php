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
        Cache::tags('chargify')->flush();
    }

    public function flushComponents()
    {
        Cache::tags('components')->flush();
    }

    public function flushCoupons()
    {
        Cache::tags('coupons')->flush();
    }

    public function flushCustomers()
    {
        Cache::tags('customers')->flush();
    }

    public function flushNotes(){
        Cache::tags('notes')->flush();
    }

    public function flushInvoices()
    {
        Cache::tags('invoices')->flush();
    }

    public function flushPaymentProfiles()
    {
        Cache::tags('payment_profiles')->flush();
    }

    public function flushProducts()
    {
        Cache::tags('products')->flush();
    }

    public function flushProductFamilies()
    {
        Cache::tags('product_families')->flush();
    }

    public function flushSubscriptions()
    {
        Cache::tags('subscriptions')->flush();
    }
}