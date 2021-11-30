<?php
namespace app\controller;

class version extends controller {

	public function get() : \srouter\controller_response {

		return new \srouter\controller_response(
			200,
			[new \srouter\http_response_header("some-header", "some-value")],
			["major" => 1, "minor" => 0, "patch" => 0]
		);
	}
}

