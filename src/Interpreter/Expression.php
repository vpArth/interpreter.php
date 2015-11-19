<?php

namespace Arth\Utils\Interpreter;

abstract class Expression
{
    private static $keycount=0;
    private $key;
    public function getKey()
    {
        if (!isset($this->key)) {
            self::$keycount++;
            $this->key = self::$keycount;
        }
        return $this->key;
    }

    abstract function interpret( IContext $context );
}
