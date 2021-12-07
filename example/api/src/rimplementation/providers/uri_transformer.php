<?php
declare(strict_types=1);
namespace rimplementation\providers;

/**
*I run this stuff at localhost so my complete path may be something
*like /path/to/php-router/example... When I instruct my browser to do "blah"
*there I end up with "http://localhost/php-router/example/blah" and I only want
*the blah part. That's the job of the uri_transformer:
*- splits the uri into parts and takes only the path "php-router/example/blah".
*- removes whatever we pass into the constructor.
*- we got "blah".
*/
class uri_transformer implements \srouter\interfaces\uri_transformer {

	public function __construct(
		string $_leading
	) {

		$this->leading=$_leading;
	}

	public function transform(string $_uri) : string {

		//this transformer will remove
		$pieces=parse_url($_uri);
		return str_replace($this->leading, "", $pieces["path"]);
	}

	private string                          $leading;
}
