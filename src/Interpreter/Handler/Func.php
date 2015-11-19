<?php

namespace Arth\Utils\Interpreter\Handler;

use Arth\Utils\Interpreter\Handler;
use Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Scanner;
use Arth\Utils\Interpreter\Expression as E;

class Func implements Handler
{
    public function match(Parser $parser, Scanner $scanner)
    {
        // only one argument implementation yet
        $start = $scanner->getContext()->pop();

        $arguments = array();
        while (($t = $scanner->getContext()->pop())!=='(') {
            $arguments[] = $t;
        }
        $name = $scanner->getContext()->pop();
        $scanner->getContext()->push(new E\Func($name, array_reverse($arguments)));
    }
}
