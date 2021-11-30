<?php
namespace srouter\interfaces;

/**
*describes a class that can build exception handlers. Does not accept null at 
*all as a return value: a handler must always come out.
*/

interface exception_handler_factory {

/**
*must build and return a exception handler.
*/
	public function build_exception_handler(string $_name) : \srouter\interfaces\exception_handler;
}
