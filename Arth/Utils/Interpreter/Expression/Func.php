<?php

namespace Arth\Utils\Interpreter\Expression;

use Arth\Utils\Interpreter\Expression;
use Arth\Utils\Interpreter\IContext;

class Func extends Expression
{
    protected $name;
    protected $arguments = array();
    protected static $list = array();

    public static function register($name, $call)
    {
        self::$list[$name] = $call;
    }

    function __construct($name, array $arguments = array())
    {
        $this->name = $name;
        // Each is Expression
        $this->arguments = $arguments;
    }

    function interpret(IContext $ctx)
    {
        $foo = self::$list[$this->name];
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
