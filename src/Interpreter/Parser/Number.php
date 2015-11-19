<?php

namespace Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Scanner;
use Arth\Utils\Interpreter\Parser;

class Number extends Parser
{
    protected $num;

    public function __construct($num = null)
    {
        parent::__construct();
        $this->num = $num;
    }
    public function trigger(Scanner $scanner)
    {
        if ($scanner->type() !== Scanner::NUMBER) {
            return false;
        }
        if (is_null($this->num)) {
            return true;
        }
        return ($scanner->token() == $this->num);
    }

    protected function doScan(Scanner $scanner) {
        return $this->trigger($scanner);
    }
}
