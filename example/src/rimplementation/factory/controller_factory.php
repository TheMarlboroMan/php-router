<?php
namespace rimplementation\factory;

class controller_factory implements \srouter\interfaces\controller_factory {

	public function build(
		string $_classname
	) {

		$classname="\\app\\".str_replace("::", "\\", $_classname);
		if(!class_exists($classname)) {

			return null;
		}

		return new $classname();
	}
}
