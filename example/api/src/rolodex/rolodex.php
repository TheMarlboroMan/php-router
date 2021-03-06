<?php
namespace rolodex;

class rolodex {

/**
*class constructor
*/

	public function __construct(
		string $_filename
	) {

		$this->filename=$_filename;

		$this->setup($_filename);
		$this->read($_filename);
	}

/**
*retrieves all contacts in the rolodex file.
*/

	public function get_all() : array {

		return $this->list;
	}

/**
*creates a new entry in the rolodex
*/

	public function create(
		\rolodex\rolodex_contact $_contact
	) : \rolodex\rolodex_contact {

		if(0!==$_contact->get_id()) {

			throw new \rolodex\exception("rolodex contact cannot have id set when creating");
		}

		$_contact->set_id($this->next_id++);
		$this->list[]=clone $_contact;
		$this->write($this->filename);
		return $_contact;
	}

/**
*returns a copy of the contact by id or null if no contact by that id can be
*found
*/

	public function find_by_id(
		int $_id
	) : ?\rolodex\rolodex_contact {

		$result=array_filter(
			$this->list, 
			function(\rolodex\rolodex_contact $_contact) use ($_id) : bool {
			
				return $_id===$_contact->get_id();
			}
		);

		if(!count($result)) {

			return null;
		}

		return array_shift($result);
	}

/**
*updates a rolodex contact
*/

	public function patch(
		\rolodex\rolodex_contact $_contact
	) : void {

		$contact=$this->find_by_id($_contact->get_id());
		if(null===$contact) {

			throw new \rolodex\exception("cannot find contact when patching");
		}

		$this->delete($contact); //yes, this will write to disk.
		$this->list[]=$_contact; //yeep, not sorted, not production quality.

		$this->write($this->filename); //yes, this will write to disk again.
	}

/**
*removes a rolodex contact
*/

	public function delete(
		\rolodex\rolodex_contact $_contact
	) : void {

		$contact=$this->find_by_id($_contact->get_id());
		if(null===$contact) {

			throw new \rolodex\exception("cannot find contact when deleting");
		}

		$this->list=array_filter(
			$this->list,
			function(\rolodex\rolodex_contact $_item) use ($contact) : bool {

				return $_item->get_id() !== $contact->get_id();
			}
		);

		$this->write($this->filename);
	}

	private function setup(
		string $_filename
	) : void {

		try {
			if(!file_exists($_filename)) {

				$this->write($_filename);
			}
		}
		catch(\rolodex\exception $e) {

			throw new \rolodex\exception("could not setup rolodex file: ".$e->getMessage());
		}
	}

	private function read(
		string $_filename
	) : void {

		//this flag is my new best friend, apparently.
		$contents=file($_filename, FILE_IGNORE_NEW_LINES);
		if(false===$contents) {

			throw new \rolodex\exception("could not read rolodex file");
		}

		//the format will be very specific: first line, next id. The rest, 
		//rolodex contacts.

		$number_line=array_shift($contents);
		if(!ctype_digit($number_line)) {

			throw new \rolodex\exception("bad rolodex file, first line is expected to be an integer");
		}
		$this->next_id=(int)$number_line;

		while(count($contents)) {

			$line=array_shift($contents);

			$tabcount=substr_count($line, "\t");
			if(3 !== $tabcount) {

				throw new \rolodex\exception("bad rolodex file, contact lines are supposed to have 3 tabs, got $tabcount");
			}

			list($id, $name, $number, $company)=explode("\t", $line);
			$contact=new \rolodex\rolodex_contact();
			$contact->set_id($id)
					->set_name($name)
					->set_number($number)
					->set_company($company);
			
			$this->list[]=$contact;
		}
	}

	private function write(
		string $_filename
	) : void {

		$contents=(string)$this->next_id.PHP_EOL;
		foreach($this->list as $contact) {

			//again, let us not think of what happens if there's a tab in the contact data.
			$contents.=$contact->get_id()."\t".$contact->get_name()."\t".$contact->get_number()."\t".$contact->get_company().PHP_EOL;
		}

		if(false===file_put_contents($_filename, $contents)) {

			throw new \rolodex\exception("failed to write file");
		}
	}

	private string  $filename;
	private int     $next_id=1;
	private array   $list=[];
};
