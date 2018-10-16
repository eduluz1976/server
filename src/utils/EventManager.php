<?php
namespace eduluz1976\server\utils;


use eduluz1976\server\base\EventBase;
use eduluz1976\server\exception\EventException;

trait EventManager
{


    protected $lsEvents = [];


    /**
     * @param string $triggerEvent
     * @param string|mixed $action
     * @param mixed ...$parameters
     */
    public function addEvent($triggerEvent, $action, ...$parameters) {
        if (!isset($this->lsEvents[$triggerEvent])) {
            $this->lsEvents[$triggerEvent] = [];
        }
        $event = self::generateEvent($this, $action, $parameters);

        $this->lsEvents[$triggerEvent][] = $event;
    }




    /**
     * @return array
     */
    public function getEvents() {
        return $this->lsEvents;
    }


    /**
     * @param string $eventName
     * @return mixed
     */
    public function getEvent($eventName) {
        if (isset($this->lsEvents[$eventName])) {
            return $this->lsEvents[$eventName];
        }

    }



    /**
     * @param Node $container
     * @param mixed $action
     * @param array $parameters
     * @return EventBase
     */
    public static function generateEvent($container,$action, $parameters=[]) {

        if (is_string($action)) {

            if (substr($action,0,7)=='plugin:') {

                $pluginName = substr($action, 7);
                $plugin = $container->getPlugin($pluginName);

                if (is_subclass_of($plugin,\eduluz1976\server\base\RunnableBase::class)) {

                    $plugin->setCode($pluginName);

                    return $plugin;
                }
            }

        }

        throw new EventException("Generator not found", EventException::EXCEPTION_UNKNOWN_GENERATOR);
    }


    /**
     * @param string $eventName
     * @param array $parameters
     */
    public function triggerEvent($eventName, $parameters=[]) {

        $lsEvents = $this->getEvent($eventName);

        if (!$lsEvents) {
            return;
        }

        foreach ($lsEvents as $code => $event) {

            try {
                $event->exec($code, $parameters);
            } catch (\Exception $ex) {
                // add the logger here
            }

        }

    }


    public function removeEvent($eventName, $action) {

        if (!isset($this->lsEvents[$eventName])) {
            return false;
        }


        if (is_string($action)) {

            if (substr($action,0,7)=='plugin:') {
                $key = substr($action, 7);
            }

        }

        if ($key) {
            foreach ($this->lsEvents[$eventName] as $i => $event) {
                if ($event->getCode() === $key) {
                    unset($this->lsEvents[$eventName][$i]);
                    return;
                }
            }
        }

    }


}