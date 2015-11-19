<?php

namespace tests\Interpreter;

use Arth\Utils\Interpreter as I;
use Arth\Utils\Interpreter\Expression as E;

class Expression extends \PHPUnit_Framework_TestCase
{
    protected $ctx;
    protected function setUp()
    {
        $this->ctx = new I\IContext;
    }

    public function testValue()
    {
        $value = 13.2;
        $e = new E\Value($value);
        $e->interpret($this->ctx);
        $this->assertEquals($value, $this->ctx->get($e));

        $value = 'Hello, world';
        $e = new E\Literal($value);
        $e->interpret($this->ctx);
        $this->assertEquals($value, $this->ctx->get($e));
    }

    public function testOperator()
    {
        $l = 4;
        $r = 3;
        $lE = new E\Value($l);
        $rE = new E\Value($r);
        //Add
        $op = new E\Add($lE, $rE);
        $op->interpret($this->ctx);
        $this->assertEquals($l + $r, $this->ctx->get($op));
        //Sub
        $op = new E\Sub($lE, $rE);
        $op->interpret($this->ctx);
        $this->assertEquals($l - $r, $this->ctx->get($op));
        //Mul
        $op = new E\Mul($lE, $rE);
        $op->interpret($this->ctx);
        $this->assertEquals($l * $r, $this->ctx->get($op));
        //Div
        $op = new E\Div($lE, $rE);
        $op->interpret($this->ctx);
        $this->assertEquals($l / $r, $this->ctx->get($op));
        //Pow
        $op = new E\Pow($lE, $rE);
        $op->interpret($this->ctx);
        $this->assertEquals(pow($l, $r), $this->ctx->get($op));
    }

    public function testToString()
    {
        $l = '4';
        $r = '3';
        $lE = new E\Value($l);
        $rE = new E\Value($r);

        $this->assertEquals('5', ''.new E\Value('5'));
        $this->assertEquals('(4 + 3)', ''.new E\Add($lE, $rE));
        $this->assertEquals('(4 - 3)', ''.new E\Sub($lE, $rE));
        $this->assertEquals('(4 * 3)', ''.new E\Mul($lE, $rE));
        $this->assertEquals('(4 / 3)', ''.new E\Div($lE, $rE));
        $this->assertEquals('(4 ^ 3)', ''.new E\Pow($lE, $rE));
        $this->assertEquals("'Hello, world!'", ''.new E\Literal('Hello, world!'));
        $this->assertEquals("sum(4, 3)", ''.new E\Func('sum', array($lE, $rE)));
        $this->assertEquals("-4", ''.new E\Neg($lE));
    }

    protected function tearDown()
    {
    }
}
