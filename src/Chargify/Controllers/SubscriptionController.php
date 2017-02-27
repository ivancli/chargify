<?php
namespace IvanCLI\Chargify\Controllers;

use Illuminate\Support\Facades\Cache;
use IvanCLI\Chargify\Models\Customer;
use IvanCLI\Chargify\Models\Subscription;
use IvanCLI\Chargify\Traits\CacheFlusher;
use IvanCLI\Chargify\Traits\Curl;

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 10/24/2016
 * Time: 9:33 AM
 */

/**
 * Class SubscriptionController
 * @package IvanCLI\Chargify\Controllers
 */
class SubscriptionController
{
    use Curl, CacheFlusher;

    /**
     * Create a new subscription
     *
     * @param array $fields
     * @return Subscription|null
     */
    public function create(array $fields = array())
    {
        $validator = $this->__validate($fields);
        if ($validator['status'] != true) {
            return $validator['errors'];
        }
        return $this->__create($fields);
    }

    /**
     * Create migration record
     *
     * @param $subscription_id
     * @param array $fields
     * @return Subscription|mixed
     */
    public function createMigration($subscription_id, array $fields = array())
    {
        return $this->__createMigration($subscription_id, $fields);
    }

    /**
     * Create a preview subscription
     *
     * @param array $fields
     * @param bool $noCache
     * @return mixed|null
     */
    public function preview(array $fields = array(), $noCache = false)
    {
        if (config('chargify.caching.enable') == true && $noCache == false) {
            $encodedFields = json_encode($fields);
            return Cache::remember("subscriptions.{$encodedFields}", config('chargify.caching.ttl'), function () use ($fields) {
                return $this->__preview($fields);
            });
        } else {
            return $this->__preview($fields);
        }
    }

    /**
     * Create a preview renewal subscription
     *
     * @param $subscription_id
     * @return null
     */
    public function previewRenew($subscription_id)
    {
        return $this->__previewRenew($subscription_id);
    }

    /**
     * Load preview data of migration
     *
     * @param $subscription_id
     * @param array $fields
     * @return Subscription|mixed
     */
    public function previewMigration($subscription_id, array $fields = array())
    {
        return $this->__previewMigration($subscription_id, $fields);
    }

    /**
     * Update a subscription
     *
     * @param $subscription_id
     * @param $fields
     * @return Subscription|mixed
     */
    public function update($subscription_id, $fields)
    {
        return $this->__update($subscription_id, $fields);
    }

    /**
     * Cancel a subscription
     *
     * @param $subscription_id
     * @return bool
     */
    public function cancel($subscription_id)
    {
        return $this->__cancel($subscription_id);
    }

    /**
     * Reactivate a subscription
     *
     * @param $subscription_id
     * @return Subscription|mixed
     */
    public function reactivate($subscription_id)
    {
        return $this->__reactivate($subscription_id);
    }

    /**
     * Add coupon code to subscription
     *
     * @param $subscription_id
     * @param $coupon_code
     * @return mixed
     */
    public function addCoupon($subscription_id, $coupon_code)
    {
        return $this->__addCoupon($subscription_id, $coupon_code);
    }

    /**
     * Remove all coupon codes from subscription
     *
     * @param $subscription_id
     * @return mixed
     */
    public function removeCoupon($subscription_id)
    {
        return $this->__removeCoupon($subscription_id);
    }

    /**
     * Remove payment profile from a subscription
     *
     * @param $subscription_id
     * @param $payment_profile_id
     * @return bool|mixed
     */
    public function deletePaymentProfile($subscription_id, $payment_profile_id)
    {
        return $this->__deletePaymentProfile($subscription_id, $payment_profile_id);
    }

    /**
     * Force override data of a subscription
     *
     * @param $subscription_id
     * @param $fields
     *
     * available fields:
     * activated_at, canceled_at, cancellation_message, expires_at
     *
     * @return mixed
     */
    public function override($subscription_id, $fields)
    {
        return $this->__override($subscription_id, $fields);
    }

    /**
     * Load subscriptions in pagination
     *
     * @param int $offset
     * @param int $length
     * @return array
     */
    public function all($offset = 1, $length = 200)
    {
        //this function cannot be managed by Cache easily without TAG, since API forced the output to be paginated.
        return $this->__all($offset, $length);
    }

    /**
     * Load a subscription by subscription id
     *
     * @param $subscription_id
     * @param bool $noCache
     * @return Subscription|null
     */
    public function get($subscription_id, $noCache = false)
    {
        if (config('chargify.caching.enable') == true && $noCache == false) {
            return Cache::remember("chargify.subscriptions.{$subscription_id}", config('chargify.caching.ttl'), function () use ($subscription_id) {
                return $this->___get($subscription_id);
            });
        } else {
            return $this->___get($subscription_id);
        }
    }

