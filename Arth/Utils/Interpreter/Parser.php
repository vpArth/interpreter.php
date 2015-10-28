<?php

namespace Arth\Utils\Interpreter;

abstract class Parser
{
    protected $discard = false;
    protected $name;
    private static $count=0;
    public $id=0;

    public function __construct()
    {
        self::$count++;
        $this->id = self::$count;
        $this->setName(get_class($this)."[".$this->id."]");
        // $this->handler = new Handler\Value;
    }
    public function setName($name) { $this->name = $name; return $this;}
    public function getName() { return $this->name;}

    public function next(Scanner $scan)
    {
        // @todo: make \s skipping customizable
        do {
        // echo "\n\t{$this->name}: ".$scan->token();
            $scan->next();
            // echo ", ".$scan->token()."\n";
        } while ($scan->type() === Scanner::WHITESPACE);
    }

    public function scan(Scanner $scan)
    {
        if ($scan->type() === Scanner::SOF) $this->next($scan);
        $ret = $this->doScan($scan);
        $this->report("scan=$ret discard={$this->discard} term=".$this->term());

        if ($ret && !$this->discard && $this->term()) $this->push($scan);
        if ($ret) $this->invokeHandler($scan);
        if ($this->term() && $ret) $this->next($scan);
        return $ret;
    }
    public function discard() { $this->discard = true; return $this;}
    public function term() {return true; }

    abstract public function trigger(Scanner $scan);

    abstract protected function doScan(Scanner $scan);

    protected function push(Scanner $scan)
    {
        $scan->getContext()->push($scan->token());
    }

    protected $handler;
    public function setHandler(Handler $h)
    {
        $this->handler = $h;
        return $this;
    }
    protected function invokeHandler(Scanner $scan)
    {
        if (!empty($this->handler))
        {
            // $this->report( "Call handler: ".get_class($this->handler));
            $this->handler->match($this, $scan);
        }
    }

    // Debug purpose
    protected static $debug = false;
    public static function setDebug($bool) { self::$debug = $bool; }

    public function report($msg)
    {
        if (self::$debug) {
            echo "<".$this->getName().">: $msg\n";
        }
    }
}
