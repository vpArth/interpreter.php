<?php

namespace Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Scanner;

class Char extends Parser
{
    protected $char;

    public function __construct($char = null)
    {
        parent::__construct();
        $this->char = $char;
    }
    public function trigger(Scanner $scanner)
    {
        if ($scanner->type() !== Scanner::CHAR) {
            return false;
        }
        if (is_null($this->char)) {
            return true;
        }
        return ($scanner->token() == $this->char);
    }

    protected function doScan(Scanner $scanner) {
        return $this->trigger($scanner);
    }
}
