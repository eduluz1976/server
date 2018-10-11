<?php
/**
 * Created by PhpStorm.
 * User: eduardoluz
 * Date: 2018-10-08
 * Time: 9:12 PM
 */

namespace eduluz1976\server\exception;


class ConfigException extends \Exception
{
    const EXCEPTION_FILE_DOES_NOT_EXISTS = 5010;
    const EXCEPTION_INVALID_CONTENTS = 5011;
    const EXCEPTION_INVALID_CONTEXT = 5012;

}