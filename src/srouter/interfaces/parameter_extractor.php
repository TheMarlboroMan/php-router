<?php
namespace srouter\interfaces;

/***
*describes a class that can take a request and an array of "parameter" and
*return such parameters in a map.
*/

interface parameter_extractor {

	public function extract(request $_request, array $_argument) : array;
}
