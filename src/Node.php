<?php

namespace eduluz1976\server;


use eduluz1976\server\utils\ConfigurationManager;
use eduluz1976\server\utils\EventManager;
use eduluz1976\server\utils\LogManager;
use eduluz1976\server\utils\PluginManager;

/**
 *
 *
 * Lifecycle
 *
 *
 *
 *
 * Class Node
 * @package eduluz1976\server
 */
class Node
{
    use ConfigurationManager;
    use PluginManager;
    use EventManager;
    use LogManager;

    const EVENT_INIT = 'onInit';
    const EVENT_START = 'onInit';
    const EVENT_PAUSE = 'onInit';
    const EVENT_RESUME = 'onInit';
    const EVENT_STOP = 'onInit';
    const EVENT_REQUEST_CONNECTION = 'onInit';
    const EVENT_REQUEST_CONNECTION_ACCEPTED = 'onInit';
    const EVENT_REMOTE_DISCONNECTION = 'onInit';

    // target
    const EVENT_ACCEPT_CONNECTION = 'onInit';
    const EVENT_RECEIVE_CONNECTION = 'onInit';



    public function filler() {

    }


}