<?php
namespace srouter\interfaces;

/***
*describes a class that can build in transformers from a string. Accepts
*returning null if nothing can be built with the given name.
*/

interface in_transformer_factory {

	public function build_in_transformer(string $_name) : ?in_transformer;
}
