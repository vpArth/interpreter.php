<?php

namespace Arth\Utils\Interpreter;

class StringReader implements Reader
{
    protected $src = '', $pos = 0;
    public function __construct($s)
    {
        $this->src = preg_split('//u', $s, -1, PREG_SPLIT_NO_EMPTY);
    }
    public function __toString()
    {
        return implode('', $this->src);
    }

    public function pos()
    {
        return $this->pos;
    }
    public function back()
    {
        if (!$this->pos) throw new \Exception('Already at start');
        $this->pos--;
        return $this;
    }
    public function read()
    {
        if ($this->pos >= count($this->src)) {
            return false;
        }
        return $this->src[$this->pos++];
    }
}
