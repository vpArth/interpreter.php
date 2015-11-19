<?php

namespace Arth\Utils\Interpreter\Parser;

use Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Scanner;

class Alternative extends Collection
{
    public function trigger(Scanner $scan)
    {
        foreach ($this->parsers as $p) {
            if ($p->trigger($scan)) {
                return true;
            }
        }
        return false;
    }
    protected function doScan(Scanner $scan)
    {
        $state = $scan->getState();
        foreach ($this->parsers as $p) {
            if (!$p->trigger($scan)) {
                continue;
            }
            if ($p->scan($scan)) {
                return true;
            }
            $scan->setState($state);
        }
        return false;
    }
}
