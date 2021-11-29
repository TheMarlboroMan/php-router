<?php
namespace srouter\interfaces;

/***
*describes a class that can build argument extractors from a string. Accepts a
*null value to signal it cannot build whatever has been requested.
*/

interface parameter_extractor_factory {

	public function build(string $_name) : ?parameter_extractor;
}
