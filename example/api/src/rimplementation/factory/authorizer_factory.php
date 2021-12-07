<?php
declare(strict_types=1);
namespace rimplementation\factory;

class authorizer_factory implements \srouter\interfaces\authorizer_factory {

	public function __construct(
		\app\dependency_container $_dc
	) {

		$this->dc=$_dc;
	}

	public function build_authorizer(
		string $_name
	) : ?\srouter\interfaces\authorizer {

		switch($_name) {

			case "logged_in":
				return new \rimplementation\providers\logged_in_auth(
					$this->dc->get_app_logger(),
					$this->dc->get_user_auth()
				);
			break;
		}

		return null;
	}

	private \app\dependency_container $dc;
}
