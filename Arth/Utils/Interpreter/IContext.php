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

    // Custom functions
    protected $functions = array();
    public function regFunction($name, /*Callable*/ $foo)
    {
        $this->functions[$name] = $foo;
    }
    public function getFunction($name)
    {
        return isset($this->functions[$name]) ? $this->functions[$name] : null;
    }
}
