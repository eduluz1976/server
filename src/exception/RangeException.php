<?php
/**
 * Created by PhpStorm.
 * User: eduardoluz
 * Date: 2018-10-08
 * Time: 9:12 PM
 */

namespace eduluz1976\server\exception;

class RangeException extends \Exception
{
    const EXCEPTION_EMPTY_RANGE = 5000;
    const EXCEPTION_PORT_IS_MISSING = 5001;
    const EXCEPTION_INVALID_PORT_CHARACTER = 5002;
    const EXCEPTION_INVALID_PORT_INTERVAL = 5003;
    const EXCEPTION_ADDR_IS_MISSING = 5004;
}
