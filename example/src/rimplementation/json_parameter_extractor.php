<?php
namespace rimplementation;

/**
*in this example everything comes in as json, so this will be easy.
*/
class json_parameter_extractor implements \srouter\interfaces\parameter_extractor {

	public function __construct() {

		$this->parameter_maker=new \srouter\parameter_maker();
	}

	public function extract(
		\srouter\argument $_argument,
		\srouter\interfaces\request $_request,
		array $_uri_params
	) {

		$name=$_argument->get_name();

		//if the argument comes in the URI, just be done with that.
		if($_argument->get_source()==="uri") {

			$value=$this->parameter_maker->find_uri_parameter($name, $_uri_params);
			return $this->parameter_maker->make_param($value, $_argument);
		}

		//same if it comes from the query string...
		if($_argument->get_source()==="query") {

			$value=$this->parameter_maker->find_query_parameter($name, $_request);
			return $this->parameter_maker->make_param($value, $_argument);
		}

		//else, attempt to locate it in the body.
		if($_argument->get_source()==="json") {

			$value=null;
			if(null===$this->json_doc) {

				if($_request->is_multipart()) {

					throw new \Exception("json parameter extractor refuses multipart requests");
				}

				$this->json_doc=json_decode($_request->get_body());
				if(JSON_ERROR_NONE !== json_last_error()) {

					throw new \Exception("could not decode json request body: ".json_last_error_msg());
				}
			}

			$value=property_exists($this->json_doc, $name)
				? $this->json_doc->$name
				: null;

			return $this->parameter_maker->make_param($value, $_argument);
		}

		throw new \Exception("invalid argument type for json parameter extractor");

	}

	private \srouter\parameter_maker $parameter_maker;
	private ?\stdClass               $json_doc=null;

}
