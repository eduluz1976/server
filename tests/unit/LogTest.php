<?php

namespace eduluz1976\server;

use eduluz1976\server\exception\LogException;
use PHPUnit\Framework\TestCase;

class LogTest extends TestCase
{
    public function testLogFolderNotExistent()
    {
        $node = new Node();
        $this->expectExceptionCode(LogException::EXCEPTION_INVALID_PATH);
        $node->initLogger('./invalid_log_folder/app.log');

        $node->getLogger()->debug('Test');
    }

    public function testLogFolderExistentButWriteProtected()
    {
        $node = new Node();
        $this->expectExceptionCode(LogException::EXCEPTION_PATH_WRITE_ONLY);
        $node->initLogger('./testbase/read_only_log/app.log');
    }

    public function testLogFileExistentButWriteProtected()
    {
        $node = new Node();
        $this->expectExceptionCode(LogException::EXCEPTION_PATH_WRITE_ONLY);
        $node->initLogger('./testbase/write_log/app.log');
    }

    public function testLogOk()
    {
        $path = './testbase/log/app.log';
        $initialContents = 'test';

        if (file_exists($path)) {
            unlink($path);
        }

        $node = new Node();
        $node->initLogger($path);

        $node->getLogger()->debug($initialContents);

        $contents = file_get_contents($path);

        $this->assertGreaterThan(0, strpos($contents, 'node.DEBUG'));
        $this->assertGreaterThan(0, strpos($contents, $initialContents));
    }
}
