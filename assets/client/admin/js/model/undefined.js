var file_script = [
	LINK + "admin/js/model/empty.js"
];
$LAB.script(file_script).wait(function(){
	if(AJAX_MODE) { if(FILE == 'login.html') {
	//================================= START ====================================		
	$('#form-login').on('submit', function() {
		$.ajax({
			url			: HTTP_SERVER + 'way=public/index',
			data		: $('#form-login').serialize(),
			type		: 'POST',
			beforeSend	: function() {
				$('#form-login input[type="submit"]').val('Loading...');
			},
			complete	: function() {
				$('#form-login input[type="submit"]').val('login');
			},
			success		: function(data) {
				if ( data ){
					var user = new_User;
					if(user.login(data)){
						window.location = "./";
					}
				} else { alert('Username atau Password anda salah !'); }
			},
			error 		: function (result, textStatus, xhr) {
				alert('textStatus : '+ textStatus + '\nresult : ' + JSON.stringify(result) + '\nxhr : ' + xhr);
			}
		});	
		return false;
	}); } else { window.location = "login.html"; }
	//================================ FINISH ====================================
	}
});