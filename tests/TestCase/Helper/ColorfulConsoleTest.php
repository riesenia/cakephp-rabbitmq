<?php
namespace RabbitMQ\Test\TestCase\Helper;

use Cake\TestSuite\TestCase;
use RabbitMQ\Helper\ColorfulConsole;

class ColorfulConsoleTest extends TestCase
{
    public function testVaildState()
    {
        $c = new ColorfulConsole();
        try {
            ob_start();
            $c('default', 'white');
            $c('info', 'cyan');
            $c('success', 'green');
            $c('warning', 'yellow');
            $c('error', 'red');
            ob_end_clean();
        } catch (Exception $e) {
            $this->fail();
        }

        $this->assertTrue(true);
    }

    public function testInvaildState()
    {
        $c = new ColorfulConsole();
        $this->expectException(\InvalidArgumentException::class);
        $c('ex', 'exception');
    }
}
