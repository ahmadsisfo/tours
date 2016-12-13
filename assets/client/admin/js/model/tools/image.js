Mimage();
function Mimage(){
	$(document).delegate('a[data-name=\'inputimage\']','click', function(e) {
		e.preventDefault();
		buildimagemanager(this);
	});
	
	$('a[data-name=\'inputimage\']').on('click', function(e) {
		e.preventDefault();
		buildimagemanager(this);
	});
}

function buildimagemanager(dom){
	var element = $(dom).attr('id');
	
	$(dom).popover({
		html: true,
		placement: 'right',
		trigger: 'manual',
		content: function() {
			return '<button type="button" id="file-'+element+'" class="btn btn-primary"><i class="fa fa-pencil"></i></button> <button type="button" id="clear-'+element+'" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>';
		}
	});

	$(dom).popover('show');		

	$('#file-'+element).on('click', function() {
		$('#modal-image').remove();
		$.ajax({
			url: SERVER + 'way=admin/tools/filemanager&sign=' + new_localStorage.get('sign') + '&target=' + $('#'+element).parent().find('input').attr('id') + '&thumb=' + $('#'+element).attr('id'),
			dataType: 'html',
			beforeSend: function() {
				$('#file-'+element+' i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
			},
			complete: function() {
				$('#'+element).popover('hide');
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
		
		
	});

	$('#clear-'+element).on('click', function() {
		$('#'+element).find('img').attr('src', $('#'+element).find('img').attr('data-placeholder'));
		
		$('#'+element).parent().parent().find('.thumb').attr('value', $('#'+element).find('img').attr('data-placeholder').split(LINK_IMG).join(''));
		
		$('#'+element).parent().find('input').attr('value', '');

		$('#'+element).popover('hide');
	});
}