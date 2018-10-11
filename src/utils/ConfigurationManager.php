<?php

namespace eduluz1976\server\utils;


use \eduluz1976\server\exception;

/**
 * Trait ConfigurationManager
 * @package eduluz1976\server\utils
 */
trait ConfigurationManager
{

    /**
     * @var string
     */
    protected $configContext=false;

    /**
     * @var array
     */
    protected $configContents = [];


    /**
     * @param string $context
     */
    public function setConfigContext($context) {
        $this->configContext = $context;
    }


    /**
     * @throws exception\ConfigException
     */
    protected function validateConfigContext() {
        if (!isset($this->configContents[$this->configContext]) ||  !$this->configContents[$this->configContext]) {
            throw new exception\ConfigException("Configuration context " . $this->configContext." not found", exception\ConfigException::EXCEPTION_INVALID_CONTEXT);
        }
    }


    /**
     * @param string $key
     * @param bool $default
     * @return mixed
     * @throws exception\ConfigException
     */
    public function getConfig($key, $default=false) {

        if (!$this->configContext) {
            $context = key($this->configContents);
            $this->setConfigContext($context);
        }

        $this->validateConfigContext();

        if (isset($this->configContents[$this->configContext][$key])) {
            return $this->configContents[$this->configContext][$key];
        } else {
            return $default;
        }
    }


    /**
     * @return array
     * @throws exception\ConfigException
     */
    public function getAllConfig() {
        $this->validateConfigContext();
        return $this->configContents[$this->configContext];
    }


    /**
     * @param string $contents
     * @return array
     * @throws exception\ConfigException
     */
    public function setAllConfig($contents) {
        $json = json_decode($contents, true);

        if (!is_array($json)) {
            throw new exception\ConfigException("Invalid contents on configuration .", exception\ConfigException::EXCEPTION_INVALID_CONTENTS);
        }

        $this->configContents = $json;
        return $json;
    }


    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     * @throws exception\ConfigException
     */
    public function setConfig($key, $value) {
        $this->validateConfigContext();

        $this->configContents[$this->configContext][$key] = $value;

        return $this;
    }


    /**
     * @param string $configFile
     * @param bool $mergeCurrent
     * @throws exception\ConfigException
     */
    public function loadConfiguration($configFile, $mergeCurrent=false) {

        $config = $this->getConfigFileContents($configFile);

        if ($mergeCurrent) {
            $this->configContents = array_replace($this->configContents, $config);
        } else {
            $this->configContents = $config;
        }

    }


    /**
     * @param string $configFile
     * @return array
     * @throws exception\ConfigException
     */
    public function getConfigFileContents($configFile) {
        if (!file_exists($configFile)) {
            throw new exception\ConfigException("File $configFile does not exists", exception\ConfigException::EXCEPTION_FILE_DOES_NOT_EXISTS);
        }

        $contents = file_get_contents($configFile);
        $json = $this->setAllConfig($contents);

        return $json;

    }



}