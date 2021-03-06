<?php
declare(strict_types=1);
namespace rimplementation\factory;

class request_factory implements \srouter\interfaces\request_factory {

/**
*nothing special.
*/
	public function build_request() : \srouter\interfaces\request {

		$raw_request=\request\request_factory::from_apache_request();
		return new \rimplementation\request($raw_request);
	}
}
