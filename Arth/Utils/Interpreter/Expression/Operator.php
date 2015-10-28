<?php

namespace Arth\Utils\Interpreter\Expression;

use Arth\Utils\Interpreter\Expression;
use Arth\Utils\Interpreter\IContext;

abstract class Operator extends Expression
{
    protected $l, $r;

    public function __construct(Expression $l, Expression $r)
    {
        $this->l = $l;
        $this->r = $r;
    }

    public function interpret(IContext $ctx)
    {
        $this->l->interpret($ctx);
        $this->r->interpret($ctx);
        $l = $ctx->get($this->l);
        $r = $ctx->get($this->r);

        $this->doInterpret($ctx, $l, $r);
    }
    abstract protected function doInterpret(IContext $ctx, $l, $r);
}
