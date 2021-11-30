<?php
namespace rimplementation\factory;

class out_transformer_factory implements \srouter\interfaces\out_transformer_factory {

	public function build(
		string $_name
	) : ?\srouter\interfaces\out_transformer {

		switch($_name) {

			case "json": return new \rimplementation\json_out();
		}

		return null;
	}
}
