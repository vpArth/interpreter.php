<?php

namespace tests\Interpreter;

use Arth\Utils\Interpreter as I;

require_once __DIR__ . "/../../loader.php";

class Scanner extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    public function testMain()
    {
        $scan = new I\Scanner(new I\StringReader("Hello, мир\n\t'152'"), $ctx = new I\Context);

        $this->assertSame($ctx, $scan->getContext());
        $scan->next(); // WORD
        $this->assertSame('Hello', $scan->token());
        $this->assertSame(I\Scanner::WORD, $scan->type());
        $this->assertSame(1, $scan->line());
        $this->assertSame(6, $scan->char());

        $scan->next(); // CHAR <,>
        $this->assertSame(',', $scan->token());
        $this->assertSame(I\Scanner::CHAR, $scan->type());

        $scan->next(); // WHITESPACE #32
        $this->assertSame(' ', $scan->token());
        $this->assertSame(I\Scanner::WHITESPACE, $scan->type());

        $scan->next(); // world
        $this->assertSame('мир', $scan->token());
        $this->assertSame(I\Scanner::WORD, $scan->type());
        $this->assertSame(1, $scan->line());
        $this->assertSame(11, $scan->char());

        $scan->next(); // EOL
        $this->assertSame(I\Scanner::EOL, $scan->type());
        $this->assertSame(2, $scan->line());
        $scan->next(); // <tab>
        $scan->next(); // <APOS>
        $this->assertSame(I\Scanner::APOS, $scan->type());
        $scan->next(); // number
        $this->assertSame('152', $scan->token());
        $this->assertSame(I\Scanner::NUMBER, $scan->type());
        $scan->next(); // <APOS>
        $scan->next(); // <EOF>
        $this->assertNull($scan->token());
        $this->assertSame(I\Scanner::EOF, $scan->type());
    }

    public function testState()
    {
        $scan = new I\Scanner(new I\StringReader("1+2"), $ctx = new I\Context);
        $start = $scan->getState();
        $scan->next()->next()->next()->next();
        $this->assertSame(I\Scanner::EOF, $scan->type());
        $scan->setState($start);
        $this->assertSame('1', $scan->next()->token());
        $this->assertEquals(array(I\Scanner::CHAR, '+'), $scan->peek());
        $this->assertEquals(array(I\Scanner::CHAR, '+'), $scan->peek());
    }

    public function testScan()
    {
        $scan = new I\Scanner(new I\StringReader("1+2"), $ctx = new I\Context);
        $scan->next(); $this->assertSame('1', $scan->token());
        $scan->next(); $this->assertSame('+', $scan->token());
        $scan->next(); $this->assertSame('2', $scan->token());
    }

    protected function tearDown()
    {
    }
}
