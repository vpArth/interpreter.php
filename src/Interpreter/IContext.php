<?php

namespace Arth\Utils\Interpreter;

class IContext
{
    protected $data = array();
    public function set(Expression $e, $value)
    {
        $this->data[$e->getKey()] = $value;
    }

    public function get(Expression $e)
    {
        return $this->data[$e->getKey()];
    }

    // Custom functions
    protected static $sFunctions = array();
    protected $functions = array();
    public static function regSFunction($name, /*Callable*/ $foo)
    {
        static::$sFunctions[$name] = $foo;
    }
    public function regFunction($name, /*Callable*/ $foo)
    {
        $this->functions[$name] = $foo;
    }
    public function getFunction($name)
    {
        return isset($this->functions[$name]) ? $this->functions[$name] :
            (isset(static::$sFunctions[$name]) ? static::$sFunctions[$name] : null);
    }
}


IContext::regSFunction('trim', function($str) {
    return trim($str);
});
IContext::regSFunction('concat', function() {
    return implode('', func_get_args());
});
