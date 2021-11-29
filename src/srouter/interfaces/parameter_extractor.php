<?php
namespace srouter\interfaces;

/***
*describes a class that can take input data and return a parameter value to
*call a controller method.
*/

interface parameter_extractor {

	public function extract(
		\srouter\argument $_argument,
		\srouter\interfaces\request $_request,
		array $_uri_params
	);
}
