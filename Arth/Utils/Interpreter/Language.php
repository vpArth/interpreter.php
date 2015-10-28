<?php

namespace Arth\Utils\Interpreter;

abstract class Language
{
    public function __construct($statement)
    {
        $this->compile($statement);
    }

    abstract public function compile($statement);
    abstract public function evaluate(IContext $context = null);
}
