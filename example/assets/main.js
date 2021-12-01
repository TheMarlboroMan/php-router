class rolodex {

	constructor() {

		this.auth_token=null;
	}

	start() {

		if(!this.is_auth()) {

		}
	}

	is_auth() {

console.log(document.baseURI);

		fetch(document.baseURI+"api/version")
		.then( (_res) => {return _res.json})
		.then( (_res) => {console.log(_res);});
	}

}

function start_rolodex() {

	let r=new rolodex();
	r.start();
}

