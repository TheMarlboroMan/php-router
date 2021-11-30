<?php
namespace app\controller;

class contacts extends controller {

	public function get(
		?string $_filter_number,
		?string $_filter_name
	) :\srouter\controller_response {

		//TODO:
	}

	public function post(
		string $_name,
		string $_phone,
		string $_company
	) :\srouter\controller_response {

		//TODO:
	}

	public function info(
		int $_id
	) :\srouter\controller_response {

		//TODO:
	}

	public function patch(
		int $_id,
		?string $_name,
		?string $_phone,
		?string $_company
	) :\srouter\controller_response {

		//TODO:
	}

	public function delete(
		int $_id,
	) :\srouter\controller_response {

		//TODO:
	}

}
