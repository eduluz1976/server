<?php
namespace eduluz1976\server\utils;


use eduluz1976\server\exception\PluginException;
use eduluz1976\server\base\PluginBase;

trait PluginManager
{

    /**
     * @var array
     */
    protected $lsPlugins = [];


    public function addPlugin(PluginBase &$plugin, $code=false) {

        if (!$plugin->getCode()) {
            if (!$code) {
                $code = md5(count($this->lsPlugins));
            }
            $plugin->setCode($code);
        }

        $this->lsPlugins[$code] = $plugin;
    }

    /**
     * @param array $spec
     * @param bool|string $code
     * @return PluginBase
     * @throws PluginException
     */
    public function forgePlugin($spec=[], $code=false) {

        $plugin = self::factory($spec);
        $this->addPlugin($plugin, $code);
        return $plugin;
    }


    /**
     * @param string $code
     * @return PluginBase
     * @throws PluginException
     */
    public function getPlugin($code)  {
        if (!isset($this->lsPlugins[$code])) {
            throw new PluginException("Code does not found: $code", PluginException::EXCEPTION_PLUGIN_CODE_DOES_NOT_FOUND);
        }

        return $this->lsPlugins[$code];
    }


    /**
     * @return array list of Plugins
     */
    public function getPluginList() {
        return $this->lsPlugins;
    }



    /**
     * @param array $spec
     * @param mixed $code
     * @return PluginBase
     * @throws PluginException
     */
    public static function factory($spec=[],$code=false) {

        if (isset($spec['className'])) {
            $className = $spec['className'];
        }

        if (!class_exists($className)) {
            throw new PluginException("Class $className does not exists", PluginException::EXCEPTION_CLASS_DOES_NOT_EXISTS);
        }

        $obj = new $className($spec, $code);

        if (!is_subclass_of($obj,\eduluz1976\server\base\PluginBase::class)) {
            throw new PluginException("Class $className does not extends PluginBase class", PluginException::EXCEPTION_INVALID_CLASS);
        }


        if ($code) {
            $obj->setCode($code);
        }

        return $obj;
    }

}