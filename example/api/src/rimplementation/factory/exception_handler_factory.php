<?php
declare(strict_types=1);
namespace rimplementation\factory;

class exception_handler_factory implements \srouter\interfaces\exception_handler_factory {

/**
*no exception handlers.
*/
	public function build_exception_handler(
		string $_name
	) : ?\srouter\interfaces\exception_handler {

		return null;
	}
}
