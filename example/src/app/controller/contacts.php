<?php
namespace app\controller;

class contacts {

	public function get(
		?string $_filter_number,
		?string $_filter_name
	) {

var_dump(func_get_args());
die();

		return new \srouter\response(200, [], "lool");
	}

	public function post(
		string $_name,
		string $_phone,
		string $_company
	) {

		//TODO:
	}

	public function info(
		int $_id
	) {

		//TODO:
	}

	public function patch(
		int $_id,
		?string $_name,
		?string $_phone,
		?string $_company
	) {

		//TODO:
	}

	public function delete(
		int $_id,
	) {

		//TODO:
	}

}
