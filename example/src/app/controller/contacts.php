<?php
namespace app\controller;

class contacts {

	public function get(
		string $_filter_number,
		string $_filter_name
	) {

var_dump(func_get_args());
die();

		return new \srouter\response(200, [], "lool");
	}

}
