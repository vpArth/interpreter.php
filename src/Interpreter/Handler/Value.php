<?php

namespace Arth\Utils\Interpreter\Handler;

use Arth\Utils\Interpreter\Handler;
use Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Scanner;
use Arth\Utils\Interpreter\Expression as E;

class Value implements Handler
{
    public function match(Parser $parser, Scanner $scanner)
    {
        $value = $scanner->getContext()->pop();
        $scanner->getContext()->push(new E\Value($value));
    }
}
