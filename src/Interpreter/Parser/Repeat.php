<?php

namespace Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Parser;
use Arth\Utils\Interpreter\Scanner;

class Repeat extends Wrap {
    private $min;
    private $max;

    public function __construct(Parser $p, $min=0, $max=0)
    {
        parent::__construct($p);
        if ($max < $min && $max > 0) {
            throw new \Exception("maximum($max) greater than minimum($min)");
        }
        $this->min = $min;
        $this->max = $max;
    }

    function trigger(Scanner $scan) { return true; }

    protected function doScan( Scanner $scan )
    {
        $start = $scan->getState();

        $count = 0;
        while ( true ) {
            if ( $this->max > 0 && $count >= $this->max) {
                break;
            }

            if (!$this->parser->trigger($scan) || !$this->parser->scan($scan) ) {
                if ($this->min == 0 || $count >= $this->min) {
                    break;
                } else {
                    //
                    $scan->setState($start);
                    return false;
                }
            }
            $count++;
        }
        return true;
    }
}
