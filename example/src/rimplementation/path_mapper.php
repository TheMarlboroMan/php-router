<?php
namespace rimplementation;

/**
*this class uses the pattern matcher to do its job.
*/

class path_mapper implements \srouter\interfaces\path_mapper {

	public function __construct(
		string $_filename
	) {

		$this->matcher=\tools\pattern_matcher\matcher::from_empty();

		$contents=file_get_contents($_filename);
		if(false===$contents) {

			throw new \Exception("could not read contents of $_filename");
		}

		$path_info=json_decode($contents);
		if(JSON_ERROR_NONE !== json_last_error()) {

			throw new \Exception("failed to decode path index file: ".json_last_error_msg());
		}

		foreach($path_info as $value) {

			$this->matcher->add_pattern(
				new \tools\pattern_matcher\pattern($value->path, count($this->path_map))
			);
			$this->path_map[]=$value->methods;
		}
	}

	public function map(
		string $_method,
		string $_uri
	) : ?\srouter\route {


		$match=$this->matcher->match($_uri);
		if(!$match->is_match()) {

			return null;
		}

		$family=$this->path_map[$match->get_name()];
		if(!property_exists($family, $_method)) {

			return null;
		}

		$path=$family->$_method;

		var_dump($path);
		$arguments=array_map(
			function(\stdClass $_item) : \srouter\argument {

				//TODO: Extract these!!

				return new \srouter\argument(
					$_item->name,
					$_item->source,
					$type,
					$optional,
					$notrim,
					$default
				);
			},
			$path->arguments
		);

		return new \srouter\route(
			$path->controller,
			$path->method,
			$arguments,
			$path->out,
			array_map(
				function(\tools\pattern_matcher\parameter $_item) {

					return new \srouter\uri_parameter($_item->get_name(), $_item->get_value());
				},
				$match->get_parameters()
			),
			$path->auth,
			$path->in,
			$path->arg
		);
	}

	private \tools\pattern_matcher\matcher  $matcher;
	private array                           $path_map=[];
}