    /**
     * load all subscriptions by customer id
     *
     * @param $customer_id
     * @param bool $noCache
     * @return array
     */
    public function allByCustomer($customer_id, $noCache = false)
    {
        if (config('chargify.caching.enable') == true && $noCache == false) {
            return Cache::remember("chargify.customers.{$customer_id}.subscriptions", config('chargify.caching.ttl'), function () use ($customer_id) {
                return $this->__allByCustomer($customer_id);
            });
        } else {
            return $this->__allByCustomer($customer_id);
        }
    }

    /**
     * @param $fields
     * @return Subscription|null
     */
    private function __create($fields)
    {
        $url = config('chargify.api_domain') . "subscriptions.json";
        $data = array(
            "subscription" => $fields
        );
        $data = json_decode(json_encode($data), false);
        $subscription = $this->_post($url, $data);
        if (isset($subscription->subscription)) {
            $subscription = $this->__assign($subscription->subscription);
            $this->flushSubscriptions();
            $this->flushCustomers();
        }
        return $subscription;
    }

    /**
     * @param $subscription_id
     * @param $fields
     * @return Subscription|mixed
     */
    private function __createMigration($subscription_id, $fields)
    {
        $url = config('chargify.api_domain') . "subscriptions/{$subscription_id}/migrations.json";
        $data = array(
            "migration" => $fields
        );
        $data = json_decode(json_encode($data), false);
        $subscription = $this->_post($url, $data);
        if (isset($subscription->subscription)) {
            $subscription = $this->__assign($subscription->subscription);
            $this->flushSubscriptions();
            $this->flushCustomers();
        }
        return $subscription;
    }

    /**
     * @param $fields
     * @return mixed|null
     */
    private function __preview($fields)
    {
        $url = config('chargify.api_domain') . "subscriptions/preview.json";
        $data = array(
            "subscription" => $fields
        );
        $data = json_decode(json_encode($data), false);
        $subscriptionPreview = $this->_post($url, $data);
        if (isset($subscriptionPreview->subscription_preview)) {
            $subscriptionPreview = $subscriptionPreview->subscription_preview;
        }
        return $subscriptionPreview;
    }

    /**
     * @param $id
     * @return mixed|null
     */
    private function __previewRenew($id)
    {
        $url = config('chargify.api_domain') . "subscriptions/{$id}/renewals/preview.json";
        $renewalPreview = $this->_post($url);
        if (isset($renewalPreview->renewal_preview)) {
            $renewalPreview = $renewalPreview->renewal_preview;
        }
        return $renewalPreview;
    }

    private function __previewMigration($subscription_id, $fields)
    {
        $url = config('chargify.api_domain') . "subscriptions/{$subscription_id}/migrations/preview.json";
        $data = array(
            "migration" => $fields
        );
        $data = json_decode(json_encode($data), false);
        $subscription = $this->_post($url, $data);
        if (isset($subscription->subscription)) {
            $subscription = $this->__assign($subscription->subscription);
            $this->flushSubscriptions();
            $this->flushCustomers();
        }
        return $subscription;
    }

    /**
     * @param $subscription_id
     * @param $fields
     * @return Subscription|mixed
     */
    private function __update($subscription_id, $fields)
    {
        $url = config('chargify.api_domain') . "subscriptions/{$subscription_id}.json";
        $data = array(
            "subscription" => $fields
        );
        $data = json_decode(json_encode($data), false);
        $subscription = $this->_put($url, $data);
        if (isset($subscription->subscription)) {
            $subscription = $this->__assign($subscription->subscription);
            $this->flushSubscriptions();
            $this->flushCustomers();
        }
        return $subscription;
    }

    /**
     * @param $subscription_id
     * @param string $cancellation_message
     * @return bool
     */
    private function __cancel($subscription_id, $cancellation_message = "User action")
    {
        $url = config('chargify.api_domain') . "subscriptions/{$subscription_id}.json";
        $data = array(
            "subscription" => array(
                "cancellation_message" => $cancellation_message,
            )
        );
        $data = json_decode(json_encode($data), false);
        $subscription = $this->_delete($url, json_encode($data));
        if (isset($subscription->subscription)) {
            $this->flushSubscriptions();
            return $subscription->subscription;
        }
        return $subscription;
    }

    /**
     * @param $subscription_id
     * @return Subscription|mixed
     */
    private function __reactivate($subscription_id)
    {
        $url = config('chargify.api_domain') . "subscriptions/{$subscription_id}/reactivate.json";
        $subscription = $this->_put($url);
        if (isset($subscription->subscription)) {
            $subscription = $this->__assign($subscription->subscription);
            $this->flushSubscriptions();
            $this->flushCustomers();
        }
        return $subscription;
    }

