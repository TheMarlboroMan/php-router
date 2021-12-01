<?php
namespace rimplementation\factory;

class out_transformer_factory implements \srouter\interfaces\out_transformer_factory {

/**
*provides a way to map the controller response body to json. This, of course,
*implies that we will make out stuff jsonSerializable.
*/

	public function build_out_transformer(
		string $_name
	) : ?\srouter\interfaces\out_transformer {

		switch($_name) {

			case "json": return new \rimplementation\providers\json_out();
		}

		return null;
	}
}
