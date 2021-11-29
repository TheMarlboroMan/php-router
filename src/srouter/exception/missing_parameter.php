<?php
namespace srouter\exception;

/**
*thrown when a parameter is not optional and has not been provided.
*/

class missing_compulsory_parameter extends exception {

	public function __construct(string $_name) {

		parent::__construct("missing parameter '$_name'");
	}
};
