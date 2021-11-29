<?php
namespace srouter;

class argument {

	public const    type_any=0;
	public const    type_int=1;
	public const    type_bool=2;
	public const    type_string=3;
	public const    type_double=4;
	public const    type_array=5;

	public function __construct(
		string  $_name,
		string  $_source,
		int     $_type,
		bool    $_optional,
		bool    $_notrim,
		$_default=null
	) {

		$this->name=$_name;
		$this->source=$_source;
		$this->type=$_type;
		$this->optional=$_optional;
		if($this->optional) {

			$this->default=$_default;
		}

		$this->notrim=$_notrim;
	}

	public function get_name() : string {

		return $this->name;
	}

	public function get_source() : string {

		return $this->source;
	}

	public function get_type() : int {

		return $this->type;
	}

	public function is_notrim() : bool {

		return $this->notrim;
	}

	public function is_optional() : bool {

		return $this->optional;
	}

	public function get_default() {

		return $this->default;
	}


	private string      $name;
	private string      $source;
	private int         $type;
	private bool        $notrim;
	private bool        $optional;
	private             $default;
}
