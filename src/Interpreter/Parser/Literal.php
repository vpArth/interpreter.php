<?php

namespace Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Scanner;

class Literal extends Parser
{
    protected function push(Scanner $scan){}
    public function trigger(Scanner $scan)
    {
        return in_array($scan->type(), array(Scanner::APOS, Scanner::QUOTE));
    }

    protected function doScan(Scanner $scan) {
        $q = $scan->type();
        $ok = false;
        $escape = false;
        $str = '';
        while ($scan->next()) {
            if ($escape) {
                $escape = false;

            } else {
                if ($scan->token() === '\\') {
                    $escape = true;
                    continue;
                }
                if ($q === $scan->type()) {
                    $ok = true;
                    break;
                }
            }
            $str .= $scan->token();
        }
        if ($ok && !$this->discard) {
            $scan->getContext()->push($str);
        }
        return $ok;
    }
}
