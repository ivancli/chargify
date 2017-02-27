<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 10/25/2016
 * Time: 11:44 AM
 */

namespace IvanCLI\Chargify\Controllers;


use IvanCLI\Chargify\Models\Transaction;
use IvanCLI\Chargify\Traits\CacheFlusher;
use IvanCLI\Chargify\Traits\Curl;

class TransactionController
{
    use Curl, CacheFlusher;

    public function all()
    {
        return $this->__all();
    }

    public function get($transaction_id)
    {
        return $this->___get($transaction_id);
    }

    public function allBySubscription($subscription_id, $queryString = null)
    {
        return $this->__allBySubscription($subscription_id, $queryString);
    }

    private function __all()
    {
        $url = config('chargify.api_domain') . "transactions.json";
        $transactions = $this->_get($url);
        if (is_array($transactions)) {
            $transactions = array_pluck($transactions, 'transaction');
            $output = array();
            foreach ($transactions as $transaction) {
                $output[] = $this->__assign($transaction);
            }
            return $output;
        } else {
            return $transactions;
        }
    }

    private function ___get($transaction_id)
    {
        $url = config('chargify.api_domain') . "transactions/{$transaction_id}.json";
        $transaction = $this->_get($url);
        if (isset($transaction->transaction)) {
            $transaction = $transaction->transaction;
            $transaction = $this->__assign($transaction);
        }
        return $transaction;
    }

    private function __allBySubscription($subscription_id, $queryString)
    {
        $url = config('chargify.api_domain') . "subscriptions/{$subscription_id}/transactions.json";
        if (!is_null($queryString)) {
            $url .= "?" . $queryString;
        }
        $transactions = $this->_get($url);
        if (is_array($transactions)) {
            $transactions = array_pluck($transactions, 'transaction');
            $output = array();
            foreach ($transactions as $transaction) {
                $output[] = $this->__assign($transaction);
            }
            return $output;
        } else {
            return $transactions;
        }
    }

    private function __assign($input_transaction)
    {
        $transaction = new Transaction;
        foreach ($input_transaction as $key => $value) {
            if (property_exists($transaction, $key)) {
                $transaction->$key = $value;
            }
        }
        return $transaction;
    }
}