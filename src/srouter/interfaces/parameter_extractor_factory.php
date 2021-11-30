<?php
namespace srouter\interfaces;

/***
*describes a class that can build argument extractors from a string. Accepts a
*null value to signal it cannot build whatever has been requested.
*/

interface parameter_extractor_factory {

/**
*must build and return a parameter extractor.
*/
	public function build_parameter_extractor(string $_name) : ?parameter_extractor;
}
