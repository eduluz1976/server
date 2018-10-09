<?php
/**
 * Created by PhpStorm.
 * User: eduardoluz
 * Date: 2018-09-30
 * Time: 11:44 PM
 */

namespace eduluz1976\server;


class ServicetUnity
{


    const PORT_REPORT = 'port_report';
    const PORT_INPUT = 'port_input';


    protected $id=0;
    protected $hash='';

    protected $lsConnectedInstances = [];
    protected $sockets = [];

    public function __construct($config=[])
    {
        $this->id = time();
        $this->hash = hash('SHA256', $this->id);
    }



    protected function init() {
        $factory = new \Socket\Raw\Factory();

        $found = false;

        //for ($i=)
        $socket = $factory->createClient('tcp://127.0.0.1:3800');
        $this->sockets[self::PORT_REPORT] = $socket;

        
//        $socket->


//        $this->sockets[self::PORT_INPUT] = $factory->createServer('tcp://0.0.0.0:3730');

    }


    public function start() {


        while (true) {
            $t = time();
            echo "\n $t : SU";
            usleep(500000);
            $this->sockets[self::PORT_REPORT]->
        }
    }


}