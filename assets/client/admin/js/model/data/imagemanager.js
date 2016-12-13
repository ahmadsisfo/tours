Mdataimagemanager();

function Mdataimagemanager() {
//$(document).ready(function() {
	
	// Override summernotes image manager
	$('button[data-event=\'showImageDialog\']').attr('data-toggle', 'image').removeAttr('data-event');
	
	
	// Image Manager
	$(document).delegate('a[data-toggle=\'image\']', 'click', function(e) {
		e.preventDefault();
	
		var element = $(this).attr('id');
		
		$(this).popover({
			html: true,
			placement: 'right',
			trigger: 'manual',
			content: function() {
				return '<button type="button" id="file-'+element+'" class="btn btn-primary"><i class="fa fa-pencil"></i></button> <button type="button" id="clear-'+element+'" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>';
			}
		});
	
		$(this).popover('toggle');		
	
		$('#file-'+element).on('click', function() {
			$('#modal-image').remove();
			$.ajax({
				url: SERVER + 'way=admin/tools/filemanager&sign=' + new_localStorage.get('sign') + '&target=' + $('#'+element).parent().find('input').attr('id') + '&thumb=' + $('#'+element).attr('id'),
				dataType: 'html',
				beforeSend: function() {
					$('#button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
					$('#button-image').prop('disabled', true);
				},
				complete: function() {
					$('#button-image i').replaceWith('<i class="fa fa-upload"></i>');
					$('#button-image').prop('disabled', false);
				},
				success: function(html) {
					$('body').append('<div id="modal-image" class="modal">' + html + '</div>');
					$('#modal-image').modal('show');
					$('#modal-image').find('a').attr('row', element);
			
					$LAB.script(HTTP_MODEL + 'tools/filemanager.js').wait(function(){
						Mtoolsfilemanager();
					});
				}
			});
			
			$('#'+element).popover('hide');
		});
	
		$('#clear-'+element).on('click', function() {
			$('#'+element).find('img').attr('src', $('#'+element).find('img').attr('data-placeholder'));
			
			$('#'+element).parent().find('input').attr('value', '');
	
			$('#'+element).popover('hide');
		});
	});
	
	//ON RELOAD
	$('a[data-toggle=\'image\']').on('click', function(e) {
		e.preventDefault();
	
		var element = $(this).attr('id');
		
		$('#'+element).popover({
			html: true,
			placement: 'right',
			trigger: 'manual',
			content: function() {
				return '<button type="button" id="file-'+element+'" class="btn btn-primary"><i class="fa fa-pencil"></i></button> <button type="button" id="clear-'+element+'" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>';
			}
		});
	
		$('#'+element).popover('toggle');		
	
		$('#file-'+element).on('click', function() {
			$('#modal-image').remove();
			$.ajax({
				url: SERVER + 'way=admin/tools/filemanager&sign=' + new_localStorage.get('sign') + '&target=' + $('#'+element).parent().find('input').attr('id') + '&thumb=' + $('#'+element).attr('id'),
				dataType: 'html',
				beforeSend: function() {
					$('#button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
					$('#button-image').prop('disabled', true);
				},
				complete: function() {
					$('#button-image i').replaceWith('<i class="fa fa-upload"></i>');
					$('#button-image').prop('disabled', false);
				},
				success: function(html) {
					$('body').append('<div id="modal-image" class="modal">' + html + '</div>');
					$('#modal-image').modal('show');
					$('#modal-image').find('a').attr('row', element);
			
					$LAB.script(HTTP_MODEL + 'tools/filemanager.js').wait(function(){
						Mtoolsfilemanager();
					});
				}
			});
			
			$('#'+element).popover('hide');
		});
	
		$('#clear-'+element).on('click', function() {
			$('#'+element).find('img').attr('src', $('#'+element).find('img').attr('data-placeholder'));
			
			$('#'+element).parent().find('input').attr('value', '');
	
			$('#'+element).popover('hide');
		});
	});
//});
}