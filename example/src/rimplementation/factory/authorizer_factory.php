<?php
namespace rimplementation\factory;

class authorizer_factory implements \srouter\interfaces\authorizer_factory {

	public function build(
		string $_name
	) : \srouter\interfaces\authorizer {

		die("AUTHORIZER FACTORY NEEDS TO BUILD $_name");
	}
}
