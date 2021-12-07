<?php
declare(strict_types=1);
namespace app;

/**
*absolutely not production-ready dependency container, but will do for this
*small example.
*/

class dependency_container {

	public function __construct(
		string $_dir
	) {

		$this->dir=$_dir;
	}

	public function get_app_logger() : \log\logger_interface {

		if(null===$this->app_logger) {

			$app_log_file=$this->dir."/log/app.log";

			$this->app_logger=new \log\file_logger(
				new \log\default_formatter(),
				$app_log_file
			);
		}

		return $this->app_logger;
	}

	public function get_user_list() : \app\user_list {

		if(null===$this->user_list) {

			$user_file=$this->dir."/data/users.dat";
			$this->user_list=new \app\user_list($user_file);
		}

		return $this->user_list;
	}

	public function get_user_auth() : \app\user_auth {

		if(null===$this->user_auth) {

			$auth_file=$this->dir."/data/auth.dat";
			$this->user_auth=new \app\user_auth($auth_file);
		}

		return $this->user_auth;
	}

	public function get_rolodex() : \rolodex\rolodex {

		if(null===$this->rolodex) {

			$rolodex_file=$this->dir."/data/rolodex.dat";
			$this->rolodex=new \rolodex\rolodex($rolodex_file);
		}

		return $this->rolodex;
	}

	private string                  $dir;
	private ?\log\logger_interface  $app_logger=null;
	private ?\app\user_list         $user_list=null;
	private ?\app\user_auth         $user_auth=null;
	private ?\rolodex\rolodex       $rolodex=null;

}
