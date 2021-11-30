<?php
namespace srouter;

/**
*defines a route obtained by a path mapper. Indicates the controller and method
*to invoke, a description of the method arguments, the names of out transformer,
*authorizers, in_transformers, argument extractor and exception handlers and 
*also any parameters obtained directly from the url. Nullable values will
*be accepted by the router. Non nullable ones will surely crash.
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

	public function get_classname() :  string {

		return $this->classname;
	}

	public function get_methodname() :  string {

		return $this->methodname;
	}

	public function get_arguments() : array {

		return $this->arguments;
	}

	public function get_out_transformer() :  string {

		return $this->out_transformer;
	}

	public function get_uri_parameters() :  array {

		return $this->uri_parameters;
	}

	public function get_authorizers() :  array {

		return $this->authorizers;
	}

	public function get_in_transformer() :  ?string {

		return $this->in_transformer;
	}

	public function get_argument_extractor() :  ?string {

		return $this->argument_extractor;
	}

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
