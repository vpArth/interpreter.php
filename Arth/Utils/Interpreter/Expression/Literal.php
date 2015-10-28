<?php

namespace Arth\Utils\Interpreter\Expression;

class Literal extends Value {
    public function __toString() {return "'{$this->value}'"; }
}
