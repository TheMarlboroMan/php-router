<?php
declare(strict_types=1);
//read this file and whatever this file points to to learn the ropes for this.
//first off, very important, know that there's an .htaccess file redirecting
//everything here.

//setup autoload. We have setup some dependencies in the external directory,
//there's a lone file called what-goes-here telling you what to do.
//setup an autoloader for the router, at ../src
//let us also setup an autoloader for every part of our project, residing in
//src...

spl_autoload_register(function(string $_class) {

	$filename=__DIR__."/src/external/".str_replace("\\", "/", $_class).".php";
	if(file_exists($filename)) {

		require_once($filename);
		return;
	}

	$filename=__DIR__."/../../src/".str_replace("\\", "/", $_class).".php";
	if(file_exists($filename)) {

		require_once($filename);
		return;
	}

	$filename=__DIR__."/src/".str_replace("\\", "/", $_class).".php";
	if(file_exists($filename)) {

		require_once($filename);
		return;
	}


	throw new \Exception("could not load class $_class from ".__DIR__);
});

try {

	//This part has nothing to do with the router, it is our dependency container
	//that can be injected into our router implementations. A real world example
	//would have config files and stuff, but this will suffice.
	$dc=new \app\dependency_container(__DIR__);

	//a logger. This router wants a logger and it will conform to the logger
	//interface found at https://github.com/TheMarlboroMan/php-log.
	$logger=new \log\file_logger(
		new \log\default_formatter(),
		__DIR__."/log/router.log"
	);

	//now, let's build stuff in order... first we want to be able to build a request
	//compatible object for the router...
	$request_factory=new \rimplementation\factory\request_factory();

	//next we need to be able to infer a path from an uri and a method
	$uri_transformer=new \rimplementation\providers\uri_transformer("/php-router/example/api/");
	$path_mapper=new \rimplementation\providers\path_mapper(__DIR__."/conf/paths.json");

	//after that, maybe we want to transform the request body, which may include decyphering.
	$in_transformer_factory=new \rimplementation\factory\in_transformer_factory();

	//then we may want to perform authorization... We inject our stuff in.
	$authorizer_factory=new \rimplementation\factory\authorizer_factory($dc);

	//we want to extract arguments from the request and map their names...
	$argument_extractor_factory=new \rimplementation\factory\argument_extractor_factory();
	$parameter_mapper_factory=new \rimplementation\factory\parameter_mapper_factory();

	//next we'll want to build the controllers, so we give the factory what it will need...
	$controller_factory=new \rimplementation\factory\controller_factory($dc);

	//with the controller output we may want to transform it ...
	$out_transformer_factory=new \rimplementation\factory\out_transformer_factory();

	//and maybe something will fail so...
	$exception_handler_factory=new \rimplementation\factory\exception_handler_factory();

	//and that should be it: let's build the router with all this stuff...
	$router=new \srouter\router(
		$logger,
		$request_factory,
		$uri_transformer,
		$path_mapper,
		$in_transformer_factory,
		$authorizer_factory,
		$argument_extractor_factory,
		$parameter_mapper_factory,
		$controller_factory,
		$out_transformer_factory,
		$exception_handler_factory
	);

	//And that's it, we can leave now...
	$router->route()->out();
}
catch(\Exception $e) {

	$logger->error("something went very wrong: ".$e->getMessage());
	header("HTTP/1.1 500 internal server error");
	echo "internal server error".PHP_EOL;
	exit(1);
}
