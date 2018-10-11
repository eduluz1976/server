<?php
/**
 * Created by PhpStorm.
 * User: eduardoluz
 * Date: 2018-10-10
 * Time: 10:07 PM
 */

namespace eduluz1976\server;


use eduluz1976\server\exception\PluginException;

abstract class PluginBase
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var Node
     */
    protected $container;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return PluginBase
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return Node
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param Node $container
     * @return PluginBase
     */
    public function setContainer($container)
    {
        $this->container = $container;
        return $this;
    }





    /**
     * PluginBase constructor.
     * @param array $spec
     * @param mixed $code
     */
    public function __construct($spec=[],$code=false)
    {
        if ($code) {
            $this->setCode($code);
        }

        if (!empty($spec)) {
            $this->bind($spec);
        }
    }


    /**
     * Pass the initialization parameters to plugin
     *
     * @param array $spec
     * @return mixed
     */
    protected abstract function bind($spec=[]);


    /**
     * Execute the $command, passing parameters
     *
     * @param string $command
     * @param array $parms
     * @return mixed
     */
    public abstract function exec($command, $parms=[]);


}