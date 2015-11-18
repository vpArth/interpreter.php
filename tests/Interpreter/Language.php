<?php

namespace tests\Interpreter;

use Arth\Utils\Interpreter as I;
use Arth\Utils\Interpreter\Expression\DivisionByZero as DivisionByZero;
require_once __DIR__ . "/../../loader.php";

class Language extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        I\Expression\Func::register('twice', function($a){return 2*$a;});
        I\Expression\Func::register('PI', function(){return pi();});
        I\Expression\Func::register('sin', function($a){return sin($a);});
        I\Expression\Func::register('cos', function($a){return cos($a);});
        I\Expression\Func::register('sum', function() {
            return array_sum(func_get_args());
        });
    }

    /**
     * @dataProvider flProvider
     */
    public function testFL($expression, $result)
    {
        $fl = new I\FL($expression);
        $this->assertEquals($fl->evaluate(), $result);
    }

    public function testFunc()
    {
        $fl = new I\FL('twice(3.14)');
        $this->assertEquals($fl->evaluate(), '6.28');

        $fl = new I\FL('PI()');
        $this->assertEquals($fl->evaluate(), pi());

        $fl = new I\FL('sum(cos(0), 2, 3, twice(2), 5)');
        $this->assertEquals($fl->evaluate(), '15');

        $fl = new I\FL('sum(cos(0), 2, 3, twice(2), 5)');

        $ctx = new I\IContext;
        $ctx->regFunction('sum', function() {
            return implode('', func_get_args());
        });

        $this->assertEquals($fl->evaluate($ctx), '12345');
    }

    public function testError()
    {
        try {new I\FL('(13+7'); $this->assertFalse(true, 'Unpair parentheses');}catch(I\ParseError $e){}
        try {foo(new I\FL('12/0'))->evaluate(); $this->assertFalse(true, 'DivisionByZero');
        } catch (DivisionByZero $e) {
            $this->assertEquals('Деление на 0', $e->getMessage());
        }
    }

    public function flProvider()
    {
        return array(
            array('3.14', '3.14'),
            array('-3.14', '-3.14'),
            array('+3.14', '3.14'),
            array('-(3.14)', '-3.14'),
            array('3+-2', '1'),
            array('3 +2', '5'),
            array('15.7-13.8', '1.9'),
            array('2+3', '5'),
            array('2*3', '6'),
            array('2^3', '8'),
            array('2+2*2', '6'),
            array('-2+2*2', '2'),
            array('(2+2)*2', '8'),
            array('-(2+2)*2', '-8'),
            array('sin(1/3)^2+cos(1/3)^2', '1'),
            array('-sin(1/3)^2+-cos(1/3)^2', '-1'),
            array('-5^2', '-25'),//or better ['-5^2', 25] ?
            array('2^-2', '0.25'),
            array('2^-2+5', '5.25'),
            array('10-+(3.14*2-(0.2+0.08))', '4'),
            array("'test'", 'test'),
            array("const('pi')", M_PI),
            array("const('exp')", M_E),
            array("val('test')", 'test'),
            array("val(12.3+0.2)", '12.5'),
            array("ln(const('exp'))", '1'),
            array("const('exp')^ln(const('pi'))", M_PI),
            array("lg(10000)", 4),
            array("min(5, -10)", -10),
            array("max(6, -10, 7, 6, 7)", 7),
            array("avg(4, 9)", 6),
        );
    }
    protected function tearDown()
    {
    }
}
