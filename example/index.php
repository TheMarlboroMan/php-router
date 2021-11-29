<?php
//read this file and whatever this file points to to learn the ropes for this.
//first off, very important, know that there's an .htaccess file redirecting
//everything here.

//setup autoload. We have setup some dependencies in the external directory,
//these are
//https://github.com/TheMarlboroMan/php-request
//https://github.com/TheMarlboroMan/php-log
//https://github.com/TheMarlboroMan/php-pattern-matcher

//setup an autoloader for the router, at ../src

//let us also setup an autoloader for every part of our project, residing in
//src...

spl_autoload_register(function(string $_class) {

	$filename=__DIR__."/src/external/".str_replace("\\", "/", $_class).".php";
	if(file_exists($filename)) {

		require_once($filename);
		return;
	}

	$filename=__DIR__."/../src/".str_replace("\\", "/", $_class).".php";
	if(file_exists($filename)) {

		require_once($filename);
		return;
	}

	$filename=__DIR__."/src/".str_replace("\\", "/", $_class).".php";
	if(file_exists($filename)) {

		require_once($filename);
		return;
	}


	throw new \Exception("could not load class $_class");
});

//a logger. This router wants a logger and it will conform to the logger
//interface found at https://github.com/TheMarlboroMan/php-log.
$logger=new \log\out_logger(new \log\default_formatter());

//now, let's build stuff in order... first we want to be able to build a request
//compatible object for the router...
$request_factory=new \rimplementation\factory\request_factory();

//next we need to be able to infer a path from an uri and a method
$uri_transformer=new \rimplementation\uri_transformer("/srouter/example/");
$path_mapper=new \rimplementation\path_mapper(__DIR__."/conf/paths.json");

//after that, maybe we want to transform the request body, which may include decyphering.
$in_transformer_factory=new \rimplementation\factory\in_transformer_factory();

//then we may want to perform authorization...
$authorizer_factory=new \rimplementation\factory\authorizer_factory();

//we want to extract arguments from the request.
$argument_extractor_factory=new \rimplementation\factory\argument_extractor_factory();

//next we want to build the controller...
$controller_factory=new \rimplementation\factory\controller_factory();
//TODO:

//with the controller output we may want to transform it ...
//TODO:

//and that should be it: let's build the router with all this stuff...
$router=new \srouter\router(
	$logger,
	$request_factory,
	$uri_transformer,
	$path_mapper,
	$in_transformer_factory,
	$authorizer_factory,
	$argument_extractor_factory,
	$controller_factory
);

//And that's it, we can leave now.
$router->route();
