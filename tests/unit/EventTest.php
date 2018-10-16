<?php
namespace eduluz1976\server;


use eduluz1976\server\base\PluginBase;
use eduluz1976\server\exception\PluginException;
use eduluz1976\server\utils\EventManager;
use eduluz1976\server\utils\PluginManager;



class ValidPluginClass2 extends PluginBase {


    public static $value=1;

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
        foreach ($parms as $v) {
            self::$value *= $v;
        }

    }

}


class EventTest extends \PHPUnit\Framework\TestCase
{

    protected $eventManager;
    protected $node;

    protected function getEventManager() {

        if (!$this->eventManager) {

            $this->eventManager = new class {
                use EventManager;
            };

        }

        return $this->eventManager;
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


    /**
     *
     * Get an Node instance, add the 'log1' plugin on it, and add an event pointing to this log1
     *
     *
     * @throws PluginException
     */
    public function testAddValidEvent() {

        $this->node = null;

        $this->getNode()->forgePlugin(['className'=>ValidPluginClass::class],'log1');
        $this->getNode()->getPlugin('log1')->setContainer($this->getNode());

        $this->getNode()->addEvent('onInit', 'plugin:log1');

        $this->assertCount(1, $this->getNode()->getEvents());


        $this->expectExceptionCode(PluginException::EXCEPTION_PLUGIN_CODE_DOES_NOT_FOUND);
        $this->getNode()->addEvent('onInit', 'plugin:log2');



    }


    /**
     * Get an Node instance, add the 'log1' plugin on it, and add an event pointing to this log1.
     * - Try to run the event passing 2 parameters (integer, positive) - result ok;
     * - Try to run the same event with 2 paraeters (the 2nd is zero) - error
     */
    public function testRunEvent() {

        $this->node = null;

        $this->getNode()->forgePlugin(['className'=>ValidPluginClass2::class],'log2');
        $this->getNode()->getPlugin('log2')->setContainer($this->getNode());

        $this->getNode()->addEvent('onInit', 'plugin:log2');


        $this->getNode()->triggerEvent('onInit', [3,7]);

        $this->assertEquals(21, ValidPluginClass2::$value);


        $this->getNode()->forgePlugin(['className'=>ValidPluginClass::class],'log1');
        $this->getNode()->getPlugin('log1')->setContainer($this->getNode());
        $this->getNode()->addEvent('onInit', 'plugin:log1');

        $this->getNode()->triggerEvent('onInit', [2,5]);

        $this->assertEquals(210, ValidPluginClass2::$value);

    }


    /**
     * Add multiple actions to same event;
     * Try to run
     */
    public function testAddMultipleEvents() {

        $this->node = null;

        $this->getNode()->forgePlugin(['className'=>ValidPluginClass::class],'log1');
        $this->getNode()->getPlugin('log1')->setContainer($this->getNode());

        $this->getNode()->forgePlugin(['className'=>ValidPluginClass2::class],'log2');
        $this->getNode()->getPlugin('log2')->setContainer($this->getNode());

        $this->getNode()->addEvent('onInit', 'plugin:log1');
        $this->getNode()->addEvent('onInit', 'plugin:log2');
        $this->getNode()->addEvent('onStop', 'plugin:log2');

        $this->assertCount(2, $this->getNode()->getEvents());

//        print_r($this->getNode()->getEvents());

        $lsEventsInit = $this->getNode()->getEvent('onInit');
        $this->assertCount(2, $lsEventsInit);
    }


    public function testRemoveEvent() {
        $this->node = null;

        $this->getNode()->forgePlugin(['className'=>ValidPluginClass::class],'log1');
        $this->getNode()->getPlugin('log1')->setContainer($this->getNode());

        $this->getNode()->forgePlugin(['className'=>ValidPluginClass2::class],'log2');
        $this->getNode()->getPlugin('log2')->setContainer($this->getNode());

        $this->getNode()->addEvent('onInit', 'plugin:log1');
        $this->getNode()->addEvent('onInit', 'plugin:log2');

        $this->assertCount(1, $this->getNode()->getEvents());

//        print_r($this->getNode()->getEvents());

        $lsEventsInit = $this->getNode()->getEvent('onInit');
        $this->assertCount(2, $lsEventsInit);


        $this->getNode()->removeEvent('onInit', 'plugin:log2');

        $lsEventsInit2 = $this->getNode()->getEvent('onInit');
        $this->assertCount(1, $lsEventsInit2);

    }



}