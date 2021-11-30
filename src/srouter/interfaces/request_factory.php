<?php
namespace srouter\interfaces;

/**
*defines a basic interface for the request factory.
*/

interface request_factory {

/**
*must build a request compatible object from whichever sources.
*/

	public function build_request() : request;

};
