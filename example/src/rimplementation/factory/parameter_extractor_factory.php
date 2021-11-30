<?php
namespace rimplementation\factory;

class parameter_extractor_factory implements \srouter\interfaces\parameter_extractor_factory {

	public function build_parameter_extractor(
		string $_name
	) : ?\srouter\interfaces\parameter_extractor {

		switch($_name) {

			case "query": return new \rimplementation\query_only_parameter_extractor();
			case "json": return new \rimplementation\json_parameter_extractor();
			case "uri": return new \rimplementation\uri_parameter_extractor();
		}

		return null;
	}
}
