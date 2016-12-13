$LAB.script(LINK + "admin/js/engine.js").wait(function(){
	var registry = new_Registry;
	var loader   = new_Loader;
	loader.init(registry);
	
	if(AJAX_MODE) {
		if(new_User.isLogged()&& FILE != 'login.html'){
			loader.model('home/header');
			if(isset(get_url('sign')) && isset(get_url('way'))) {
				loader.model(just_way(get_url('way')));
			} else { 
				loader.model('home/dashboard');
			}
		}else {
			loader.model('undefined');	
			new_User.logout();
		}
	} else {
		if(isset(get_url('sign')) && isset(get_url('way'))) {
			loader.model('home/header');
			loader.model(just_way(get_url('way')));
		} else { 
			loader.model('undefined');		
		}
	}
});

