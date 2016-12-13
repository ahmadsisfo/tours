Msystemapi();
function Msystemapi() {

	$(document).delegate('#button-generate','click', function(e) {
		e.preventDefault();
		rand = '';
		string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		for (i = 0; i < 256; i++) {
			rand += string[Math.floor(Math.random() * (string.length - 1))];
		}
		$('#input-password').val(rand);
	});	

}
