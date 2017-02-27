<?php

namespace IvanCLI\Chargify\Models;
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 23/10/2016
 * Time: 1:10 PM
 */

/**
 * Please check
 * https://docs.chargify.com/api-invoices
 * for related documentation provided by Chargify
 *
 * Class Invoice
 * @package IvanCLI\Chargify\Models
 */
class Invoice
{
    public $id;
    public $subscription_id;
    public $statement_id;
    public $site_id;
    public $state;
    public $total_amount_in_cents;
    public $paid_at;
    public $created_at;
    public $updated_at;
    public $amount_due_in_cents;
    public $number;
    public $charges;
    public $payments_and_credits;

    public function __construct($id = null)
    {

    }
}