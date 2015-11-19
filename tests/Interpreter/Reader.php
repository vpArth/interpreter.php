<?php

namespace tests\Interpreter;

use Arth\Utils\Interpreter as I;

class Reader extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    public function testMain()
    {
        $s = 'Hello, world!';
        $sr = new I\StringReader($s);

        $this->assertEquals($s, $sr);
        $this->assertEquals(0, $sr->pos());
        $this->assertEquals('H', $sr->read());
        $this->assertEquals('e', $sr->read());
        $this->assertEquals(2, $sr->pos());
        $sr->back();
        $this->assertEquals(1, $sr->pos());
        $this->assertEquals('e', $sr->read());
    }

    public function testUtf8()
    {
        $s = 'Привет';
        $sr = new I\StringReader($s);

        $this->assertEquals($s, $sr);
        $this->assertEquals(0, $sr->pos());
        $this->assertEquals('П', $sr->read());
        $this->assertEquals('р', $sr->read());
        $this->assertEquals(2, $sr->pos());
        $sr->back();
        $this->assertEquals(1, $sr->pos());
        $this->assertEquals('р', $sr->read());
    }

    protected function tearDown()
    {
    }
}
