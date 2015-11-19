<?php

namespace Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Scanner;

class MayBe extends Wrap
{
    function trigger(Scanner $scan) { return true; }

    protected function doScan( Scanner $scan )
    {
        if ($this->parser->trigger($scan))
            $this->parser->scan($scan);
        return true;
    }
}
