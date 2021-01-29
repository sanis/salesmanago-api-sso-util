<?php


namespace Tests\Exception;

use PHPUnit\Framework\TestCase;
use Faker;

use SALESmanago\Exception\Exception;

class ExceptionTest extends TestCase
{
    public function testGetLogMessage()
    {
        try {
            throw new Exception('This is log test massage');
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $viewMessage = $exception->getViewMessage();
        }

        $this->assertEquals('This is log test massage', $message);
        $this->assertEquals('This is log test massage', $viewMessage);
    }

    public function testGetViewMessage()
    {
        try {
            throw new Exception('This is view test massage');
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $viewMessage = $exception->getViewMessage();
        }

        $this->assertEquals('This is view test massage', $message);
        $this->assertEquals('This is view test massage', $viewMessage);
    }

    public function testGetConsoleMessage()
    {
        try {
            throw new Exception('This is Console test massage');
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $viewMessage = $exception->getViewMessage();
        }

        $this->assertEquals('This is view Console massage', $message);
        $this->assertEquals('This is view Console massage', $viewMessage);
    }
}