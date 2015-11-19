<?php

namespace Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Scanner;

class CharClass extends Parser
{
    protected $chars;

    public function __construct($chars = array())
    {
        parent::__construct();
        $this->chars = is_array($chars) ? $chars : array($chars);
    }
    public function trigger(Scanner $scan)
    {
        return in_array($scan->token(), $this->chars);
    }

    protected function doScan(Scanner $scan) {
        return $this->trigger($scan);
    }
}
