<?php
namespace srouter\interfaces;

/**
*describes a class that can authorize a request.
*/

interface authorizer {

	public function authorize(request $_request) : bool;
}
