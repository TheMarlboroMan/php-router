<?php
namespace rimplementation;

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
