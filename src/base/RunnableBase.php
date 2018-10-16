<?php

namespace eduluz1976\server\base;


abstract class RunnableBase
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
     * Execute the $command, passing parameters
     *
     * @param string $command
     * @param array $parms
     * @return mixed
     */
    public abstract function exec($command, $parms=[]);


}