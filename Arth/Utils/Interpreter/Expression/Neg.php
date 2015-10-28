<?php

namespace Arth\Utils\Interpreter\Expression;

use Arth\Utils\Interpreter\Expression;
use Arth\Utils\Interpreter\IContext;

class Neg extends Expression
{
    private $value;

    function __construct($value)
    {
        $this->value = $value;
    }

    function interpret(IContext $context)
    {
        $this->value->interpret($context);
        $value = $context->get($this->value);
        $context->set( $this, -$value );
    }
    public function __toString() {return '-'.$this->value; }
}
