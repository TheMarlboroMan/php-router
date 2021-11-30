<?php
namespace srouter\interfaces;

/**
*describes a class that can build exception handlers. Does not accept null as a 
*way to signify that the router will crash. 
*/

interface exception_handler_factory {

	public function build_exception_handler(string $_name) : \srouter\interfaces\exception_handler;
}