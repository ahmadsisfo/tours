var new_Registry = {
	data : {},

	get : function (key) {
		if (isset(this.data[key]))
			return this.data[key];
		else 
			return false;
	},
	
	set : function (key, value) {
		this.data[key] = value;
	},
	
	has : function (key) {
		return isset(this.data);
	}
}

var new_Config = {
	data : {},
	
	get : function (key) {
		if (isset(this.data[key]))
			return this.data[key];
		else 
			return false;
	},
	
	set : function (key, value) {
		this.data[key] = value;
	},
	
	has : function (key) {
		return isset(this.data);
	}
}

var new_Loader = {
	registry : '',
	
	init : function (registry) {
		this.registry = registry;
	},
	
	model : function (model) {
		if (model != undefined) {
			var classes = 'M'+ model.replace('/','');
			$.getScript(HTTP_MODEL + model + '.js')
			.done(function(script, textStatus){
				classes = 'new_' + classes;
				new_Loader.registry.set('M' + model.replace('/',''), classes);
			})
			.fail(function(jqxhr, settings, exception){
				console.log('file : ' +model+ '.js tidak ditemukan');
			});			
		} 
	}
}

var new_Url = {
	domain : '',
	
	link : function(route, args) {
		var url = HTTP_SERVER;
		url += '?way=' . route;
		if(args) {
			args.trim('&',args);
			url += args.replace('&','&amp;')
		}
		return url;
	},
	
	redirect : function(link) {
		return window.location(link);
	}
}

var new_localStorage = {
	set : function (value) {
		for(var key in value) {
			if(typeof value[key] === "object"){
				window.localStorage.setItem(key, JSON.stringify(value[key]));
			} else {
				window.localStorage.setItem(key, value[key]);
			}
		}
	},
	
	get : function (key) {
		var value = window.localStorage.getItem(key);
		return value;
	},
	
	remove : function (key) {
		window.localStorage.removeItem(key);
	}
}

var new_sessionStorage = {
	set : function (value) {
		for(var key in value) {
			window.sessionStorage.setItem(key,value[key]);
		}
	},
	
	get : function (key) {
		var value = window.sessionStorage.getItem(key);
		return value;
	},
	
	remove : function (key) {
		window.sessionStorage.removeItem(key);
	}
}

var new_User = {
	
	login : function (data){
		new_localStorage.set(data);
		return true;
	},
	
	isLogged : function () {
		var sign = new_localStorage, status_login = false;
		if(isset(sign.get('sign_status')) && (sign.get('sign_status')=="true")){
			$.ajax({url		: SERVER + 'way=public/index/check&sign='+sign.get('sign'),async:false, 
				success		: function(data) {
					if ( data ){status_login = true;} 
				},
				error 		: function (result, textStatus, xhr) {
					alert('textStatus : '+ textStatus + '\nresult : ' + JSON.stringify(result) + '\nxhr : ' + xhr);
				}
			});
			return status_login;
		}else {
			return false;
		}
	},
	
	logout : function () {
		var arr = ['sign_status','sign','user_id','username','permissions'];
		for(var i in arr){
			window.localStorage.removeItem(arr[i]);
		}
		//window.location = "login.html";
	},
	
	hasPermission : function (key, value){
		if  (isset(new_localStorage.get('permissions'))){
			var permissions = $.parseJSON(new_localStorage.get('permissions'));
			if(isset(permissions[key])){
				if($.inArray(value, permissions[key])>= 0){
					return true
				} else {
					return false;
				}
			}
		} else 
			return false;
	}
}

function get_url(key) {
	var value = [];
	var query = String(document.location).split('?');
	if (query[1]) {
		var part = query[1].split('&');
		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');
			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}
		if (value[key]) {	
			return value[key];
		} else {
			return '';
		}
	}
}

function just_way(key){
	var result = '', value = key.split('/');

	for(i = 1; i < 3; i++){
		
		if(i != 0){
			result += '/';
		}
		result += value[i];
	}
	return result;
}