    /**
     * @param $subscription_id
     * @param $coupon_code
     * @return Subscription|mixed
     */
    private function __addCoupon($subscription_id, $coupon_code)
    {
        $url = config('chargify.api_domain') . "subscriptions/{$subscription_id}/add_coupon.json?code={$coupon_code}";
        $subscription = $this->_post($url);
        if (isset($subscription->subscription)) {
            $subscription = $this->__assign($subscription->subscription);
            $this->flushSubscriptions();
            $this->flushCustomers();
        }
        return $subscription;
    }

    /**
     * @param $subscription_id
     * @return bool|mixed
     */
    private function __removeCoupon($subscription_id)
    {
        $url = config('chargify.api_domain') . "subscriptions/{$subscription_id}/remove_coupon.json";
        $output = $this->_delete($url);
        if ($output == "Coupon removed") {
            $subscription = $this->get($subscription_id);
            $this->flushSubscriptions();
            $this->flushCustomers();
            return true;
        }
        return $output;
    }

    /**
     * @param $subscription_id
     * @param $payment_profile_id
     * @return bool|mixed
     */
    private function __deletePaymentProfile($subscription_id, $payment_profile_id)
    {
        $url = config('chargify.api_domain') . "subscriptions/{$subscription_id}/payment_profiles/{$payment_profile_id}.json";
        $output = $this->_delete($url);
        if (!isset($output->errors)) {
            $this->flushSubscriptions();
            $this->flushCustomers();
            $this->flushPaymentProfiles();
            return true;
        }
        return $output;
    }

    /**
     * @param $subscription_id
     * @param $fields
     * @return mixed
     */
    public function __override($subscription_id, $fields)
    {
        $url = config('chargify.api_domain') . "subscriptions/{$subscription_id}.json";
        $data = array(
            "subscription" => $fields
        );
        $data = json_decode(json_encode($data), false);
        $result = $this->_put($url, json_encode($data));
        $this->flushSubscriptions();
        return $result;
    }

    /**
     * @param $offset
     * @param $length
     * @return array
     */
    private function __all($offset, $length)
    {
        $url = config('chargify.api_domain') . "subscriptions.json";
        if ($offset >= 0 && $length > 0) {
            $page = ceil($offset / $length);
            $url .= "?per_page={$length}&page={$page}";
        }
        $subscriptions = $this->_get($url);
        if (is_array($subscriptions)) {
            $subscriptions = array_pluck($subscriptions, 'subscription');
            $output = array();
            foreach ($subscriptions as $subscription) {
                $output[] = $this->__assign($subscription);
            }
            return $output;
        } else {
            return $subscriptions;
        }
    }

    /**
     * @param $subscription_id
     * @return Subscription|null
     */
    private function ___get($subscription_id)
    {
        $url = config('chargify.api_domain') . "subscriptions/{$subscription_id}.json";
        $subscription = $this->_get($url);
        if (isset($subscription->subscription)) {
            $subscription = $subscription->subscription;
            $subscription = $this->__assign($subscription);
        }
        return $subscription;
    }

    /**
     * @param $customer_id
     * @return array
     */
    private function __allByCustomer($customer_id)
    {
        $url = config('chargify.api_domain') . "customers/{$customer_id}/subscriptions.json";
        $subscriptions = $this->_get($url);
        if (is_array($subscriptions)) {
            $subscriptions = array_pluck($subscriptions, 'subscription');
            $output = array();
            foreach ($subscriptions as $subscription) {
                $output[] = $this->__assign($subscription);
            }
            return $output;
        } else {
            return $subscriptions;
        }
    }

    /**
     * @param $input_subscription
     * @return Subscription
     */
    private function __assign($input_subscription)
    {
        $subscription = new Subscription;
        foreach ($input_subscription as $key => $value) {
            switch ($key) {
                case "customer":
                    if (isset($value->id)) {
                        $subscription->customer_id = $value->id;
                    }
                    break;
                case "product":
                    if (isset($value->id)) {
                        $subscription->product_id = $value->id;
                    }
                    break;
                case "credit_card":
                    if (isset($value->id)) {
                        $subscription->credit_card_id = $value->id;
                    }
                    break;
                case "bank_account":
                    if (isset($value->id)) {
                        $subscription->bank_account_id = $value->id;
                    }
                    break;
                default:
                    if (property_exists($subscription, $key)) {
                        $subscription->$key = $value;
                    }
            }
        }
        return $subscription;
    }

    /**
     * @param $fields
     * @return array
     */
    private function __validate($fields)
    {
        $status = true;
        $errors = [];
        if (!isset($fields['product_handle']) && !isset($fields['product_id'])) {
            $status = false;
            $errors[] = "product_handle or product_id is required";
        }
        if (!isset($fields['customer_attributes']) && !isset($fields['customer_id']) && !isset($fields['customer_reference'])) {
            $status = false;
            $errors[] = "please provide customer_attributes or customer_id or customer reference.";
        }
        if ($status === false) {
            return compact(['status', 'errors']);
        }
        return compact(['status']);
    }
}