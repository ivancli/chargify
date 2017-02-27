<?php
/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 10/27/2016
 * Time: 10:06 AM
 */

namespace IvanCLI\Chargify\Models;

/**
 * Please check
 * https://docs.chargify.com/api-webhooks
 * for related documentation provided by Chargify
 *
 * Class Webhook
 * @package IvanCLI\Chargify\Models
 */
class Webhook
{
    public $id;
    public $successful;
    public $event;
    public $body;
    public $signature;
    public $signature_hmac_sha_256;
    public $created_at;
    public $accepted_at;
    public $last_sent_at;
    public $last_error_at;
    public $last_sent_url;
    public $last_error;
}