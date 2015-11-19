<?php

namespace Arth\Utils\Interpreter\Handler;

use Arth\Utils\Interpreter\Handler;
use Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Scanner;
use Arth\Utils\Interpreter\Expression as E;

class Operator implements Handler
{
    public function match(Parser $parser, Scanner $scanner)
    {
        $right = $scanner->getContext()->pop();
        $op = $scanner->getContext()->pop();
        $left = $scanner->getContext()->pop();
        switch ($op) {
            case '+':
                $e = new E\Add($left, $right);
                break;
            case '-':
                $e = new E\Sub($left, $right);
                break;
            case '*':
                $e = new E\Mul($left, $right);
                break;
            case '/':
                $e = new E\Div($left, $right);
                break;
            case '^':
                $e = new E\Pow($left, $right);
                break;
            // default: '????'; return;
        }
        $scanner->getContext()->push($e);
    }
}
