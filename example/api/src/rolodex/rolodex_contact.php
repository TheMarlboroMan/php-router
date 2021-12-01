<?php
namespace rolodex;

class rolodex_contact implements \jsonSerializable {

	public function get_id() : int {
	
		return $this->id;
	}

	public function get_name() : string {

		return $this->name;
	}

	public function get_number() : string {

		return $this->number;
	}

	public function get_company() : string {

		return $this->company;
	}

	public function set_id(int $_id) : rolodex_contact {

		$this->id=$_id;
		return $this;
	}

	public function set_name(string $_name) : rolodex_contact {

		$this->name=$_name;
		return $this;
	}

	public function set_number(string $_number) : rolodex_contact {

		$this->number=$_number;
		return $this;
	}

	public function set_company(string $_company) : rolodex_contact {

		$this->company=$_company;
		return $this;
	}

	public function jsonSerialize() : array {

		return [
			"id" => $this->id,
			"name" => $this->name,
			"number" => $this->number,
			"company" => $this->company
		];
	}

	private int $id=0;
	private string $name;
	private string $number;
	private string $company;
};
