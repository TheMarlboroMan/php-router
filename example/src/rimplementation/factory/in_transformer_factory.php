<?php
namespace rimplementation\factory;

class in_transformer_factory implements \srouter\interfaces\in_transformer_factory {

	public function build(
		string $_name
	) : \srouter\interfaces\in_transformer {

		throw new \Exception("there are no input transformers, actually");
		return null;
	}
}
