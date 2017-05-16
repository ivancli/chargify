<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 10/27/2016
 * Time: 9:43 AM
 */

namespace IvanCLI\Chargify\Controllers;


use IvanCLI\Chargify\Models\Statement;
use IvanCLI\Chargify\Traits\Curl;

/**
 * Class StatementController
 * @package IvanCLI\Chargify\Controllers
 */
class StatementController
{
    use Curl;

    protected $accessPoint;

    protected $apiDomain;

    public function __construct($accessPoint)
    {
        $this->accessPoint = $accessPoint;

        $this->apiDomain = config("chargify.{$this->accessPoint}.api_domain");
    }

    /**
     * Get all statements of a subscription
     *
     * @param $subscription_id
     * @param null $page
     * @param null $per_page
     * @return array|mixed
     */
    public function allBySubscription($subscription_id, $page = null, $per_page = null)
    {
        return $this->__allBySubscription($subscription_id, $page, $per_page);
    }

    /**
     * Get all statement IDs of a subscription
     *
     * @param $subscription_id
     * @param null $queryString
     * @return mixed
     */
    public function allIDsBySubscription($subscription_id, $queryString = null)
    {
        return $this->__allIDsBySubscription($subscription_id, $queryString);
    }

    /**
     * Get all statement IDs
     *
     * @param null $page
     * @param null $per_page
     * @return mixed
     */
    public function allIDs($page = null, $per_page = null)
    {
        return $this->__allIDs($page, $per_page);
    }

    /**
     * Get a statement by statement ID
     *
     * @param $statement_id
     * @return Statement|mixed
     */
    public function get($statement_id)
    {
        return $this->___get($statement_id);
    }

    /**
     * @param $subscription_id
     * @param $page
     * @param $per_page
     * @return array|mixed
     */
    private function __allBySubscription($subscription_id, $page, $per_page)
    {
        $url = $this->apiDomain . "subscription_id/{$subscription_id}/statements.json";
        if (!is_null($page) && !is_null($per_page)) {
            $url .= "?page={$page}&per_page={$per_page}";
        }
        $statements = $this->_get($url);
        if (is_array($statements)) {
            $statements = array_pluck($statements, 'statement');
            $output = array();
            foreach ($statements as $statement) {
                $output[] = $this->__assign($statement);
            }
            return $output;
        } else {
            return $statements;
        }
    }

    /**
     * @param $subscription_id
     * @param $queryString
     * @return mixed
     */
    private function __allIDsBySubscription($subscription_id, $queryString)
    {
        $url = $this->apiDomain . "subscription_id/{$subscription_id}/statements/ids.json";
        if (!is_null($queryString)) {
            $url .= "?" . $queryString;
        }
        $statementIds = $this->_get($url);
        return $statementIds;
    }

    /**
     * @param $page
     * @param $per_page
     * @return mixed
     */
    private function __allIDs($page, $per_page)
    {
        $url = $this->apiDomain . "statements/ids.json";
        if (!is_null($page) && !is_null($per_page)) {
            $url .= "?page={$page}&per_page={$per_page}";
        }
        $statementIds = $this->_get($url);
        return $statementIds;
    }

    /**
     * @param $statement_id
     * @return Statement|mixed
     */
    private function ___get($statement_id)
    {
        $url = $this->apiDomain . "statements/{$statement_id}.json";
        $statement = $this->_get($url);
        if (isset($statement->statement)) {
            $statement = $statement->statement;
            $output = $this->__assign($statement);
            return $output;
        } else {
            return $statement;
        }
    }

    /**
     * @param $input_statement
     * @return Statement
     */
    private function __assign($input_statement)
    {
        $statement = new Statement;
        foreach ($input_statement as $key => $value) {
            if (property_exists($statement, $key)) {
                $statement->$key = $value;
            }
        }
        return $statement;
    }
}