<?php
namespace srouter;

/**
*intermediate response class that can be sent to out transformers so they
*become http responses. unlike the http response, the body can still be
*anything.
*/

class controller_response {

	public function     __construct(
		int $_code,
		array $_headers, //of http_response_header type!
		$_body
	) {

		$this->status_code=$_code;
		$this->headers=$_headers;
		$this->body=$_body;
	}

	public function     get_status_code() : string{

		return $this->status_code;
	}

	public function     get_headers() : array {

		return $this->headers;
	}

	public function     get_body() {

		return $this->body;
	}

	private int         $status_code;
	private array       $headers;
	private             $body;
}
