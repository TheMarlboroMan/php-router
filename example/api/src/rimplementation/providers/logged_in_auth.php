<?php
namespace rimplementation\providers;

use srouter\interfaces\request;

class logged_in_auth implements \srouter\interfaces\authorizer {

	private const log_module="logged_in_auth";

	public function __construct(
		\log\logger_interface $_logger,
		\app\user_auth $_user_auth
	) {

		$this->logger=$_logger;
		$this->user_auth=$_user_auth;
	}

	public function authorize(
		\srouter\interfaces\request $_request
	): bool {

		if(!$_request->has_header("rolodex-auth-token")) {

			$this->logger->notice("request has no auth bearing token", self::log_module);
			return false;
		}

		$token=$_request->get_header("rolodex-auth-token");
		$auth_node=$this->user_auth->get($token);
		if(null===$auth_node) {

			$this->logger->notice("auth bearing token not found or expired", self::log_module);
			return false;
		}

		$this->logger->info("valid request, extending token", self::log_module);
		$this->user_auth->extend($token);
		return true;
	}

	private \log\logger_interface   $logger;
	private \app\user_auth          $user_auth;

}
