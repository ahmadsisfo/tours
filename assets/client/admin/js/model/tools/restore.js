Mtoolsrestore();

function Mtoolsrestore() {

if(AJAX_MODE){

$(document).delegate('#submit-form-restore', 'click', function(e) {
		e.preventDefault();
		
		$('#form-restore2').remove();
		$(this).tooltip('destroy');
		$('body').prepend('<form enctype="multipart/form-data" id="form-restore2" style="display: none;"><input type="file" name="import" /></form>');

		$('#form-restore2 input[name=\'import\']').trigger('click');

		
		$('#form-restore2 input[name=\'import\']').on( 'change', function() {
		
		$(this).tooltip('destroy');
		if(confirm('Apakah anda ingin merestore file ini ?')){
		
				$.ajax({
					url: SERVER + 'way=admin/tools/restore/restore&sign=' + new_localStorage.get('sign'),
					type: 'post',		
					//dataType: 'json',
					data: new FormData($(this).parent()[0]),
					cache: false,
					contentType: false,
					processData: false,	
					beforeSend: function() {
						$('#submit-form-restore i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
						$('#submit-form-restore').prop('disabled', true);
					},
					complete: function() {
						$('#submit-form-restore i').replaceWith('<i class="fa fa-upload"></i>');
						$('#submit-form-restore').prop('disabled', false);
					},
					success: function(html) {
						
						$('#content').html(html);
						$('#submit-form-restore').remove();
						$('#form-restore input[name=\'import\']').parent().html('<a id="submit-form-restore" data-toggle="tooltip" title="add file restore" class="btn btn-default"><i class="fa fa-upload"></i></a>');

					},			
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
				return false;
		} else {
			return false;
		}
		
	});
		
});

}
}