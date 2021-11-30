<?php
namespace srouter;

/**
*http response header.
*/

class http_response_header {

	public function __construct(
		string $_name,
		string $_value
	) {

		$this->name=$_name;
		$this->value=$_value;
	}

	public function get_name() : string {

		return $this->name;
	}

	public function get_value() : string {

		return $this->value;
	}

	public function __toString() : string {

		return strtolower($this->name).":".strtolower($this->value);
	}

	private string $name;
	private string $value;
}
