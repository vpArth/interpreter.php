<?php

namespace Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Parser;

abstract class Wrap extends Parser {
    protected $parser;

    public function __construct(Parser $p)
    {
        parent::__construct();
        $this->parser = $p;
    }
    public function term() {return false;}
}
