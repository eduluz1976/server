<?php
/**
 * Created by PhpStorm.
 * User: eduardoluz
 * Date: 2018-09-30
 * Time: 11:44 PM
 */

namespace eduluz1976\server;


class ReportUnity
{

    const PORT_REPORT = 'port_report';
    const PORT_INPUT = 'port_input';


    protected $lsConnectedInstances = [];
    protected $sockets = [];

    public function __construct($config=[])
    {


    }



    protected function init() {
        $factory = new \Socket\Raw\Factory();

        $this->sockets[self::PORT_REPORT] = $factory->createServer('tcp://0.0.0.0:3800');
        $this->sockets[self::PORT_INPUT] = $factory->createServer('tcp://0.0.0.0:3730');

    }


    public function start() {


        while (true) {
            $t = time();
            echo "\n $t : ";
            usleep(1000000);
        }
    }


}