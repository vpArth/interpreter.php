<?php

namespace Arth\Utils\Interpreter;

class IContext
{
    private $data = array();
    public function set(Expression $e, $value)
    {
        $this->data[$e->getKey()] = $value;
    }

    public function get(Expression $e)
    {
        return $this->data[$e->getKey()];
    }
}
