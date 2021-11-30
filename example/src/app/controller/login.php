<?php
namespace app\controller;

class login extends controller {

	private const log_module="login";

	public function post(
		string $_username,
		string $_pass
	) :\srouter\controller_response {

		$user_list=$this->dc->get_user_list();
		$user=$user_list->find_by_name($_username);

		if(null===$user) {

			$this->dc->get_app_logger()->info("bad username '$_username'", self::log_module);

			return new \srouter\controller_response(
				400, [], "invalid username or password"
			);
		}

		if($user->pass!==hash("sha512", $_pass)) {

			$this->dc->get_app_logger()->info("bad password for '$_username'", self::log_module);

			return new \srouter\controller_response(
				400, [], "invalid username or password"
			);
		}

		$token=$this->dc->get_user_auth()->generate($user->username);
		return new \srouter\controller_response(
			200,
			[new \srouter\http_response_header("auth-token", $token)],
			"welcome"
		);


	}
}

