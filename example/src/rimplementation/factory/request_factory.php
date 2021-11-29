<?php
namespace rimplementation\factory;

class request_factory implements \srouter\interfaces\request_factory {

	public function build() : \srouter\interfaces\request {

		$raw_request=\request\request_factory::from_apache_request();
		return new \rimplementation\request($raw_request);
	}
}
