<?php
namespace eduluz1976\server;


use eduluz1976\server\exception\PluginException;
use eduluz1976\server\utils\PluginManager;



class InvalidPluginClass {

}

class ValidPluginClass extends PluginBase {
    /**
     * Pass the initialization parameters to plugin
     *
     * @param array $spec
     * @return mixed
     */
    protected function bind($spec = [])
    {
        // TODO: Implement bind() method.
    }

    /**
     * Execute the $command, passing parameters
     *
     * @param string $command
     * @param array $parms
     * @return mixed
     */
    public function exec($command, $parms = [])
    {
        // TODO: Implement exec() method.
    }

}


class PluginTest extends \PHPUnit\Framework\TestCase
{

    protected $pluginManager;
    protected $node;

    protected function getPluginManager() {

        if (!$this->pluginManager) {

            $this->pluginManager = new class {
                use PluginManager;
            };

        }

        return $this->pluginManager;
    }

    protected function getNode() {
        if (!$this->node) {
            $this->node = new Node();
            $this->node->setAllConfig(json_encode(['test'=>[
                "listen_addr_range" => "0.0.0.0:37000-37100",
                "key_false" => false,
                "key_true" => true
            ]]));
        }
        return $this->node;
    }



    public function testCreateInvalidPlugin() {
        $plugin = $this->getPluginManager();
        $this->expectExceptionCode(PluginException::EXCEPTION_INVALID_CLASS);
        $plugin->forgePlugin(['className'=>InvalidPluginClass::class]);
    }


    public function testCreateValidPlugin() {
        $plugin = $this->getPluginManager();
        //$this->expectExceptionCode(PluginException::EXCEPTION_INVALID_CLASS);
        $plugin->forgePlugin(['className'=>ValidPluginClass::class]);

        $this->assertTrue(is_object($plugin));

    }


    public function testsetContainer() {
        $pluginManager = $this->getPluginManager();
        $pluginManager->forgePlugin(['className'=>ValidPluginClass::class],'test');

        $plugin = $pluginManager->getPlugin('test');

        $plugin->setContainer($this->getNode());

        $this->assertTrue($plugin->getContainer()->getConfig('key_true'));

        //print_r($plugin);

    }


}