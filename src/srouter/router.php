<?php
namespace srouter;

class router {

	private const log_module="srouter";

	public function __construct(
		\log\logger_interface               $_logger,
		interfaces\request_factory          $_request_factory,
		interfaces\uri_transformer          $_uri_transformer,
		interfaces\path_mapper              $_path_mapper,
		interfaces\in_transformer_factory   $_in_transformer_factory,
		interfaces\authorizer_factory       $_authorizer_factory,
		interfaces\argument_extractor_factory $_argument_extractor_factory,
		interfaces\controller_factory       $_controller_factory
	) {

		$this->logger=$_logger;
		$this->path_mapper=$_path_mapper;
		$this->uri_transformer=$_uri_transformer;
		$this->request_factory=$_request_factory;
		$this->in_transformer_factory=$_in_transformer_factory;
		$this->authorizer_factory=$_authorizer_factory;
		$this->argument_extractor_factory=$_argument_extractor_factory;
		$this->controller_factory=$_controller_factory;
	}

	public function                         route() {

		try {

			$this->logger->info("will build the request", self::log_module);
			$request=$this->request_factory->build();

			$transformed_uri=$this->uri_transformer->transform($request->get_uri());
			$this->logger->info($request->get_uri()." becomes $transformed_uri", self::log_module);

			$this->logger->info("will map ".$request->get_method()." : $transformed_uri to a route", self::log_module);
			$route=$this->path_mapper->map(
				$request->get_method(),
				$transformed_uri
			);

			if(null===$route) {

				$this->logger->notice("failed to map, will throw", self::log_module);
				//TODO: Throw shit
				die("WILL THROW!");
			}

			if(null!==$route->get_in_transformer()) {

				$this->logger->info("will perform input transformation with '".$route->get_in_transformer()."'", self::log_module);
				$request=$this->transform_request($route->get_in_transformer(), $request);
			}

			var_dump($route);
			die();

			if(count($route->get_authorizers())) {

				$this->logger->info("will perform authorization", self::log_module);
				$this->authorize($route->get_authorizers(), $request);
			}

			$arguments=[];
			if(null!==$route->get_argument_extractor()) {

				$this->logger->notice("will extract arguments with '".$route->get_argument_extractor()."'", self::log_module);
				$arguments=$this->extract_arguments($route->get_argument_extractor(), $request, $route->uri_parameters);
			}

			//build controller...
			$controller=$this->build_controller($route->get_classname());
			if(!method_exists($controller, $route->get_methodname())) {

				$this->logger->warning("could not find method '".$route->get_methodname()."' in '".$route->get_classname()."'", self::log_module);
				throw new \Exception("method does not exist for controller");
			}

			//ensure the parameters are fully mapped...
			$reflector=new \ReflectionMethod($controller, $route->get_methodname());
			$parameters=$reflector->getParameters();

			if(count($arguments) != count($parameters)) {

				$this->logger->warning("method arguments don't match signature on '".$route->get_methodname()."' in '".$route->get_classname()."'", self::log_module);
				throw new \Exception("argument mismatch");
			}

			//map parameters, in a sexy way, so they can be in any order...
			//TODO:

			//TODO: Make sure there are no parameters missing (by name, in this case)

			//execute...
			$methodname=$route->get_methodname();
			$this->logger->info("will perform execution now...", self::log_module);
			$result=$controller->$methodname(...$arguments);
			if(! ($result instanceof response)) {

				$this->logger->warning("returned value is not a response!", self::log_module);
				throw new \Exception("result of '".$route->get_methodname()."' in '".$route->get_classname()."' is not a response");
			}

			//transform out...
			//TODO:

			var_dump($result);
			die();



		}
		catch(\Exception $e) {

			//TODO: what about interrupts?
			die("ERROR:".$e->getMessage());
		}
	}

	private function                        build_controller(
		string $_classname
	) {

		$controller=$this->controller_factory->build($_classname);

		if(null===$controller) {

			throw new \Exception("could not build controller '$_classname'");
		}

		if(!is_object($controller)) {

			throw new \Exception("controller '$_classname' must be an object");
		}

		return $controller;
	}

	private function                        transform_request(
		string $_key,
		interfaces\request $_request
	) : interfaces\request {

		$factory=$this->in_transformer_factory->build($_key);
		if(null===$factory) {

			//TODO: Throw fatal.
			throw new \Exception("fatal error, cannot build request transformer");
		}

		return $factory->transform($_request);
	}

	private function                        authorize(
		array $_authorizers,
		interfaces\request $_request
	) {

		foreach($_authorizers as $key) {

			$this->logger->info("will authorize using $key", self::log_module);
			$authorizer=$this->authorizer_factory->build($key);

			if(null===$authorizer) {
				//TODO: Throw fatal.
				throw new \Exception("fatal error, cannot build authorizer");
			}

			//TODO: What if there's no authorizer from the factory? how to signal?
			if(!$authorizer->authorize($_request)) {

				$this->logger->notice("$key authorization failed", self::log_module);
				//TODO: Should be another exception.
				throw new \Exception("failed to authorize using $key");
			}
		}
	}

	private function extract_arguments(
		string $_argument_extractor,
		interfaces\request $_request,
		array $_uri_parameters
	) {

		$extractor=$this->argument_extractor_factory->build($_argument_extractor);
		if(null===$extractor) {

			throw new \Exception("cannot build argument extractor");
		}

		//TODO:
		//What this must do is... grab argument list from the path prototype.
		//Use this argument list on the request!

		//TODO: have a toolset that can be used like "from_query_string",
		//"from_urlencoded", "from_json", "from_uri" and then recombined by the
		//user in any way.

		return $extractor->extract($_request, $_uri_parameters);
	}

	private \log\logger_interface               $logger;
	private interfaces\path_mapper              $path_mapper;
	private interfaces\uri_transformer          $uri_transformer;
	private interfaces\request_factory          $request_factory;
	private interfaces\in_transformer_factory   $in_transformer_factory;
	private interfaces\authorizer_factory       $authorizer_factory;
	private interfaces\argument_extractor_factory $argument_extractor_factory;
	private interfaces\controller_factory       $controller_factory;
}
