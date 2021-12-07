<?php
declare(strict_types=1);
namespace rimplementation\factory;

use srouter\interfaces\parameter_mapper;

class parameter_mapper_factory implements \srouter\interfaces\parameter_mapper_factory {

/**
*in case you didn't notice, all parameters have leading underscore names. I
*find it easy to identify arguments that way in a language with no compilers,
*no shadowing warnings and and that forces us to prepend $this-> to every
*instance property.
*/
	public function build_parameter_mapper(string $_name): ?parameter_mapper {

		switch($_name) {

			case "underscore":
				return new \rimplementation\providers\parameter_mapper_underscore();
		}

		return null;

	}

}
