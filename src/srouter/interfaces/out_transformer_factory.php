<?php
namespace srouter\interfaces;

/***
*describes a class that can build out transformers from a string. Accepts
*returning new values if nothing can be built from the name.
*/

interface out_transformer_factory {

	public function build(string $_name) : ?out_transformer;
}
