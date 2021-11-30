<?php
namespace srouter\interfaces;

/***
*describes a class that can build out transformers from a string. Accepts
*returning null values if nothing can be built from the name.
*/

interface out_transformer_factory {

/**
*must build an output transformer factory.
*/
	public function build_out_transformer(string $_name) : ?out_transformer;
}
