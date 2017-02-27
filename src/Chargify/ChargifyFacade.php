<?php
namespace IvanCLI\Chargify;

use Illuminate\Support\Facades\Facade;
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 23/10/2016
 * Time: 1:07 AM
 */
class ChargifyFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'chargify';
    }
}