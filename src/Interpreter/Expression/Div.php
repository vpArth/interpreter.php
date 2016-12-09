<?php

namespace Arth\Utils\Interpreter\Expression;

use Arth\Utils\Interpreter\IContext;

class Div extends Operator
{
    protected function doInterpret(IContext $ctx, $l, $r)
    {
        if ($r == 0) {
            throw new DivisionByZero('Деление на 0');
        }
        $ctx->set($this, $l / $r);
    }
    public function __toString() {return "({$this->l} / {$this->r})"; }
}
