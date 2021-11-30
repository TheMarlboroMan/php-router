<?php
namespace rimplementation;

class uri_parameter_extractor implements \srouter\interfaces\parameter_extractor {

	public function __construct() {

		$this->parameter_maker=new \srouter\parameter_maker();
	}

	public function extract(
		\srouter\argument $_argument,
		\srouter\interfaces\request $_request,
		array $_uri_params
	) {

		$name=$_argument->get_name();

		//if the argument comes in the URI, just be done with that.
		if($_argument->get_source()==="uri") {

			$value=$this->parameter_maker->find_uri_parameter($name, $_uri_params);
			return $this->parameter_maker->make_param($value, $_argument);
		}

		throw new \Exception("invalid argument type for uri parameter extractor");

	}

	private \srouter\parameter_maker $parameter_maker;

}
