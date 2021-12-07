<?php
declare(strict_types=1);
namespace rimplementation\factory;

class argument_extractor_factory implements \srouter\interfaces\argument_extractor_factory {

/**
*we provide with three different ways of getting arguments.
*/
	public function build_argument_extractor(
		string $_name
	) : ?\srouter\interfaces\argument_extractor {

		switch($_name) {

			case "query": return new \rimplementation\providers\query_only_argument_extractor();
			case "json": return new \rimplementation\providers\json_argument_extractor();
			case "uri": return new \rimplementation\providers\uri_argument_extractor();
		}

		return null;
	}
}
