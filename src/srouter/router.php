<?php
namespace srouter;

/**
*the router. You would expect this to be documented, right?
*/

class router {

	private const log_module="srouter";

	public function __construct(
		\log\logger_interface               $_logger,
		interfaces\request_factory          $_request_factory,
		interfaces\uri_transformer          $_uri_transformer,
		interfaces\path_mapper              $_path_mapper,
		interfaces\in_transformer_factory   $_in_transformer_factory,
		interfaces\authorizer_factory       $_authorizer_factory,
		interfaces\parameter_extractor_factory $_parameter_extractor_factory,
		interfaces\controller_factory       $_controller_factory,
		interfaces\out_transformer_factory  $_out_transformer_factory
	) {

		$this->logger=$_logger;
		$this->path_mapper=$_path_mapper;
		$this->uri_transformer=$_uri_transformer;
		$this->request_factory=$_request_factory;
		$this->in_transformer_factory=$_in_transformer_factory;
		$this->authorizer_factory=$_authorizer_factory;
		$this->parameter_extractor_factory=$_parameter_extractor_factory;
		$this->controller_factory=$_controller_factory;
		$this->out_transformer_factory=$_out_transformer_factory;
	}

	public function                         route() : \srouter\http_response {

		try {

			$this->logger->info("will build the request", self::log_module);
			$request=$this->request_factory->build_request();

			$transformed_uri=$this->uri_transformer->transform($request->get_uri());
			$this->logger->info($request->get_uri()." becomes $transformed_uri", self::log_module);

			$this->logger->info("will map ".$request->get_method()." : $transformed_uri to a route", self::log_module);
			$route=$this->path_mapper->map(
				$request->get_method(),
				$transformed_uri
			);

			if(null===$route) {

				$this->logger->notice("failed to map, will throw", self::log_module);
				throw new \srouter\exception\not_found();
			}

			if(null!==$route->get_in_transformer()) {

				$this->logger->info("will perform input transformation with '".$route->get_in_transformer()."'", self::log_module);
				$request=$this->transform_request($route->get_in_transformer(), $request);
			}

			if(count($route->get_authorizers())) {

				$this->logger->info("will perform authorization", self::log_module);
				$this->authorize($route->get_authorizers(), $request);
			}

			$arguments=[];
			if(null!==$route->get_argument_extractor()) {

				$this->logger->notice("will extract arguments with '".$route->get_argument_extractor()."'", self::log_module);
				$arguments=$this->extract_arguments(
					$route->get_argument_extractor(),
					$route->get_arguments(),
					$request,
					$route->get_uri_parameters()
				);
			}

			//build controller...
			$controller=$this->build_controller($route->get_classname());
			if(!method_exists($controller, $route->get_methodname())) {

				$this->logger->warning("could not find method '".$route->get_methodname()."' in '".$route->get_classname()."'", self::log_module);
				throw new \srouter\exception\bad_dependency("method does not exist for controller");
			}

			//finally, sort arguments as they appear on the controller....
			$sorted_arguments=[];
			$reflector=new \ReflectionMethod($controller, $route->get_methodname());
			foreach($reflector->getParameters() as $parameter) {

				$this->logger->info("will attempt to find value for argument '".$parameter->getName()."'", self::log_module);

				//i always use underscores for parameters...
				$name=substr($parameter->getName(), 1);
				if(array_key_exists($name, $arguments)) {

					$sorted_arguments[]=$arguments[$name];
					continue;
				}

				$name=$parameter->getName();
				if(array_key_exists($name, $arguments)) {

					$sorted_arguments[]=$arguments[$name];
					continue;
				}

				$this->logger->info("cannot find parameter for '".$parameter->getName()."'", self::log_module);
				throw new \srouter\exception\argument_mapping("cannot find parameter for argument '".$parameter->getName()."'");
			}

			if(count($reflector->getParameters()) !== count($sorted_arguments)) {

				throw new \srouter\exception\bad_dependency("no parameters could be extracted when the prototype demands them");
			}

			//execute...
			$methodname=$route->get_methodname();
			$this->logger->info("will perform execution now...", self::log_module);
			$result=$controller->$methodname(...$sorted_arguments);

			if(! ($result instanceof controller_response)) {

				$this->logger->warning("returned value is not a controller_response!", self::log_module);
				throw new \srouter\exception\bad_response("result of '".$route->get_methodname()."' in '".$route->get_classname()."' is not a controller_response");
			}

			//transform output.
			$this->logger->info("will transform and return output...", self::log_module);
			return $this->transform_out($result, $route->get_out_transformer());
		}
		catch(\Exception $e) {

			$this->handle_error($e);
		}
		catch(\Error $e) {

			$this->handle_error($e);
		}
	}

