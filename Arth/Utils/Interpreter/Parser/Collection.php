<?php

namespace Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Parser;

abstract class Collection extends Parser
{
    protected $parsers = array();

    public function add(Parser $p)
    {
        $this->parsers[] = $p;
        return $p;
    }

    public function term() {return false;}
}
