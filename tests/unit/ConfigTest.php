<?php
namespace eduluz1976\server;


use eduluz1976\action\Action;
use eduluz1976\server\exception\ConfigException;
use eduluz1976\server\exception\RangeException;
use eduluz1976\server\utils\ConfigurationManager;

class ConfigTest extends \PHPUnit\Framework\TestCase
{


    protected $configManager;
    protected function getConfigManager() {

        if (!$this->configManager) {

            $this->configManager = new class {
                  use ConfigurationManager;

            };

        }

        return $this->configManager;
    }



    public function testLoadConfigFileDoesNotExists() {

        $config = $this->getConfigManager();

        $this->expectExceptionCode(ConfigException::EXCEPTION_FILE_DOES_NOT_EXISTS);
        $config->loadConfiguration("invalid_file");

    }


    public function testLoadValidConfigFile() {
        $config = $this->getConfigManager();

        $config->loadConfiguration(__DIR__."/../../config/conf.json");

        $this->assertTrue(is_string($config->getConfig('listen_addr_range')));

    }

    public function testLoadInvalidContext() {
        $config = $this->getConfigManager();

        $config->loadConfiguration(__DIR__."/../../config/conf.json");

        $config->setConfigContext('xpto');

        $this->expectExceptionCode(ConfigException::EXCEPTION_INVALID_CONTEXT);
        $config->getConfig('listen_addr_range');


    }

    public function testSetInvalidContents() {

        $config = $this->getConfigManager();

        $str = '{
  "dev" : {
    "listen_addr_range" : "0.0.0.0:37000-37100",
    "num_min_connections" : 2,
    "num_max_connections" : 6
  },  
}';
        $this->expectExceptionCode(ConfigException::EXCEPTION_INVALID_CONTENTS);
        $config->setAllConfig($str);

    }



    public function testSetValidContents() {

        $config = $this->getConfigManager();

        $str = '{
  "dev" : {
    "listen_addr_range" : "0.0.0.0:37000-37100",
    "num_min_connections" : 2,
    "num_max_connections" : 6
  }  
}';

        $config->setAllConfig($str);

        $config->setConfigContext('dev');

        $value = $config->getConfig('listen_addr_range');

        $this->assertEquals("0.0.0.0:37000-37100", $value);

    }






}
