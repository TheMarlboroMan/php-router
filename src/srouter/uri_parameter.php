<?php
namespace srouter;

/**
*uri parameters are a very particular thing and take the form of variable 
*sections in a uri like /path/user/USERNAME/profile where USERNAME is what
*we call a uri_parameter. A uri may have several placeholders and it is the 
*job of the path_mapper to uniquely identify each one with a name and assign
*its value.
*/

class uri_parameter {

	public function __construct(
		string $_name,
		$_value
	) {

		$this->name=$_name;
		$this->value=$_value;
	}

/**
*returns the parameter name.
*/
	public function     get_name() :string {

		return $this->name;
	}

/**
*returns the parameter value as seen in the URI.
*/
	public function     get_value() {

		return $this->value;
	}

	private string   $name;
	private          $value;
}
