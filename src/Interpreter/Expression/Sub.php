<?php

namespace Arth\Utils\Interpreter\Expression;

use Arth\Utils\Interpreter\IContext;

class Sub extends Operator
{
    protected function doInterpret(IContext $ctx, $l, $r)
    {
        $ctx->set($this, $l - $r);
    }
    public function __toString() {return "({$this->l} - {$this->r})"; }
}
