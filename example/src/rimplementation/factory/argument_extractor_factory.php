<?php
namespace rimplementation\factory;

class parameter_extractor_factory implements \srouter\interfaces\parameter_extractor_factory {

	public function build(
		string $_name
	) : \srouter\interfaces\parameter_extractor {

		die("ARGUMENT EXTRACTOR NEEDS TO BUILD $_name");
	}
}
