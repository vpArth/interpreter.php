<?php

namespace Arth\Utils\Interpreter;

interface Handler
{
    public function match(Parser $parser, Scanner $scanner);
}
