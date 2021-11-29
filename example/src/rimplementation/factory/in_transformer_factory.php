<?php
namespace rimplementation\factory;

class in_transformer_factory implements \srouter\interfaces\in_transformer_factory {

	public function build(
		string $_name
	) : \srouter\interfaces\in_transformer {

		die("IN TRANSFORMER NEEDS TO BUILD $_name");
	}
}
