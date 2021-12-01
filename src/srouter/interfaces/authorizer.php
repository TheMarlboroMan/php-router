<?php
namespace srouter\interfaces;

/**
*describes a class that can authorize a request. Only the request is passed:
*any other context must be provided by the implenentor at construction time.
*/

interface authorizer {

/**
*must return true if the request is authorized to proceed.
*/
	public function authorize(\srouter\interfaces\request $_request) : bool;
}
