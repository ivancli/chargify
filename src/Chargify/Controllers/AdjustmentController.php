<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 10/26/2016
 * Time: 5:15 PM
 */

namespace IvanCLI\Chargify\Controllers;


use IvanCLI\Chargify\Models\Adjustment;
use IvanCLI\Chargify\Traits\Curl;

class AdjustmentController
{
    use Curl;

    /**
     * Create adjustment
     *
     * @param $subscription_id
     * @param array $fields
     * @return Adjustment|mixed
     */
    public function create($subscription_id, array $fields = array())
    {
        return $this->__create($subscription_id, $fields);
    }

    /**
     * @param $subscription_id
     * @param $fields
     * @return Adjustment|mixed
     */
    private function __create($subscription_id, $fields)
    {
        $url = config('chargify.api_domain') . "subscriptions/{$subscription_id}/adjustments.json";
        $data = array(
            "adjustment" => $fields
        );
        $data = json_decode(json_encode($data), false);
        $adjustment = $this->_post($url, $data);
        if (isset($adjustment->adjustment)) {
            $adjustment = $this->__assign($adjustment->adjustment);
        }
        return $adjustment;
    }


    /**
     * @param $input_adjustment
     * @return Adjustment
     */
    private function __assign($input_adjustment)
    {
        $adjustment = new Adjustment;
        foreach ($input_adjustment as $key => $value) {
            if (property_exists($adjustment, $key)) {
                $adjustment->$key = $value;
            }
        }
        return $adjustment;
    }
}