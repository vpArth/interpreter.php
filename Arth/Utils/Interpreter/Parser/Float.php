<?php

namespace Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Scanner;

class Float extends Parser
{
    protected $number;
    public function __construct($number = null)
    {
        parent::__construct();
        $this->number = $number;
    }
    protected function push(Scanner $scan){}
    public function trigger(Scanner $scan)
    {
        if ($scan->type() !== Scanner::NUMBER) return false;
        if (is_null($this->number)) return true;
        return intval($this->number) == intval($scan->token());
    }

    protected function doScan(Scanner $scan)
    {
        $start = $scan->getState();

        $int = $scan->token();
        $scan->next();

        if ($scan->token() !== '.') {
            $scan->setState($start);

            $res = is_null($this->number) || $this->number == $int;
            if ($res) {
                $scan->getContext()->push($int);
                // echo "$int | Parsed. Next token is not a point\n";
            // } else {
                // echo "$int | Next token is not a point. Doesn't match {$this->number}\n";
            }
            return $res;
        }
        if (!$scan->next() || $scan->type() !== Scanner::NUMBER) {
            $scan->setState($start);
            // echo "$int | Fail: not number after /\d+\./ \n";
            return false;
        }
        $frac = $scan->token();
        if (!is_null($this->number)) {
            if ("$int.$frac" != $this->number) {
                $scan->setState($start);
                // echo "$int.$frac | Parsed float doesn't match {$this->number}\n";
                return false;
            }
        }
        $scan->getContext()->push("$int.$frac");
        // echo "$int.$frac | Parsed\n";
        return true;
    }
}
