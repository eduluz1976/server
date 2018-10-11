<?php

namespace eduluz1976\server\exception;


class PluginException extends \Exception
{
    const EXCEPTION_CLASS_DOES_NOT_EXISTS = 5020;
    const EXCEPTION_INVALID_CLASS = 5021;
    const EXCEPTION_PLUGIN_CODE_DOES_NOT_FOUND = 5022;

}