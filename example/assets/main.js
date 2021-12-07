class rolodex {

	constructor() {

		this.auth_token="";

		this.loader=document.getElementById("loader");
		this.login=document.getElementById("login");
		this.main=document.getElementById("main");
		this.main_form=document.getElementById("contact_form");
		this.btn_new=document.getElementById("btn_new");
		this.form_id=0;

		this.setup_login_form();
		this.setup_new_button();
		this.setup_main_form();
	}

	start() {

		this.is_auth()
		.then( (_res) => {

			if(!_res) {

				this.show_as_logged_out();
			}
		});
	}

	is_auth() {

		return new Promise( (_accept, _reject) => {

			return this.fetch(
				"version", "GET", [200, 401]
			)
			.then( (_res) => {

				_accept(200===_res.status);
			})
			.catch( (_err) => {

				alert("could not check status: "+_err.message);
			});
		});
	}

	show_as_logged_out() {

		this.main.classList.add("hidden");
		this.loader.classList.add("hidden");
		this.login.classList.remove("hidden");
	}

	show_as_logged_in() {

		this.main.classList.remove("hidden");
		this.loader.classList.add("hidden");
		this.login.classList.add("hidden");

		this.retrieve_contacts();
	}

	retrieve_contacts() {

		return this.fetch(
			"contacts", "GET", [200, 401]
		)
		.then((_res) => {

			if(200!==_res.status) {

				this.show_as_logged_out();
				return null;
			}

			return _res.json();
		})
		.then((_res) => {

			if(null===_res) {

				return;
			}

			this.main.querySelector("#contact_count").innerHTML=_res.length;
			let ul=this.main.querySelector("ul");
			while(ul.childNodes.length) {

				ul.firstChild.remove();
			}

			let template=this.main.querySelector("#contact_row");
			_res.forEach( (_item) => {

				template.content.querySelector("b").textContent=_item.name;
				template.content.querySelector("i").textContent=_item.number;
				template.content.querySelector("span").textContent=_item.company;
				template.content.querySelector("button[action='edit']").dataset.id=_item.id;
				template.content.querySelector("button[action='delete']").dataset.id=_item.id;

				let clone=document.importNode(template.content, true);

				ul.appendChild(clone);
			});

			//setup all buttons:
			Array.prototype.slice.call(
				ul.querySelectorAll("button[action='delete']")
			).forEach( (_item) => {

				_item.addEventListener("click", () => {this.confirm_delete(_item);}, true);
			});

			//setup all buttons:
			Array.prototype.slice.call(
				ul.querySelectorAll("button[action='edit']")
			).forEach( (_item) => {

				_item.addEventListener("click", () => {this.setup_edit(_item);}, true);
			});
		})
		.catch( (_err) => {

			alert("could not load rolodex: "+_err.message);
		});
	}

	setup_edit(_btn) {

		this.fetch("contacts/"+_btn.dataset.id, "GET", [200, 401])
		.then( (_res) => {

			if(_res.status !== 200) {

				this.show_as_logged_out();
				return;
			}

			return _res.json();
		})
		.then( (_res) => {

			this.main_form.name.value=_res.name;
			this.main_form.number.value=_res.number;
			this.main_form.company.value=_res.company;
			this.form_id=_btn.dataset.id;
			this.main_form.classList.remove("hidden");
		})
		.catch( (_err) => {

			alert("could not delete contact: "+_err.message);
		});
	}

	confirm_delete(_btn) {

		if(!confirm("delete contact?")) {

			return;
		}

		this.fetch("contacts/"+_btn.dataset.id, "DELETE", [200, 401])
		.then( (_res) => {

			if(_res.status !== 200) {

				this.show_as_logged_out();
				return;
			}

			return this.retrieve_contacts();
		})
		.catch( (_err) => {

			alert("could not delete contact: "+_err.message);
		});
	}

	setup_login_form() {

		let form=this.login.querySelector("form");
		setup_form_submit(form, null, () => {this.perform_login(form.btn_submit, form);})
	}

	setup_main_form() {

		setup_form_submit(this.main_form, null, () => {this.send_main_form(this.main_form.btn_submit, this.main_form);})
	}

	setup_new_button() {

		this.btn_new.addEventListener("click", () => {this.setup_form_new();}, true);
	}

	setup_form_new() {

		this.form_id=0;
		this.main_form.reset();
		this.main_form.classList.remove("hidden");
	}

	perform_login(_btn, _form) {

		_btn.disabled="disabled";

		let payload={
			username: _form.username.value.trim(),
			pass: _form.password.value.trim()
		};

		this.fetch("login", "POST", [200, 400], payload)
		.then( (_res) => {

			if(200!==_res.status) {

				_res.text().then( (_txt) => {

					throw new Error(_txt);
				})
				.catch( (_res) => {throw _res;});
			}

			this.auth_token=_res.headers.get("auth-token");
			this.show_as_logged_in();
		})
		.catch( (_err) => {

			alert("could not perform login: "+_err.message);
		})
		.finally( () => {

			_btn.disabled=false;
		})
	}

	send_main_form() {

		if(this.form_id===0) {

			return this.send_new();
		}

		return this.send_edit();
	}

	send_new() {

		let payload={
			name: this.main_form.name.value.trim(),
			phone: this.main_form.number.value.trim(),
			company: this.main_form.company.value.trim(),
		}

		return this.fetch("contacts", "POST", [201, 400, 401], payload)
		.then( (_res) => {

			if(401===_res.status) {

				this.show_as_logged_out();
				return;
			}

			if(400===_res.status) {

				alert("bad request, try again with different input");
				return;
			}

			this.main_form.reset();
			return this.retrieve_contacts();
		})
		.catch( (_err) => {

			alert("could not create contact: "+_err.message);
		});
	}

	send_edit() {

		let payload={
			name: this.main_form.name.value.trim(),
			phone: this.main_form.number.value.trim(),
			company: this.main_form.company.value.trim(),
		}

		return this.fetch("contacts/"+this.form_id, "PATCH", [200, 400, 401], payload)
		.then( (_res) => {

			if(401===_res.status) {

				this.show_as_logged_out();
				return;
			}

			if(400===_res.status) {

				alert("bad request, try again with different input");
				return;
			}

			this.main_form.reset();
			this.form_id=0;
			return this.retrieve_contacts();
		})
		.catch( (_err) => {

			alert("could not update contact: "+_err.message);
		});
	}

	fetch(_endpoint, _method, _expected_status, _payload) {

		let parameters={
			method:_method,
			headers:{
				"content-type" : "application/json",
				"rolodex-auth-token" : this.auth_token
			}
		};

		if(undefined !== _payload) {

			parameters.body = JSON.stringify(_payload);
		}

		return fetch(
			document.baseURI+"api/"+_endpoint,
			parameters
		)
		.then( (_res) => {

			if(-1==_expected_status.indexOf(_res.status)) {

				throw new Error(_res.text());
			}

			return _res;
		});
	}
}

function setup_form_submit(_form, _btn, _fn) {

	_form.addEventListener(
		"submit",
		(_event) => {

			_event.preventDefault();
			_fn();
			return false;
		},
		true
	);

	if(!_btn) {

		return;
	}

	_btn.addEventListener('click', () => {

		let btn=_form.querySelector('button[data-formrole="submit"]');

			if(!btn) {

				return;
			}

			btn.click();
		},
		true
	);
}

function start_rolodex() {

	let r=new rolodex();
	r.start();
}

