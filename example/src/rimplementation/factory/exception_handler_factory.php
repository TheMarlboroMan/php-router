<?php
namespace rimplementation\factory;

class exception_handler_factory implements \srouter\interfaces\exception_handler_factory {

	public function build_exception_handler(
		string $_name
	) : \srouter\interfaces\exception_handler {

		throw new \Exception("There are no exception handlers here");
	}
}
