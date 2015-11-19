<?php

namespace Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Scanner;

class Words extends Parser
{
    protected $words;

    public function __construct($words = array())
    {
        parent::__construct();
        $this->words = is_array($words) ? $words : array($words);
    }
    public function trigger(Scanner $scan)
    {
        return in_array($scan->token(), $this->words);
    }

    protected function doScan(Scanner $scan) {
        return $this->trigger($scan);
    }
}
