<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 10/25/2016
 * Time: 11:43 AM
 */

namespace IvanCLI\Chargify\Controllers;


use IvanCLI\Chargify\Traits\CacheFlusher;
use IvanCLI\Chargify\Traits\Curl;

/**
 * Please check
 * https://docs.chargify.com/api-sites
 * for related documentation provided by Chargify
 *
 * Class SiteController
 * @package IvanCLI\Chargify\Controllers
 */
class SiteController
{
    use Curl, CacheFlusher;

    /**
     * Get statistics of the site
     * Please check
     * https://docs.chargify.com/api-stats
     * for related documentation provided by Chargify
     *
     * @return mixed
     */
    public function stats()
    {
        return $this->__stats();
    }

    /**
     * @param null $scope
     * $scope accept the following values:
     * null, 'all', 'customers'
     *
     * @return bool|mixed
     */
    public function cleanup($scope = null)
    {
        return $this->__cleanup($scope);
    }

    private function __stats()
    {
        $url = config('chargify.api_domain') . "stats.json";
        $stats = $this->_get($url);
        return $stats;
    }

    /**
     * @param $scope
     * @return bool|mixed
     */
    private function __cleanup($scope)
    {
        $url = config('chargify.api_domain') . "site/clear_data.json";
        if (!is_null($scope)) {
            $url .= "?cleanup_scope={$scope}";
        }
        $result = $this->_post($url);
        if (!isset($result->errors)) {
            $result = true;
            /*TODO please be notice that without the use of cache tag, there is no easy way to clear all Chargify related cache in one go*/
            /*TODO so clear cache manually for this function*/
        }
        return $result;
    }

}