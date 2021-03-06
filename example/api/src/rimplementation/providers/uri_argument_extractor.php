<?php
declare(strict_types=1);
namespace rimplementation\providers;

class uri_argument_extractor implements \srouter\interfaces\argument_extractor {

	public function __construct() {

		$this->argument_maker=new \srouter\argument_maker();
	}

	public function extract(
		\srouter\parameter $_parameter,
		\srouter\interfaces\request $_request,
		array $_uri_arguments
	) {

		$name=$_parameter->get_name();

		//if the argument comes in the URI, just be done with that.
		if($_parameter->get_source()==="uri") {

			$value=$this->argument_maker->find_uri_argument($name, $_uri_arguments, $_parameter);
			return $this->argument_maker->make_argument($value, $_parameter);
		}

		throw new \Exception("invalid argument type for uri argument extractor");

	}

	private \srouter\argument_maker $argument_maker;

}
