<?php
declare(strict_types=1);
namespace rimplementation\factory;

class controller_factory implements \srouter\interfaces\controller_factory {

	public function __construct(
		\app\dependency_container $_dc
	) {

		$this->dc=$_dc;
	}

	public function build_controller(
		string $_classname
	) {

		//big c++ fan.
		$classname="\\app\\".str_replace("::", "\\", $_classname);
		if(!class_exists($classname)) {

			return null;
		}

		return new $classname($this->dc);
	}

	private \app\dependency_container $dc;
}
