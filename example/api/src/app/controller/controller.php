<?php
declare(strict_types=1);
namespace app\controller;

abstract class controller {

	public function __construct(
		\app\dependency_container $_dc
	) {

		$this->dc=$_dc;
	}

	protected \app\dependency_container $dc;

}
