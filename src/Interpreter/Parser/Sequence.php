<?php

namespace Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Scanner;

class Sequence extends Collection
{
    public function trigger(Scanner $scan)
    {
        return $this->parsers && $this->parsers[0]->trigger($scan);
    }
    protected function doScan(Scanner $scan)
    {
        $state = $scan->getState();
        foreach ($this->parsers as $p) {
            if (!$p->trigger($scan) || !$p->scan($scan)){
                $scan->setState($state);
                return false;
            }
        }
        return true;
    }
}
