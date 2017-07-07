<?php

namespace RabbitMQ\Test\TestCase\Helper;

use RabbitMQ\Helper\MeaningfulTime;
use Cake\TestSuite\TestCase;

class MeaningfulTimeTest extends TestCase
{
    public function testSimpleParseMillisecond()
    {
        $m = new MeaningfulTime();
        $this->assertEquals('30 ms', $m(30, 'ms'));
    }

    public function testSimpleParseSecond()
    {
        $m = new MeaningfulTime();
        $this->assertEquals('30 s', $m(30, 's'));
    }

    public function testSimpleParseMinute()
    {
        $m = new MeaningfulTime();
        $this->assertEquals('1 min', $m(1, 'min'));
    }

    public function testSimpleParseMinutes()
    {
        $m = new MeaningfulTime();
        $this->assertEquals('2 mins', $m(2, 'min'));
    }

    public function testParseMillisecondToSecond()
    {
        $m = new MeaningfulTime();
        $this->assertEquals('30 s', $m(30 * 1000, 'ms'));
    }

    public function testParseMillisecondToMinutes()
    {
        $m = new MeaningfulTime();
        $this->assertEquals('30 mins', $m(30 * 60 * 1000, 'ms'));
    }

    public function testParseMillisecondToHours()
    {
        $m = new MeaningfulTime();
        $this->assertEquals('30 hrs', $m(30 * 60 * 60 * 1000, 'ms'));
    }

    public function testParseSecondToHourAndMinutes()
    {
        $m = new MeaningfulTime();
        $this->assertEquals('1 hr, 30 mins', $m((60 * 60) + (30 * 60), 's'));
    }

    public function testParseSecondToHours()
    {
        $m = new MeaningfulTime();
        $this->assertEquals('2 hrs', $m(2 * 60 * 60, 's'));
    }

    public function testParseMinutesToHoursAndMinute()
    {
        $m = new MeaningfulTime();
        $this->assertEquals('30 hrs, 1 min', $m(30 * 60 + 1, 'min'));
    }

    public function testParseMillisecondToUserFriendlyTime()
    {
        $m = new MeaningfulTime();
        $this->assertEquals(
            '1 hr, 12 mins, 3 s, 300 ms', 
            $m(
                1 * 60 * 60 * 1000 +
                12 * 60 * 1000 +
                3 * 1000 +
                300, 
                'ms'
            )
        );
    }

    public function testInvaildInput()
    {
        $m = new MeaningfulTime();
        $this->expectException(\InvalidArgumentException::class);
        $m(0, 'hr');
    }
}