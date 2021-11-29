<?php
namespace srouter;

/**
*defines a route obtained by a path mapper, indicating which class must be
*instantiated, which method to call and what parameters could be inferred from
*the path itself. These parameters are an array of "uri_parameter".
*The other things are keys to be passed to the respective factories. A value
*of null (when possible) will mean "don't even bother in building this part".
*/

class route {

	public function __construct(
		string $_classname,
		string $_methodname,
		array $_arguments,
		string $_out_transformer,
		array $_parameters=[],
		array $_authorizers=[],
		?string $_in_transformer,
		?string $_argument_extractor
	) {

		$this->classname=$_classname;
		$this->methodname=$_methodname;
		$this->arguments=$_arguments;
		$this->uri_parameters=$_parameters;
		$this->in_transformer=$_in_transformer;
		$this->authorizers=$_authorizers;
		$this->argument_extractor=$_argument_extractor;
		$this->out_transformer=$_out_transformer;
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


	private string   $classname;
	private string   $methodname;
	private array    $arguments;
	private string   $out_transformer;
	private array    $uri_parameters=[];
	private array    $authorizers=[];
	private ?string  $in_transformer;
	private ?string  $argument_extractor;
}
