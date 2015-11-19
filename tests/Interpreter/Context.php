<?php

namespace tests\Interpreter;

use Arth\Utils\Interpreter\Context as Ctx;

class Context extends \PHPUnit_Framework_TestCase
{
    protected $ctx;
    protected function setUp()
    {
        $this->ctx = new Ctx;
    }

    public function testStack()
    {
        $this->ctx->push(1);
        $this->ctx->push(2);
        $this->ctx->push(3);
        $this->ctx->push(4);
        $this->assertEquals(4, $this->ctx->size());
        $this->assertEquals(4, $this->ctx->peek());
        $this->assertEquals(4, $this->ctx->peek());
        $this->assertEquals(4, $this->ctx->pop());
        $this->assertEquals(3, $this->ctx->size());
        $this->assertEquals(3, $this->ctx->size());
    }

    protected function tearDown()
    {
    }
}
