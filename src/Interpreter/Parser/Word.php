<?php

namespace Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Scanner;

class Word extends Parser
{
    protected $word;

    public function __construct($word = null)
    {
        parent::__construct();
        $this->word = $word;
    }
    public function trigger(Scanner $scanner)
    {
        if ($scanner->type() !== Scanner::WORD) {
            return false;
        }
        if (is_null($this->word)) {
            return true;
        }
        return ($scanner->token() == $this->word);
    }

    protected function doScan(Scanner $scanner) {
        return $this->trigger($scanner);
    }
}
