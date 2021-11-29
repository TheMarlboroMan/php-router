<?php
namespace srouter;

/**
*implementors will be directly dealing with this class when doing argument
*extraction. This class automates the job of creating a PHP function parameter
*from a value and a router argument.
*To automate everything, the router should actually iterate all arguments for
*the router path and then call them one by one on whatever uses this thing.
*/

class parameter_maker {

/**
*returns a mixed value that will go as a parameter in a method call.
*/

	public function make_param(
		$_value,
		argument $_argument
	) {

		if(null===$_value) {

			if($_argument->is_nullable()) {

				return null;
			}

			if(!$_argument->is_optional()) {

				throw new \srouter\exception\missing_compulsory_parameter($_argument->get_name());
			}

			$_value=$_argument->get_default();
		}

		//Could actually happen with default values that are null.
		if(null===$_value && $_argument->is_nullable()) {

			return null;
		}

		try {

			$this->type_check($_value, $_argument->get_type());
		}
		catch(\srouter\exception\bad_parameter_type $e) {

			throw new \srouter\exception\bad_parameter_type("bad parameter type for '".$_argument->get_name()."'", 0, $e);
		}

		return $_argument->is_notrim()
			? $_value
			: $this->trim($_value);
	}

/**
*attempts to extract a uri-specific parameter by name, because I love those.
*/

	public function  find_uri_parameter(
		string $_name,
		array $_parameters
	) {

		var_dump($_parameters);
		die();
		//TODO TODO TODO TODO TODO.
		die("FIND URI PARAMETER");
	}

/**
*attempts to find a query string parameter and returns null if cannot find it.
*/

	public function	find_query_parameter(
		string $_name,
		\srouter\interfaces\request $_request
	) {

		return $_request->has_query($_name)
			? $_request->get_query($_name)
			: null;
	}

	private function type_check(
		$_value,
		int $_type
	) : void {

		switch($_type) {

			case \srouter\argument::type_any:
				return;
			case \srouter\argument::type_int:
				if(is_int($_value)) {
					return;
				}
			case \srouter\argument::type_bool:
				if(is_bool($_value)) {
					return;
				}
			case \srouter\argument::type_string:
				if(is_string($_value)) {
					return;
				}
 			case \srouter\argument::type_double:
				if(is_double($_value)) {
					return;
				}
			case \srouter\argument::type_array:
				if(is_array($_value)) {
					return;
				}
		}

		throw new \srouter\exception\bad_parameter_type();
	}

	private function trim(
		$_value
	) {

		if(is_string($_value)) {

			return trim($_value);
		}

		if(is_array($_value)) {

			foreach($_value as $key => $val) {

				$_value[$key]=$this->trim($val);
			}

			return $_value;
		}

		return $_value;
	}
}
