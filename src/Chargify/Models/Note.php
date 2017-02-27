<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 10/26/2016
 * Time: 5:00 PM
 */

namespace IvanCLI\Chargify\Models;


use IvanCLI\Chargify\Controllers\SubscriptionController;

class Note
{
    public $body;
    public $created_at;
    public $id;
    public $sticky;
    public $subscription_id;
    public $updated_at;

    private $subscriptionController;

    public function __construct()
    {
        $this->subscriptionController = new SubscriptionController;
    }

    public function subscription()
    {
        return $this->subscriptionController->get($this->subscription_id);
    }
}