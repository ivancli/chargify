<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 10/25/2016
 * Time: 1:26 PM
 */

namespace IvanCLI\Chargify\Controllers;


use Illuminate\Support\Facades\Cache;
use IvanCLI\Chargify\Models\PaymentProfile;
use IvanCLI\Chargify\Traits\Curl;

class PaymentProfileController
{
    use Curl;

    protected $accessPoint;

    protected $apiDomain;

    public function __construct($accessPoint)
    {
        $this->accessPoint = $accessPoint;

        $this->apiDomain = config("chargify.{$this->accessPoint}.api_domain");
    }

    public function create($fields)
    {
        return $this->__create($fields);
    }

    public function update($payment_profile_id, $fields)
    {
        return $this->__update($payment_profile_id, $fields);
    }

    /**
     * load a payment profile by payment profile id
     *
     * @param $payment_profile_id
     * @return PaymentProfile|null
     */
    public function get($payment_profile_id)
    {
        if (config('chargify.caching.enable') == true) {
            return Cache::remember("{$this->apiDomain}.payment_profiles.{$payment_profile_id}", config('chargify.caching.ttl'), function () use ($payment_profile_id) {
                return $this->___get($payment_profile_id);
            });
        } else {
            return $this->___get($payment_profile_id);
        }
    }

    private function __create($fields)
    {
        $url = $this->apiDomain . "payment_profiles.json";
        $data = array(
            "payment_profile" => $fields
        );
        $data = json_decode(json_encode($data), false);
        $paymentProfile = $this->_post($this->accessPoint, $url, $data);
        if (isset($paymentProfile->payment_profile)) {
            $paymentProfile = $this->__assign($paymentProfile->payment_profile);
        }
        return $paymentProfile;
    }

    private function __update($payment_profile_id, $fields)
    {
        $url = $this->apiDomain . "payment_profiles/{$payment_profile_id}.json";
        $data = array(
            "payment_profile" => $fields
        );
        $data = json_decode(json_encode($data), false);
        $paymentProfile = $this->_put($this->accessPoint, $url, $data);
        if (isset($paymentProfile->payment_profile)) {
            $paymentProfile = $this->__assign($paymentProfile->payment_profile);
            Cache::forget("{$this->accessPoint}.payment_profiles.{$payment_profile_id}");
        }
        return $paymentProfile;
    }

    /**
     * @param $payment_profile_id
     * @return PaymentProfile|null
     */
    private function ___get($payment_profile_id)
    {
        $url = $this->apiDomain . "payment_profiles/{$payment_profile_id}.json";
        $paymentProfile = $this->_get($this->accessPoint, $url);
        if (isset($paymentProfile->payment_profile)) {
            $paymentProfile = $paymentProfile->payment_profile;
            $output = $this->__assign($paymentProfile);
            return $output;
        } else {
            return $paymentProfile;
        }
    }

    /**
     * @param $input_payment_profile
     * @return PaymentProfile
     */
    private function __assign($input_payment_profile)
    {
        $paymentProfile = new PaymentProfile($this->apiDomain);
        foreach ($input_payment_profile as $key => $value) {
            if (property_exists($paymentProfile, $key)) {
                $paymentProfile->$key = $value;
            }
        }
        return $paymentProfile;
    }
}