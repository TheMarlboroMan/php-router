<?php
namespace srouter;

class uri_parameter {

	public function __construct(
		string $_name,
		$_value
	) {

		$this->name=$_name;
		$this->value=$_value;
	}

	public function     get_name() :string {

		return $this->name;
	}

	public function     get_value() {

		return $this->value;
	}

	private string   $name;
	private          $value;
}
