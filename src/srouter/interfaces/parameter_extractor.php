<?php
namespace srouter\interfaces;

/***
*describes a class that can take input data and return a parameter value to
*call a controller method.
*/

interface parameter_extractor {

/**
*must extract the value (mixed) for the given argument from the request. Uri
*params are provided in case they are needed.
*/
	public function extract(
		\srouter\argument $_argument,
		\srouter\interfaces\request $_request,
		array $_uri_params
	);
}
