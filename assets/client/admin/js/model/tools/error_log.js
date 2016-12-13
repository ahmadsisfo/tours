Mtoolserrorlog();

function Mtoolserrorlog() {

//$(document).ready(function() {
	$(document).delegate('#error_clear', 'click', function(e) {
		e.preventDefault();
		$(this).tooltip('destroy');
		if(confirm('Apakah anda ingin menghapus error.log ?')){
			if(AJAX_MODE){
				$.ajax({
					url: SERVER +'way=admin/tools/error_log/clear&sign=' + new_localStorage.get('sign'),
					dataType: 'html',
					beforeSend: function() {
						$('#content').html('Loading...');
					},
					complete: function() {
						//$('#content').html('');
					},
					success: function(html) {
						$('#content').html(html);
						Mtoolserrorlog();
					},
					error 		: function (result, textStatus, xhr) {
						alert('textStatus : '+ textStatus + '\nresult : ' + JSON.stringify(result) + '\nxhr : ' + xhr);
					}
					
				});
				return false;
			} else {
				location.href= SERVER + 'way=admin/tools/error_log/clear&amp;sign='+ get_url('sign');
			}
		} else {
			return false;
		}
		
	});
	
//});
}