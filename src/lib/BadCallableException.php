<?php
/**
 * Created by PhpStorm.
 * User: blixit
 * Date: 05/06/17
 * Time: 22:58
 */

namespace GWA;


use \Exception;
use Throwable;

class BadCallableException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}