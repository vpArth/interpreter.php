<?php

namespace Arth\Utils\Interpreter;

use Arth\Utils\Interpreter\Parser as P;
use Arth\Utils\Interpreter\Handler as H;

class FL extends Language
{
    public function compile($str)
    {
        $scan = new Scanner(new StringReader($str), new Context);
        $statement = $this->expression();
        $scanres = $statement->scan($scan);
        if (!$scanres || $scan->type() != Scanner::EOF)
        {
            $msg = "Parse Error:";
            $msg.=" line: {$scan->line()} ";
            $msg.=" char: {$scan->char()} ";
            $msg.=" token: \"{$scan->token()}\"";
            throw new \Exception($msg);
        }

        // $scan->getContext()->p();
        $this->interpreter = $scan->getContext()->pop();

    }

    protected $interpreter;
    public function evaluate(IContext $context = null)
    {
        if (is_null($context)) {
            $context = new IContext;
        }
        $this->interpreter->interpret($context);
        return $context->get($this->interpreter);
    }

    protected $expression;
    protected function expression()
    {
        if (!$this->expression) {
            $this->expression = new P\Sequence; // recursion lock
            $p = new P\Alternative();
            $p->add($this->floatVal());
            $p->add($this->parenth());
            $p->add($this->func());
            $m = $this->power($p);

            $a = new P\Alternative();
            $a->add($m);
            $a->add(new P\Signed($m));

            $term = $this->mul($a);
            $expr = $this->sum($term);

            $this->expression->add($expr);
        }
        return $this->expression;
    }
    protected function floatVal()
    {
        return foo(new P\FloatVal)->setHandler(new H\Value);
    }
    protected function parenth()
    {
        $p = new P\Sequence();
        $p->add(foo(new P\Char('('))->discard());
        $p->add($this->expression());
        $p->add(foo(new P\Char(')'))->discard());
        return $p;
    }
    protected function func()
    {
        $p = new P\Sequence();
        $p->add(new P\Word);
        $p->add(new P\Char('('));
        $p->add($this->argList());
        $p->add(new P\Char(')'));
        return $p->setHandler(new H\Func);
    }
    protected function argList()
    {
        $tail = new P\Sequence;
        $tail->add(foo(new P\Char(','))->discard());
        $tail->add($this->expression());

        $p = new P\Sequence;
        $p->add($this->expression());
        $p->add(new P\Repeat($tail));
        return new P\MayBe($p);
    }
    protected function power(Parser $t)
    {
        $tail = new P\Sequence;
        $tail->add(new P\Char('^'));
        $a = new P\Alternative;
        $a->add($t);
        $a->add(new P\Signed($t));
        $tail->add($a);
        $tail->setHandler(new H\Operator);

        $p = new P\Sequence;
        $p->add($t);
        $p->add(new P\Repeat($tail));
        return $p;
    }

    protected function mul(Parser $t)
    {
        $tail = new P\Sequence;
        $tail->add(new P\CharClass(array('*', '/')));
        $tail->add($t);
        $tail->setHandler(new H\Operator);

        $p = new P\Sequence;
        $p->add($t);
        $p->add(new P\Repeat($tail));
        return $p;
    }

    protected function sum(Parser $t)
    {
        $tail = new P\Sequence;
        $tail->add(new P\CharClass(array('+', '-')));
        $tail->add($t);
        $tail->setHandler(new H\Operator);

        $p = new P\Sequence;
        $p->add($t);
        $p->add(new P\Repeat($tail));
        return $p;
    }
}
