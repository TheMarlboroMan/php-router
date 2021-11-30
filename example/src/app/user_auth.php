<?php
namespace app;

class auth_node {

	public string $token;
	public string $user;
	public \DateTime $valid_until;

	public function __construct(
		string $_token,
		string $_user,
		\DateTime $_valid_until
	) {

		$this->token=$_token;
		$this->user=$_user;
		$this->valid_until=$_valid_until;
	}
}

/**
*do not ever ever ever ever ever ever use this in production!
*/

class user_auth {

	public function __construct(
		string $_filename
	) {

		if(!is_file($_filename)) {

			touch($_filename);
		}

		$this->filename=$_filename;
		$this->read_list($this->filename);
	}

/**
*generates a new token for the given username
*/
	public function generate(
		string $_user
	) : string {

		$valid_until=new \DateTime();
		$valid_until->modify("+1 hour");

		//not checked for uniqueness, do not use this in production!
		$token=bin2hex(random_bytes(32));

		$this->list[]=new auth_node(
			$token,
			$_user,
			$valid_until
		);

		$this->save_list($this->filename);

		return $token;
	}

/**
*extends the duration of the given token if it is not expired!
*/
	public function extend(
		string $_token
	) : bool {

		$token=$this->get($_token);
		if(null===$token) {

			return false;
		}

		//Check for expiration.
		$now=new \DateTime();
		if($now > $token->valid_until) {

			return false;
		}

		$now->modify("+1 hour");
		$token->valid_until=$now;

		$this->save_list($this->filename);

		return true;
	}

/**
*returns the node that corresponds to the given token
*/
	public function get(
		string $_token
	) : auth_node {

		$found=array_filter(
			$this->list,
			function(auth_node $_node) use ($_token) {

				return $_node->token===$_token;
			}
		);

		if(!count($found)) {

			return null;
		}

		return array_shift($found);
	}

	private function read_list(
		string $_filename
	) : void {

		$lines=array_filter(
			file($_filename, FILE_IGNORE_NEW_LINES),
			function(string $_line) {

				return 2===substr_count($_line, "\t");
			}
		);

		//let's not ask what happens when a user has a tab. This is not
		//production stuff!!
		$this->list=array_map(
			function(string $_line) : auth_node {

				list($token, $user, $valid)=explode("\t", $_line);
				return new auth_node($token, $user, new \DateTime($valid));
			},
			$lines,
		);
	}

	private function save_list(
		string $_filename
	) {
		$contents=array_reduce(
			$this->list,
			function(string $_carry, auth_node $_node) {

				return $_carry.=$_node->token."\t".$_node->user."\t".$_node->valid_until->format("Y-m-d H:i:s").PHP_EOL;
			},
			""
		);

		if(false===file_put_contents(
			$_filename,
			$contents
		)) {

			throw new \Exception("could not write auth data");
		}
	}

	private string $filename;
	private array $list;
}
