[![Build Status](https://travis-ci.org/vpArth/interpreter.php.svg?branch=master)](https://travis-ci.org/vpArth/interpreter.php)

Simple Intepreter
===

Context-free grammars based interpreter with own parsing system.

Usage: `see tests\Intepreter\Language.php`

For 5.3 compat used global function foo:
`function foo($a) { return $a; }`
`foo(new ClassName)->method()`
