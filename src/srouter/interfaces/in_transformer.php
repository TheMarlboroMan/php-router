<?php
namespace srouter\interfaces;

/**
*describes a class that can take the request and transform it into another.
*/

interface in_transformer {

	public function transform(request $_request) : request;
}
