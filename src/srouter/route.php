<?php
namespace srouter;

/**
*defines a route obtained by a path mapper. Indicates the controller and method
*to invoke, a description of the method arguments, the names of out transformer,
*authorizers, in_transformers, argument extractor and exception handlers and 
*also any parameters obtained directly from the url. Nullable values will
*be accepted by the router. Non nullable ones will surely throw.
*/

class route {

	public function __construct(
		string $_classname,
		string $_methodname,
		array $_arguments, //array of \sroute\argument!!
		string $_out_transformer,
		array $_parameters=[],
		array $_authorizers=[],
		?string $_in_transformer,
		?string $_argument_extractor,
		array $_exception_handlers=[]
	) {

		$this->classname=$_classname;
		$this->methodname=$_methodname;
		$this->arguments=$_arguments;
		$this->uri_parameters=$_parameters;
		$this->in_transformer=$_in_transformer;
		$this->authorizers=$_authorizers;
		$this->argument_extractor=$_argument_extractor;
		$this->out_transformer=$_out_transformer;
		$this->exception_handlers=$_exception_handlers;
	}

/**
*returns a string that represents the classname of the controller so the 
*controller factory can build it. A sensible default is the fully qualified
*name of the controller class.
*/
	public function get_classname() :  string {

		return $this->classname;
	}

/**
*returns the name of the method to call into the controller. It goes without
*saying that the method should be public and non-static.
*/
	public function get_methodname() :  string {

		return $this->methodname;
	}

/**
*returns an array of srouter\argument elements, representing the mapping of
*the controller method's arguments to the request.
*/
	public function get_arguments() : array {

		return $this->arguments;
	}

/**
*returns a string that represents the output transformer so the out transformer
*factory can identify and build it. A sensible default is a fully qualified
*class name.
*/
	public function get_out_transformer() :  string {

		return $this->out_transformer;
	}

/**
*returns an array of uri parameters, which are fed at construction time (the
*path mapper is responsible of locating these when mapping).
*uri parameters are a very particular thing and take the form of variable 
*sections in a uri like /path/user/USERNAME/profile where USERNAME is what
*we are looking for.
*/

	public function get_uri_parameters() :  array {

		return $this->uri_parameters;
	}

/**
*returns a sequential array of strings that represent authorizer names to be
*fed to the respective factory. Once build, these will be run in order before
*executing the controller's method. An empty array can be specified to denote
*that no authorizers are needed.
*/
	public function get_authorizers() :  array {

		return $this->authorizers;
	}

/**
*returns a string representing the input transformer so the corresponding 
*factory can identify and build it. null can be returned to specify that no
*request transformation will be needed (which is a sensible default).
*/
	public function get_in_transformer() :  ?string {

		return $this->in_transformer;
	}

/**
*returns a string representing the argument extractor so the corresponding 
*factory can identify and build it. null can be returned when the controller
*method has no arguments. If the method has arguments an null is given the 
*router is sure to throw.
*/
	public function get_argument_extractor() :  ?string {

		return $this->argument_extractor;
	}

/**
*returns an array of exception handlers for this route. An empty array can be
*returned to specify that the router defaults (or generic ones added after the
*router is built) are acceptable. These handlers control the output when an
*exception is thrown and not caught in the routing process.
*/
	public function get_exception_handlers() : array {

		return $this->exception_handlers;
	}

	private string   $classname;
	private string   $methodname;
	private array    $arguments;
	private string   $out_transformer;
	private array    $uri_parameters=[];
	private array    $authorizers=[];
	private ?string  $in_transformer;
	private ?string  $argument_extractor;
	private array    $exception_handlers;
}
