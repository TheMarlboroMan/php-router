<?php
declare(strict_types=1);
namespace rimplementation\providers;

class parameter_mapper_underscore implements \srouter\interfaces\parameter_mapper {

/**
*underscores parameter names: they are declared without underscores in the route
*and with a leading underscore in the controller source.
*/
	public function map(string $_name): string {

		return "_".$_name;
	}
}
