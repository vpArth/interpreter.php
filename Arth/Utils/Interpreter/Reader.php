<?php

namespace Arth\Utils\Interpreter;

interface Reader
{
    public function read();
    public function pos();
    public function back();
}
