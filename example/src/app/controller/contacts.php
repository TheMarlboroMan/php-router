<?php
namespace app\controller;

class contacts extends controller {

	public function get(
		?string $_filter_number,
		?string $_filter_name
	) :\srouter\controller_response {

		$rolodex=$this->dc->get_rolodex();

		$contacts=array_filter(
			$rolodex->get_all(),
			function(\rolodex\rolodex_contact $_node) use ($_filter_name, $_filter_number) {

				$name_ok=true;
				if(null !== $_filter_name) {

					$name_ok=false!==strpos($_node->get_name(), $_filter_number);
				}

				$number_ok=true;
				if(null !== $_filter_number) {

					$number_ok=false!==strpos($_node->get_number(), $_filter_number);
				}

				return $name_ok && $number_ok;
			}
		);

		return new \srouter\controller_response(
			200,
			[],
			$contacts
		);
	}

	public function post(
		string $_name,
		string $_phone,
		string $_company
	) :\srouter\controller_response {

		$rolodex=$this->dc->get_rolodex();

		$entry=new \rolodex\rolodex_contact();
		$entry->set_name($_name)
			->set_number($_phone)
			->set_company($_company);

		$entry=$rolodex->create($entry);

		return new \srouter\controller_response(
			201,
			[
				new \srouter\http_response_header("location", "contacts/".$entry->get_id())
			],
			"created"
		);
	}

	public function info(
		int $_id
	) :\srouter\controller_response {

		$rolodex=$this->dc->get_rolodex();
		$entry=$rolodex->find_by_id($_id);

		if(null===$entry) {

			return new \srouter\controller_response(404, [], "not found");
		};

		return new \srouter\controller_response(
			200,
			[],
			$entry
		);
	}

	public function patch(
		int $_id,
		?string $_name,
		?string $_phone,
		?string $_company
	) :\srouter\controller_response {

		$rolodex=$this->dc->get_rolodex();
		$entry=$rolodex->find_by_id($_id);

		if(null!==$_name) {

			$entry->set_name($_name);
		}

		if(null!==$_phone) {

			$entry->set_number($_phone);
		}

		if(null!==$_company) {

			$entry->set_company($_company);
		}

		$rolodex->patch($entry);
		return new \srouter\controller_response(200, [], "patched");
	}

	public function delete(
		int $_id
	) :\srouter\controller_response {

		$rolodex=$this->dc->get_rolodex();
		$entry=$rolodex->find_by_id($_id);

		if(null===$entry) {

			return new \srouter\controller_response(404, [], "not found");
		};

		$rolodex->delete($entry);
		return new \srouter\controller_response(200, [], "deleted");
	}
}
