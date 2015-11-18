<?php

namespace Arth\Utils\Interpreter\Expression;

use Arth\Utils\Interpreter\Expression;
use Arth\Utils\Interpreter\IContext;

class Func extends Expression
{
    protected $name;
    protected $arguments = array();
    protected static $list = array();

    public static function register($name, /*Callable */$call)
    {
        self::$list[$name] = $call;
    }

    function __construct($name, array $arguments = array())
    {
        $this->name = $name;
        $this->arguments = $arguments;
    }

    function interpret(IContext $ctx)
    {
        $foo = $ctx->getFunction($this->name) ?: self::$list[$this->name];
        $values = array_map(function($e) use($ctx){
            $e->interpret($ctx);
            return $ctx->get($e);
        }, $this->arguments);
        $ctx->set($this, call_user_func_array($foo, $values));
    }
    public function __toString()
    {
        $args = implode(', ', $this->arguments);
        return "{$this->name}($args)";
    }
}

Func::register('val', function($a) {
    return $a;
});
Func::register('sin', function($a) {return sin($a); });
Func::register('cos', function($a) {return cos($a); });
Func::register('ln', function($a) {return log($a); });
Func::register('lg', function($a) {return log($a, 10); });

// aggregates
Func::register('sum', function() {
    return array_sum(func_get_args());
});
Func::register('min', function() {return min(func_get_args());});
Func::register('max', function() {return min(func_get_args());});
Func::register('avg', function() {
    $mul = 1;
    foreach (func_get_args() as $i => $n) {
        $mul *= $n;
    }
   return pow($mul, 1/func_num_args());
});
Func::register('const', function($name) {
    switch ($name) {
        case 'pi': return M_PI;
        case 'exp': return M_E;
    }
});
