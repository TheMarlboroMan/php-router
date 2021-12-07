<?php
declare(strict_types=1);
namespace app;

class user {

	public string $username;
	public string $pass;

	public function __construct(
		string $_user,
		string $_pass
	) {

		$this->username=$_user;
		$this->pass=$_pass;
	}
};

/**
*this is just a very quick example, don't use this stuff in production!!!
*/

class user_list {

	public function __construct(
		string $_filename
	) {

		$lines=file($_filename, FILE_IGNORE_NEW_LINES);
		if(false===$lines) {

			throw new \Exception("could not read users source");
		}

		$lines=array_filter(
			$lines,
			function(string $_line) {

				return 1===substr_count($_line, ":");
			}
		);

		$this->users=array_values(array_map(
			function(string $_line) : user {

				list($user, $pass)=explode(":", $_line);
				return new user($user, $pass);
			},
			$lines
		));
	}

	public function find_by_name(
		string $_name
	) :?user {

		$users=array_filter(
			$this->users,
			function(user $_user) use ($_name) {

				return $_user->username===$_name;
			}
		);

		if(!count($users)) {

			return null;
		}

		return array_shift($users);
	}

	private array   $users;
}
