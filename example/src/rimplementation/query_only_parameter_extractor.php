<?php
namespace rimplementation;

class query_only_parameter_extractor implements \srouter\interfaces\parameter_extractor {

	public function __construct() {

		$this->parameter_maker=new \srouter\parameter_maker();
	}

	public function extract(
		\srouter\argument $_argument,
		\srouter\interfaces\request $_request,
		array $_uri_params
	) {

		$name=$_argument->get_name();

		if($_argument->get_source()==="query") {

			$value=$this->parameter_maker->find_query_parameter($name, $_request);
			return $this->parameter_maker->make_param($value, $_argument);
		}

		throw new \Exception("invalid argument type for query only parameter extractor");

	}

	private \srouter\parameter_maker $parameter_maker;

}
