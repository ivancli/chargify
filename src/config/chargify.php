<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 23/10/2016
 * Time: 1:02 AM
 */
return [

    'caching' => array(

        'enable' => true,

        'ttl' => 60 * 24

    ),

    //the api key generate in Chargify settings
    'api_key' => env('CHARGIFY_API_KEY'),

    //it's always 'x'
    'api_password' => env('CHARGIFY_API_PASSWORD', 'x'),

    //the domain of Chargify account
    'api_domain' => env('CHARGIFY_API_DOMAIN'),

    //the share key provided in Chargify settings used to generate links
    'api_share_key' => env("CHARGIFY_API_SHARE_KEY"),
];