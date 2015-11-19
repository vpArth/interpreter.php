<?php

namespace Arth\Utils\Interpreter\Handler;

use Arth\Utils\Interpreter\Handler;
use Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Scanner;
use Arth\Utils\Interpreter\Expression as E;

class Signed implements Handler
{
    public function match(Parser $parser, Scanner $scanner)
    {
        $value = $scanner->getContext()->pop();
        $sign = $scanner->getContext()->pop();
        switch ($sign) {
            case '+':
                $e = $value;
                break;
            case '-':
                $e = new E\Neg($value);
                break;
            default:
                $e = $value;
                if (!is_null($sign))
                    $scanner->getContext()->push($sign);
                break;
        }
        $scanner->getContext()->push($e);
    }
}
