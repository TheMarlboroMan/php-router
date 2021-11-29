<?php
namespace rimplementation;

/**
*implementation of the request interface, built upon an external request class.
*/

class request implements \srouter\interfaces\request {

	public function         __construct(\request\request $_request) {

		$this->request=$_request;
	}

	public function         get_uri() : string {

		return $this->request->get_uri();
	}

	public function         get_method() : string {

		return $this->request->get_method();
	}

	public function         is_multipart() : bool {

		return $this->request->is_multipart();
	}

	//!Must return the full body of the request.
	public function         get_body() : string {

		if($this->is_multipart()) {

			throw new \Exception("get_body called on multipart request");
		}

		return $this->request->get_body();
	}

	//!Must return true if a part of the body with such name exists.
	public function         part_exists(string $_name) : bool {

		if(!$this->is_multipart()) {

			throw new \Exception("part_exists called on non-multipart request");
		}

		return $this->request->body_name_exists($_name);
	}

	//!Must return the multipart body fragment as a scalar.
	public function         get_part(string $_name) : string {

		if(!$this->is_multipart()) {

			throw new \Exception("part_exists called on non-multipart request");
		}

		return $this->request->get_body_by_name($_name);
	}

	private                 \request\request $request;
}
