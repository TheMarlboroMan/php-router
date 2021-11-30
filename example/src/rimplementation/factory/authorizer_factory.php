<?php
namespace rimplementation\factory;

class authorizer_factory implements \srouter\interfaces\authorizer_factory {

	public function __construct(
		\app\dependency_container $_dc
	) {

		$this->dc=$_dc;
	}

	public function build(
		string $_name
	) : \srouter\interfaces\authorizer {

		return
		die("AUTHORIZER FACTORY NEEDS TO BUILD $_name");
	}

	private \app\dependency_container $dc;

}
