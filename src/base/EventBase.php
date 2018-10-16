<?php
namespace eduluz1976\server\base;


abstract class EventBase extends RunnableBase
{

    /**
     * @var Node
     */
    protected $container;

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





}