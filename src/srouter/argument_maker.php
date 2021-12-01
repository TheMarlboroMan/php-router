<?php
namespace srouter;

/**
*implementors will be directly dealing with this class when doing argument
*extraction. This class automates the job of creating a PHP function argument
*from a value and a router argument.
*To automate everything, the router should actually iterate all arguments for
*the router path and then call them one by one on whatever uses this thing.
*/

class argument_maker {

/**
*returns a mixed value that will go as an argument in a method call.
*/
	public function make_argument(
		$_value,
		parameter $_parameter
	) {

		if(null===$_value) {

			if($_parameter->is_nullable()) {

				return null;
			}

			if(!$_parameter->is_optional()) {

				throw new \srouter\exception\missing_compulsory_argument($_parameter->get_name());
			}

			$_value=$_parameter->get_default();
		}

		//Could actually happen with default values that are null.
		if(null===$_value && $_parameter->is_nullable()) {

			return null;
		}

		try {

			$this->type_check($_value, $_parameter->get_type());
		}
		catch(\srouter\exception\bad_argument_type $e) {

			throw new \srouter\exception\bad_argument_type("bad argument type for '".$_parameter->get_name()."'", 0, $e);
		}

		return $_parameter->is_notrim()
			? $_value
			: $this->trim($_value);
	}

/**
*attempts to extract a uri-specific argument by name, because I love those.
*check the uri_argument class to learn about them. The path mapper can identify
*these and, if so, return them into a map. This just looks into such a map and
*returns the value if found, null if not.
*/
	public function  find_uri_argument(
		string $_name,
		array $_parameters
	) {

		$found=array_filter(
			$_parameters,
			function(\srouter\uri_argument $_param) use ($_name) {

				return $_param->get_name() === $_name;
			}
		);

		if(!count($found)) {

			return null;
		}

		return array_shift($found)->get_value();
	}

/**
*attempts to find an argument in the query string and returns null if cannot
*find it.
*/
	public function	find_query_argument(
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

			case \srouter\parameter::type_any:
				return;
			case \srouter\parameter::type_int:
				if(is_int($_value)) {
					return;
				}
			case \srouter\parameter::type_bool:
				if(is_bool($_value)) {
					return;
				}
			case \srouter\parameter::type_string:
				if(is_string($_value)) {
					return;
				}
 			case \srouter\parameter::type_double:
				if(is_double($_value)) {
					return;
				}
			case \srouter\parameter::type_array:
				if(is_array($_value)) {
					return;
				}
		}

		throw new \srouter\exception\bad_argument_type();
	}

/**
*trims a string value and all strings within arrays.
*/
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
