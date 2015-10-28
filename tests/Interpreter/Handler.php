<?php

namespace tests\Interpreter;

use Arth\Utils\Interpreter as I;
use Arth\Utils\Interpreter\Handler as H;
use Arth\Utils\Interpreter\Parser as P;
use Arth\Utils\Interpreter\Scanner as S;
use Arth\Utils\Interpreter\Expression as E;

require_once __DIR__ . "/../../loader.php";

class Handler extends \PHPUnit_Framework_TestCase
{

    private static function scanner($str)
    {
        // echo "\n$str\n";
        return new I\Scanner(new I\StringReader($str), new I\Context);
    }

    protected function setUp()
    {
    }

    public function testValue()
    {
        $p = new P\Number;
        $s = self::scanner('42');
        $h = new H\Value;
        $ctx = new I\IContext;

        $p->setHandler($h);
        $p->scan($s);
        $result = $s->getContext()->pop();
        $this->assertTrue($result instanceof E);
        $result->interpret($ctx);
        $this->assertEquals(42, $ctx->get($result));
    }

    protected function evaluate($parser, $expression)
    {
        $s = self::scanner($expression);

        $parser->scan($s);
        $res = $s->getContext()->pop();
        // var_dump($res);
        $this->assertTrue($res instanceof E);
        $ctx = new I\IContext;
        $res->interpret($ctx);
        return $ctx->get($res);
    }


    /**
     * @dataProvider signedProvider
     */
    public function testSigned($expression, $expected)
    {
        $p = new P\Sequence;
        $p->add(new P\Repeat(new P\CharClass(array('+', '-')), null, 1));
        $p->add(foo(new P\Number)->setHandler(new H\Value));
        $p->setHandler(new H\Signed);

        $this->assertEquals($expected, $this->evaluate($p, $expression));
    }

    public function signedProvider()
    {
        return array(
            array('1734', '1734'),
            array('+1734', '1734'),
            array('-1734', '-1734'),
        );
    }

    /**
     * @dataProvider operatorProvider
     */
    public function testOperator($expression, $expected)
    {
        $sum = new P\Sequence;
        $sum->add(foo(new P\Number)->setHandler(new H\Value));
        $sum->add(new P\CharClass(array('+', '-', '*', '/', '^')));
        $sum->add(foo(new P\Number)->setHandler(new H\Value));
        $sum->setHandler(new H\Operator);

        $this->assertEquals($expected, $this->evaluate($sum, $expression));
    }

    public function operatorProvider()
    {
        return array(
            array('7-5', 2),
            array('7+5', 12),
            array('7*5', 35),
            array('7/2', 3.5),
            array('7^2', 49),
        );
    }


    protected function tearDown()
    {
    }
}
