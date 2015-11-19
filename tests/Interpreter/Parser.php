<?php

namespace tests\Interpreter;

use Arth\Utils\Interpreter as I;

class Parser extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }
    private static function scanner($str)
    {
        // echo "\n$str\n";
        return new I\Scanner(new I\StringReader($str), new I\Context);
    }

    private function interpret(I\Expression $e)
    {
        $context = new I\IContext;
        $e->interpret($context);
        return $context->get($e);
    }

    // Parsers coverage
    // + Char
    // + CharClass
    // + Word
    // + Literal
    // + Number
    // + Float
    // + Sequence
    // + Repeat



    public function testChar()
    {
        $char = '>';
        $cnt = 7;
        $scan = self::scanner(str_repeat($char, $cnt)."!");
        $p = new I\Parser\Char($char);
        for ($i = 0; $i<$cnt; $i++)
            $this->assertTrue($p->scan($scan));
        $this->assertTrue((new I\Parser\Char)->scan($scan));
        $ctx = $scan->getContext();
        $this->assertEquals($cnt+1, $ctx->size());
        $this->assertEquals('!', $ctx->pop());
        for ($i = 0; $i<$cnt; $i++)
            $this->assertEquals($char, $ctx->pop());
    }
    public function testCharClass()
    {
        $chars = array(',', ';');
        $scan = self::scanner('1, 2; 3, 4;');
        $p = new I\Parser\Sequence;
        $p->add(new I\Parser\Number);

        $tail = new I\Parser\Sequence;
        $tail->add((new I\Parser\CharClass($chars))->discard());
        $tail->add(new I\Parser\Number);
        $p->add(new I\Parser\Repeat($tail));

        $ctx = $scan->getContext();
        $this->assertTrue($p->scan($scan));
        $this->assertEquals(4, $ctx->pop());
        $this->assertEquals(3, $ctx->pop());
        $this->assertEquals(2, $ctx->pop());
        $this->assertEquals(1, $ctx->pop());
    }
    public function testWord()
    {
        $scan = self::scanner('Lorem ipsum');
        $ctx = $scan->getContext();
        $this->assertTrue((new I\Parser\Word)->scan($scan));
        $this->assertEquals('Lorem', $ctx->pop());
        $this->assertTrue((new I\Parser\Word('ipsum'))->scan($scan));
        $this->assertEquals('ipsum', $ctx->pop());
    }

    public function testWords()
    {
        $words = array('Стул', 'Дверь', 'Door');
        $scan = self::scanner('Стул Door');
        $p = new I\Parser\Repeat(new I\Parser\Words($words));

        $ctx = $scan->getContext();
        $this->assertTrue($p->scan($scan));
        $this->assertEquals('Door', $ctx->pop());
        $this->assertEquals('Стул', $ctx->pop());
    }

    public function testLiteral()
    {
        $rep = new I\Parser\Repeat(new I\Parser\Literal());
        $str = implode(' ', array(
            '\'Hello, "world"!\'',
            '"\'"',
            "'\"'",
            '" \\\\ \\" "',
            "'<\\'\\'\\'>'"
        ));
        $scan = self::scanner($str);
        $ctx = $scan->getContext();

        $this->assertTrue($rep->scan($scan));
        $this->assertEquals("<'''>", $ctx->pop());
        $this->assertEquals(' \\ " ', $ctx->pop());
        $this->assertEquals('"', $ctx->pop());
        $this->assertEquals('\'', $ctx->pop());
        $this->assertEquals('Hello, "world"!', $ctx->pop());
    }

    public function testNumber()
    {
        $scan = self::scanner('16 144');
        $ctx = $scan->getContext();
        $this->assertTrue((new I\Parser\Number)->scan($scan));
        $this->assertEquals('16', $ctx->pop());
        $this->assertTrue((new I\Parser\Number(144))->scan($scan));
        $this->assertEquals('144', $ctx->pop());
    }

    public function testFloat()
    {
        $scan = self::scanner('98+14.2 0.3');
        $p = new I\Parser\Sequence;
        $p->add(new I\Parser\FloatVal('98'));
        $p->add(new I\Parser\Char('+'));
        $p->add(new I\Parser\FloatVal);
        $p->add(new I\Parser\FloatVal);

        $this->assertTrue($p->scan($scan));

        $ctx = $scan->getContext();
        // $this->assertEquals('0.000003', $ctx->peek());
        $this->assertEquals('0.3', $ctx->pop());
        // $this->assertEquals('+', $ctx->pop());
        $this->assertEquals(14.2, $ctx->pop());
        $this->assertEquals('+', $ctx->pop());
        $this->assertEquals(98, $ctx->pop());

    }

    public function testSigned()
    {
        $p = new I\Parser\Signed((new I\Parser\Number)->setHandler(new I\Handler\Value));
        $context = new I\IContext;

        foreach (array('-15', '+14') as $t) {
            $scan = self::scanner($t);
            $ctx = $scan->getContext();
            $this->assertTrue($p->scan($scan));

            $this->assertEquals(1, $ctx->size());
            $this->assertEquals($t, $this->interpret($ctx->pop()));
        }
    }

    public function testSequence()
    {
        $p = new I\Parser\Sequence;
        $p->add(new I\Parser\Word);
        $p->add(new I\Parser\Char);

        $scan = self::scanner('World!');
        $ctx = $scan->getContext();

        $this->assertTrue($p->scan($scan));
        $this->assertEquals('!', $ctx->pop());
        $this->assertEquals('World', $ctx->pop());
    }

    public function testAlternative()
    {
        $p = new I\Parser\Alternative;
        $p->add(new I\Parser\Word);
        $p->add(new I\Parser\Number);

        $scan = self::scanner('Ответ 42');
        $ctx = $scan->getContext();

        $this->assertTrue($p->scan($scan));
        $this->assertEquals(1, $ctx->size());
        $this->assertEquals('Ответ', $ctx->pop());
        $this->assertTrue($p->scan($scan));
        $this->assertEquals('42', $ctx->pop());
    }

    public function testRepeat()
    {
        $rep = new I\Parser\Repeat(new I\Parser\Word('token'));
        $scan = self::scanner('token token token token token');
        $ctx = $scan->getContext();

        $this->assertTrue($rep->scan($scan));
        $this->assertEquals(5, $ctx->size());
    }

    public function testRepeatMin()
    {
        $rep3 = new I\Parser\Repeat(new I\Parser\Word('token'), 3);
        $rep6 = new I\Parser\Repeat(new I\Parser\Word('token'), 6);

        $scan = self::scanner('token token token token token');
        $scanF = self::scanner('abracadabra');
        $ctx = $scan->getContext();

        $this->assertFalse($rep6->scan($scan));
        $this->assertFalse($rep3->scan($scanF));
        $this->assertTrue($rep3->scan($scan));
        $this->assertEquals(5, $ctx->size());
    }

    public function testRepeatMax()
    {
        $rep = new I\Parser\Repeat(new I\Parser\Word('token'), null, 3);
        $scan = self::scanner('token token token token token');
        $ctx = $scan->getContext();

        $this->assertTrue($rep->scan($scan));
        $this->assertEquals(3, $ctx->size());

        $scan = self::scanner('token token');
        $ctx = $scan->getContext();

        $this->assertTrue($rep->scan($scan));
        $this->assertEquals(2, $ctx->size());
    }

    public function testMayBe()
    {
        $p = new I\Parser\MayBe(new I\Parser\Word('token'));

        $scan = self::scanner('notokenhere');
        $ctx = $scan->getContext();
        $this->assertTrue($p->scan($scan));
        $this->assertEquals(0, $ctx->size());

        $scan = self::scanner('token is presented');
        $ctx = $scan->getContext();
        $this->assertTrue($p->scan($scan));
        $this->assertEquals(1, $ctx->size());

        $p = new I\Parser\Sequence;
        $p->add(new I\Parser\MayBe(new I\Parser\CharClass(array('-', '+'))));
        $p->add(new I\Parser\Number);

        $scan = self::scanner('-5 +7 18');
        $ctx = $scan->getContext();
        $this->assertTrue($p->scan($scan));
        $this->assertEquals('5', $ctx->pop());
        $this->assertEquals('-', $ctx->pop());
        $this->assertTrue($p->scan($scan));
        $this->assertEquals('7', $ctx->pop());
        $this->assertEquals('+', $ctx->pop());
        $this->assertTrue($p->scan($scan));
        $this->assertEquals('18', $ctx->pop());
    }

    public function testErrors()
    {
        try {
            new I\Parser\Repeat(new I\Parser\Number, 5, 2);
            $this->assertFalse(true, 'Should exception thrown');
        } catch(\Exception $e){}

        $a = new I\Parser\Alternative();
        $a->add(new I\Parser\Repeat(new I\Parser\Word('Точка'), 1));
        $a->add(new I\Parser\Word('Дочка'));
        $this->assertFalse($a->scan(self::scanner('Почка Ночка')));

        $p = new I\Parser\FloatVal('3.14');
        $this->assertTrue($p->scan(self::scanner('3.14')));
        $this->assertFalse($p->scan(self::scanner('3.16')));
        $this->assertFalse($p->scan(self::scanner('3.')));
        $this->assertFalse($p->scan(self::scanner('3')));
        $p = new I\Parser\FloatVal('3');
        $this->assertTrue($p->scan(self::scanner('3')));
        $this->assertFalse($p->scan(self::scanner('2')));
        $this->assertFalse($p->scan(self::scanner('3.14')));
    }

    public function testDebug()
    {
        $p = new I\Parser\Number('42');
        $p->setDebug(true);
        ob_start();
        $p->report('Answer');
        $res = ob_get_contents();
        $p->setDebug(false);
        ob_end_clean();
        $this->assertEquals("<Arth\Utils\Interpreter\Parser\Number[{$p->id}]>: Answer\n", $res);
    }

    protected function tearDown()
    {
    }
}
