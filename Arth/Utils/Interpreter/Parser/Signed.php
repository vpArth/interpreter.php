<?php

namespace Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Scanner;
use Arth\Utils\Interpreter\Handler;
//[+-]? ...
class Signed extends Wrap
{
    public function __construct(Parser $p)
    {
        $t = new Parser\Sequence();
        $t->add(/*new Parser\MayBe*/(new Parser\CharClass(array('+', '-'))));
        $t->add($p);
        $t->setHandler(new Handler\Signed);
        parent::__construct($t);
    }
    public function trigger(Scanner $scan) {return $this->parser->trigger($scan); }
    protected function doScan( Scanner $scan ) {return $this->parser->scan($scan); }
}
