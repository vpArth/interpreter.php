<?php

namespace Arth\Utils\Interpreter\Expression;

use Arth\Utils\Interpreter\Expression;
use Arth\Utils\Interpreter\IContext;

class Value extends Expression
{
    protected $value;

    function __construct($value)
    {
        $this->value = $value;
    }

    function interpret(IContext $context)
    {
        $context->set( $this, $this->value );
    }
    public function __toString() {return $this->value; }

}
