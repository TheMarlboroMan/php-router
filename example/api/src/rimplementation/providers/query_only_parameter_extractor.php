<?php
namespace rimplementation\providers;

class query_only_argument_extractor implements \srouter\interfaces\argument_extractor {

	public function __construct() {

		$this->argument_maker=new \srouter\argument_maker();
	}

	public function extract(
		\srouter\parameter $_argument,
		\srouter\interfaces\request $_request,
		array $_uri_arguments
	) {

		$name=$_argument->get_name();

		if($_argument->get_source()==="query") {

			$value=$this->argument_maker->find_query_argument($name, $_request);
			return $this->argument_maker->make_argument($value, $_argument);
		}

		throw new \Exception("invalid argument type for query only parameter extractor");

	}

	private \srouter\argument_maker $argument_maker;

}
