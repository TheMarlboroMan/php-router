<?php
namespace srouter;

class response {

	public function     __construct(
		int $_code,
		array $_headers,
		string $_body
	) {

		$this->status_code=$_code;
		$this->headers=$_headers;
		$this->body=$_body;
	}

	private int         $status_code;
	private array       $headers;
	private string      $body;
}
