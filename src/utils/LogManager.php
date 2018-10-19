<?php
namespace eduluz1976\server\utils;


use eduluz1976\server\exception\LogException;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

trait LogManager
{

    protected $logger;


    public function getAppName() {
        return 'node';
    }

    /**
     * @param string $path
     * @param int $level
     */
    public function initLogger($path="./log/app.log", $level=Logger::DEBUG) {
        $this->logger = new Logger($this->getAppName());

        $dir = dirname($path);
        if (!file_exists($dir)) {
            throw new LogException("Path $dir not found", LogException::EXCEPTION_INVALID_PATH);
        } elseif (!is_writable($dir)) {
            throw new LogException("Path $dir is write protected", LogException::EXCEPTION_PATH_WRITE_ONLY);
        } elseif (file_exists($path)  && !is_writable($path)) {
            throw new LogException("Path $path is write protected", LogException::EXCEPTION_PATH_WRITE_ONLY);
        }

        $this->logger->pushHandler(new StreamHandler($path , $level));

    }


    /**
     * @return Logger
     * @throws LogException
     */
    public function getLogger() {
        if (!$this->logger) {
            $this->initLogger();
        }
        return $this->logger;
    }


}