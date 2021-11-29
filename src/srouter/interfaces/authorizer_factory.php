<?php
namespace srouter\interfaces;

/***
*describes a class that can build authorizer from a string. Accepts returning a
*new value if nothing can be built with the given name.
*/

interface authorizer_factory {

	public function build(string $_name) : ?authorizer;
}