	private function                        build_controller(
		string $_classname
	) {

		$controller=$this->controller_factory->build_controller($_classname);

		if(null===$controller) {

			throw new \srouter\exception\bad_dependency("could not build controller '$_classname'");
		}

		if(!is_object($controller)) {

			throw new \srouter\exception\bad_dependency("controller '$_classname' must be an object");
		}

		return $controller;
	}

	private function                        transform_request(
		string $_key,
		interfaces\request $_request
	) : interfaces\request {

		$factory=$this->in_transformer_factory->build_in_transformer($_key);
		if(null===$factory) {

			throw new \srouter\exception\bad_dependency("fatal error, cannot build request transformer");
		}

		return $factory->transform($_request);
	}

	private function                        authorize(
		array $_authorizers,
		interfaces\request $_request
	) {

		foreach($_authorizers as $key) {

			$this->logger->info("will authorize using $key", self::log_module);
			$authorizer=$this->authorizer_factory->build_authorizer($key);

 			if(null===$authorizer) {

				throw new \srouter\exception\bad_dependency("fatal error, cannot build authorizer '$key'");
			}

			if(!$authorizer->authorize($_request)) {

				$this->logger->notice("$key authorization failed", self::log_module);
				throw new \srouter\exception\unauthorized("failed to authorize using $key");
			}
		}
	}

	private function extract_arguments(
		string $_argument_extractor,
		array $_arguments,
		interfaces\request $_request,
		array $_uri_parameters
	) {

		$extractor=$this->parameter_extractor_factory->build_parameter_extractor($_argument_extractor);
		if(null===$extractor) {

			throw new \srouter\exception\bad_dependency("cannot build argument extractor '$_argument_extractor'");
		}

		$result=[];

		foreach($_arguments as $argument) {

			$result[$argument->get_name()]=$extractor->extract(
				$argument,
				$_request,
				$_uri_parameters
			);
		}

		return $result;
	}

	private function transform_out(
		controller_response $_result,
		string $_out_transformer
	) : \srouter\http_response {

		$transformer=$this->out_transformer_factory->build_out_transformer($_out_transformer);
		if(null===$transformer) {

			throw new \srouter\exception\bad_dependency("cannot build out transformer '$_out_transformer''");
		}

		return $transformer->transform($_result);
	}

	private function handle_error(
		$_exception
	) {

		//TODO: here is where the hot is

			$this->logger->error($e->getMessage(), self::log_module);
			//TODO: See about that...
			//TODO: Sure, how is this shit transformed??
			return new \srouter\http_response(500, [], "ERROR");
			$this->logger->error("(at ".$e->getFile().":".$e->getLine().") ".$e->getMessage(), self::log_module);
			//TODO: See about that...
			//TODO: Sure, how is this shit transformed??
			return new \srouter\http_response(500, [], "ERROR");

	}

	private \log\logger_interface               $logger;
	private interfaces\path_mapper              $path_mapper;
	private interfaces\uri_transformer          $uri_transformer;
	private interfaces\request_factory          $request_factory;
	private interfaces\in_transformer_factory   $in_transformer_factory;
	private interfaces\authorizer_factory       $authorizer_factory;
	private interfaces\parameter_extractor_factory $parameter_extractor_factory;
	private interfaces\controller_factory       $controller_factory;
	private interfaces\out_transformer_factory  $out_transformer_factory;
}
