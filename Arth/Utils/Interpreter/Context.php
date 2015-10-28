<?php

namespace Arth\Utils\Interpreter;

class Context
{
    private $data = array();
    public function push($a) { array_push($this->data, $a); }
    public function pop() { return array_pop($this->data); }
    public function size() { return count($this->data); }
    public function peek($index = 0)
    {
        if ($index>=$this->size()) return null;
        return $this->data[$this->size()-($index+1)];
    }
}
