<?php
namespace srouter\interfaces;

//Provides an interface for an HTTP request.
interface request {

	//!Must return the URI.
	public function         get_uri() : string;

	//!Must return the HTTP method.
	public function         get_method() : string;

	//!Must return true if encoded as multipart.
	public function         is_multipart() : bool;

	//!Must return the full body of the request.
	public function         get_body() : string;

	//!Must return true if a part of the body with such name exists.
	public function         part_exists(string $_name) : bool;

	//!Must return the multipart body fragment as a scalar.
	public function         get_part(string $_name) : string;
};
