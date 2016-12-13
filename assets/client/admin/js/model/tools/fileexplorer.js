//Mtoolsfileexplorer();

function Mtoolsfileexplorer() {

	$('a.thumbnail').on('click', function(e) {
		e.preventDefault();
		
		
		var row = $(this).attr('row')  ; 
		
		//$('#thumb-image').find('img').parent().remove();
		
		$('#thumb-image').find('img').attr('src', $(this).find('img').attr('src'));
			
		$('#input-image').attr('value', $(this).parent().find('input').attr('value'));

		$('#'+row).find('img').attr('src', $(this).find('img').attr('src'));
		
		$('#'+row).parent().find('input[type=\'hidden\']').attr('value', $(this).parent().find('input').attr('value'));
			
		var range, sel = window.getSelection(); 
		
		if (sel.rangeCount) { 
			var img = document.createElement('img');
			img.src = $(this).attr('href');
		
			range = sel.getRangeAt(0); 
			range.insertNode(img);

		}
		$('#modal-image').modal('hide');
		$('#menu img').remove();
		
		$('.btn-primary').prop('disabled',false);
		$('input[name="scriptname"]').attr('value', $(this).parent().find('input').attr('value'));
		getscript($(this).parent().find('input').attr('value'));
		
	});
	
	$('a.directory').on('click', function(e) {
		var row = $(this).attr('row'); 
		e.preventDefault();
		$(this).tooltip('destroy');
		$('#modal-image').load($(this).attr('href'), function(){
			Mtoolsfileexplorer(); $(this).find('a').attr('row', row);
		});
	});

	$('.pagination a').on('click', function(e) {
		var row = $(this).attr('row'); 
		e.preventDefault();
		$(this).tooltip('destroy');
		$('#modal-image').load($(this).attr('href'), function(){
			Mtoolsfileexplorer(); $(this).find('a').attr('row', row);
		});
	});

	$('#button-parent').on('click', function(e) {
		var row = $(this).attr('row'); 
		e.preventDefault();
		$(this).tooltip('destroy');
		$('#modal-image').load($(this).attr('href'), function(){
			Mtoolsfileexplorer(); $(this).find('a').attr('row', row);
		});
	});

	$('#button-refresh').on('click', function(e) {
		var row = $(this).attr('row'); 
		e.preventDefault();
		$(this).tooltip('destroy');
		$('#modal-image').load($(this).attr('href'), function(){
			Mtoolsfileexplorer(); $(this).find('a').attr('row', row);
		});
	});

	$('#button-search').on('click', function() {
		var row = $(this).attr('row'); 
		var url = SERVER + 'way=admin/tools/fileexplorer&sign=' + new_localStorage.get('sign') +'&directory='+$('#directory_now').val();
		$(this).tooltip('destroy');
		var filter_name = $('input[name=\'search\']').val();
		if (filter_name) {
			url += '&filter_name=' + encodeURIComponent(filter_name);
		}
								
			url += '&thumb=' + 'thumb-image';
			
			url += '&target=' + 'input-image';
		
		$('#modal-image').load(url, function(){
			Mtoolsfileexplorer(); $(this).find('a').attr('row', row);
		});
	});

	$('#button-upload').on('click', function() {
		$('#form-upload').remove();
		$(this).tooltip('destroy');
		$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

		$('#form-upload input[name=\'file\']').trigger('click');
		
	
		$('#form-upload input[name=\'file\']').on('change', function() {
	
			$.ajax({
				url: SERVER + 'way=admin/tools/fileexplorer/upload&sign=' + new_localStorage.get('sign') +'&directory='+$('#directory_now').val(),
				type: 'post',		
				dataType: 'json',
				data: new FormData($(this).parent()[0]),
				cache: false,
				contentType: false,
				processData: false,		
				beforeSend: function() {
					$('#button-upload i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
					$('#button-upload').prop('disabled', true);
				},
				complete: function() {
					$('#button-upload i').replaceWith('<i class="fa fa-upload"></i>');
					$('#button-upload').prop('disabled', false);
				},
				success: function(json) {
					if (json['error']) {
						alert(json['error']);
					}
					if (json['success']) {
						alert(json['success']);
						
						$('#button-refresh').trigger('click');
					}
					Mtoolsfileexplorer();
				},			
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});
	});

	$('#button-folder').popover({
		html: true,
		placement: 'bottom',
		trigger: 'click',
		title: 'Nama Folder',
		content: function() {
			html  = '<div class="input-group">';
			html += '  <input type="text" name="folder" value="" placeholder="Nama Folder" class="form-control">';
			html += '  <span class="input-group-btn"><button type="button" title="Folder Baru" id="button-create" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></span>';
			html += '</div>';
			
			return html;	
		}
	});

	$('#button-file').popover({
		html: true,
		placement: 'bottom',
		trigger: 'click',
		title: 'Nama File',
		content: function() {
			html  = '<div class="input-group">';
			html += '  <input type="text" name="file" value="" placeholder="Nama File" class="form-control">';
			html += '  <span class="input-group-btn"><button type="button" title="Folder Baru" id="button-file-create" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></span>';
			html += '</div>';
			
			return html;	
		}
	});
	
	$('#button-file').on('shown.bs.popover', function() {
		$('#button-file-create').on('click', function() {
			$(this).tooltip('destroy');
			$.ajax({
				url: SERVER + 'way=admin/tools/fileexplorer/file&sign=' + new_localStorage.get('sign') +'&directory='+$('#directory_now').val(),
				type: 'post',		
				dataType: 'json',
				data: 'file=' + encodeURIComponent($('input[name=\'file\']').val()),
				beforeSend: function() {
					$('#button-create i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
					$('#button-create').prop('disabled', true);
				},
				complete: function() {
					$('#button-create i').replaceWith('<i class="fa fa-plus-circle"></i>');
					$('#button-create').prop('disabled', false);
				},
				success: function(json) {
					if (json['error']) {
						alert(json['error']);
					}
					
					if (json['success']) {
						alert(json['success']);
											
						$('#button-refresh').trigger('click');
					}
					Mtoolsfileexplorer();
				},			
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});	
	});
	
	$('#button-folder').on('shown.bs.popover', function() {
		$('#button-create').on('click', function() {
			$(this).tooltip('destroy');
			$.ajax({
				url: SERVER + 'way=admin/tools/fileexplorer/folder&sign=' + new_localStorage.get('sign') +'&directory='+$('#directory_now').val(),
				type: 'post',		
				dataType: 'json',
				data: 'folder=' + encodeURIComponent($('input[name=\'folder\']').val()),
				beforeSend: function() {
					$('#button-create i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
					$('#button-create').prop('disabled', true);
				},
				complete: function() {
					$('#button-create i').replaceWith('<i class="fa fa-plus-circle"></i>');
					$('#button-create').prop('disabled', false);
				},
				success: function(json) {
					if (json['error']) {
						alert(json['error']);
					}
					
					if (json['success']) {
						alert(json['success']);
											
						$('#button-refresh').trigger('click');
					}
					Mtoolsfileexplorer();
				},			
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});	
	});

	$('#modal-image #button-delete').on('click', function(e) {
		$(this).tooltip('destroy');
		if (confirm('Apakah Anda yakin?')) {
			$.ajax({
				url: SERVER + 'way=admin/tools/fileexplorer/delete&sign=' + new_localStorage.get('sign'),
				type: 'post',		
				dataType: 'json',
				data: $('input[name^=\'path\']:checked'),
				beforeSend: function() {
					$('#button-delete i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
					$('#button-delete').prop('disabled', true);
				},	
				complete: function() {
					$('#button-delete i').replaceWith('<i class="fa fa-trash-o"></i>');
					$('#button-delete').prop('disabled', false);
				},		
				success: function(json) {
					if (json['error']) {
						alert(json['error']);
					}
					
					if (json['success']) {
						alert(json['success']);
						
						$('#button-refresh').trigger('click');
					}
					Mtoolsfileexplorer();
				},			
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	});

}