<?php

namespace eduluz1976\server\base;


use eduluz1976\server\exception\PluginException;

abstract class PluginBase extends RunnableBase
{



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




}