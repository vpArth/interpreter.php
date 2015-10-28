<?php

namespace Arth\Utils\Interpreter;

class Scanner
{
    const WORD         = 1;
    const QUOTE        = 2;
    const APOS         = 3;
    const NUMBER       = 4;
    const WHITESPACE   = 6;
    const EOL          = 8;
    const CHAR         = 9;
    const EOF          = 0;
    const SOF          = -1;

    protected $line = 1;
    protected $char = 1;
    protected $type = self::SOF;
    protected $token = null;

    protected $reader, $context;
    public function __construct(Reader $r, Context $c)
    {
        $this->reader = $r;
        $this->context = $c;
    }
    public function getContext()
    {
        return $this->context;
    }

    public function token() {return $this->token; }
    public function type() {return $this->type; }
    public function line() {return $this->line; }
    public function char() {return $this->char; }

    protected static function getCharType($char)
    {
        switch (true) {
            case $char === false: return self::EOF;
            case $char==="'": return self::APOS;
            case $char==='"': return self::QUOTE;
            case preg_match('/[\r\n]/i', $char): return self::EOL;
            case preg_match('/\d/i', $char): return self::NUMBER;
            case preg_match('/\s/i', $char): return self::WHITESPACE;
            case preg_match('/[a-zа-я_\d]/i', $char): return self::WORD;
            default: return self::CHAR;
        }
    }

    protected static function isTypeMultiChar($type)
    {
        return in_array($type, array(self::WORD, self::NUMBER, self::WHITESPACE));
    }

    public function next()
    {
        $token = '';
        $ch = $this->reader->read();
        $type = self::getCharType($ch);
        $this->type = $type;
        if ($type === self::EOF) {
            $this->token = null;
            // echo "Token($type): [EOF] <{$this->line}, {$this->char}>\n";
            return $this;
        }
        if ($type === self::EOL) {
            $this->line++;
            $this->char = 1;
            $this->token = "\n";
            // echo "Token($type): [EOL] <{$this->line}, {$this->char}>\n";
            return $this;
        }
        if (self::isTypeMultiChar($type)) {
            do {
                $token .= $ch;
                $ch = $this->reader->read();
            } while ($type === self::getCharType($ch));
            if ($ch !== false) $this->reader->back();
        } else $token .= $ch;
        $this->token = $token;
        $this->char += mb_strlen($token, 'UTF-8');
        // echo "\n\tToken($type): [{$this->token}] <{$this->line}, {$this->char}>\n";
        return $this;
    }
    public function getState()
    {
        return array(
            'line' => $this->line,
            'char' => $this->char,
            'type' => $this->type,
            'token' => $this->token,
            'reader' => clone $this->reader,
            'context' => clone $this->context
        );
    }

    public function setState($state)
    {
        $this->line    = $state['line'];
        $this->char    = $state['char'];
        $this->type    = $state['type'];
        $this->token   = $state['token'];
        $this->reader  = $state['reader'];
        $this->context = $state['context'];
        return $this;
    }

    public function peek()
    {
        $save = $this->getState();
        $this->next();
        $result = array($this->type, $this->token());
        $this->setState($save);
        return $result;
    }
}
