Mtoolshandling();

function Mtoolshandling() {
	
	$(document).delegate('input[name=\'direc\']', 'keyup', function(e) {
		e.preventDefault();
		if($('input[name=\'to_table\']').is(':checked')){
			var direc = $(this).val();
			table = direc.split("/").pop();
			$('input[name=\'table\']').val(table);
		}
	});
	
	$(document).delegate('.inputname', 'keyup', function(e) {
		e.preventDefault();
		if($('input[name=\'to_field\']').is(':checked')){
			var direc = $(this).val();
			table = direc.split("/").pop();
			$(this).parent().parent().find('.fieldname').val(table);
			$(this).parent().parent().find('.labeling').val(table);
			$(this).parent().parent().find('.placeholder').val(table);
		}
	});
	
	$(document).delegate('.labeling', 'keyup', function(e) {
		e.preventDefault();
		if($('input[name=\'to_placeholder\']').is(':checked')){
			var direc = $(this).val();
			table = direc.split("/").pop();
			$(this).parent().parent().find('.placeholder').val(table);
		}
	});
	
	$(document).delegate('input[name=\'to_table\']', 'change', function(e) {
		if($(this).is(':checked')){
			$('input[name=\'table\']').attr('readonly',true);
		}else{
			$('input[name=\'table\']').attr('readonly',false);
		}
	});
	
	$(document).delegate('input[name=\'to_label\']', 'change', function(e) {
		if($(this).is(':checked')){
			$('.labeling').attr('readonly',true);
		}else{
			$('.labeling').attr('readonly',false);
		}
	});
	
	$(document).delegate('input[name=\'to_field\']', 'change', function(e) {
		if($(this).is(':checked')){
			$('.fieldname').attr('readonly',true);
		}else{
			$('.fieldname').attr('readonly',false);
		}
	});
	
	$(document).delegate('input[name=\'to_placeholder\']', 'change', function(e) {
		if($(this).is(':checked')){
			$('.placeholder').attr('readonly',true);
		}else{
			$('.placeholder').attr('readonly',false);
		}
	});
	
	$(document).delegate('input[name=\'to_database\']', 'change', function(e) {
		if($(this).is(':checked')){
			$('.fieldtype').attr('readonly',false);
		}else{
			$('.fieldtype').attr('readonly',true);
		}
	});
	
	$(document).delegate('.radio-active', 'change', function(e) {
		$('.radio-active').prop('checked', false);
		$(this).prop('checked', true);
	});
	
	$(document).delegate('.radio-primary', 'change', function(e) {
		$('.radio-primary').prop('checked', false);
		$(this).prop('checked', true);
	});
	
	$(document).delegate('#button-create', 'click', function(e) {
		e.preventDefault();
		var menuname = encodeURIComponent($(this).parent().parent().find('input[name=\'menuname\']').val());
		var menutarget = $(this).parent().parent().find('input[name=\'menutarget\']').val();
		//alert(JSON.stringify($(this).parent().parent().parent().parent().parent().find('ul:first')));
		var menufix = "";
		if(menutarget == ""){
			menufix = '<a class="parent">'+menuname+'</a>';
		} else {
			menufix = '<a href="<? echo$url->link(\'admin/'+menutarget+'\', $sign, \'SSL\') ?>">'+menuname.replace('%20',' ')+'</a>';
		}
		
		if($(this).parent().parent().parent().parent().parent().parent().find('ul:first').length){
			$(this).parent().parent().parent().parent().parent().parent().find('ul:first').append(
				'<li>'+menufix+' '
				+'<a data-toggle="tooltip" class="btn btn-success"><i class="fa fa-plus"></i></a> <a data-toggle="tooltip-delete" class="btn btn-danger"><i class="fa fa-trash-o"></i>'
				+'</li>'
			);
		} else {
			if($(this).parent().parent().parent().parent().parent().parent().find('a:first').find('i').length){
			} else {
				var nameold = $(this).parent().parent().parent().parent().parent().parent().find('a:first').html();
				$(this).parent().parent().parent().parent().parent().find('a:first').replaceWith('<a class="parent">'+nameold+'</a>');
			}
			$(this).parent().parent().parent().parent().parent().append(
				'<ul><li>'+menufix+' '
				+'<a data-toggle="tooltip" class="btn btn-success"><i class="fa fa-plus"></i></a> <a data-toggle="tooltip-delete" class="btn btn-danger"><i class="fa fa-trash-o"></i>'
				+'</li></ul>'
			);
		}
		$(dom).popover('hide');
		$(dom).tooltip('destroy');
		//Msystemuser();
		updateform();
		
	});	
	
	$(document).delegate('a[data-toggle=\'tooltip-delete\']', 'click', function(e) {
		var dom2 = $(this).parent().parent();
		$(this).parent().remove();
		if(dom2.html()==""){
			dom2.remove();
		}
		updateform();
	});	
	
	var dom;
	
	$(document).delegate('.selecttype', 'change', function(e) {
		e.preventDefault();
		if(dom != this){
			$(this).parent().parent().parent().find('.popover').fadeOut();
		}
		dom = this;
		if($(this).val() == 'select' || $(this).val() == 'autocomplete'){
			var inputtype = $(this).val();
			if(!$(this).attr('data-save')){			
				$(this).popover({
					html: true,
					placement: 'right',
					trigger: 'manual',
					content: function() {
						
						html  = '<div class="input-group">';
						html += '  <input type="text" name="'+inputtype+'['+$(this).attr('data-number')+'][table]" value="" placeholder="Table" class="form-control">';
						html += '  <input type="text" name="'+inputtype+'['+$(this).attr('data-number')+'][key]" value="" placeholder="Key" class="form-control">';
						html += '  <input type="text" name="'+inputtype+'['+$(this).attr('data-number')+'][value]" value="" placeholder="Value" class="form-control">';
						if($(this).val() == 'autocomplete'){
							html += '  <input type="text" name="'+inputtype+'['+$(this).attr('data-number')+'][target]" value="" placeholder="Penyimpanan Autocomplete" class="form-control">';
						}
						html += '  <span class="input-group-btn"><button type="button" class="btn btn-success button-save"><i class="fa fa-save"></i></button></span>';
						html += '</div>';
						
						return html;
					}
				});
				$(this).popover('show');
			} else {
				$(this).parent().find('.popover').fadeIn();
			}
		} else {
			$(this).parent().find('.popover').fadeOut();
		}	
	});
	
	$(document).delegate('.button-save', 'click', function(e) {
		e.preventDefault();
		$(dom).attr('data-save',true);
		$(dom).parent().find('.popover').fadeOut();
	});	
	
}

function updateform(row){
	if($('input[name=\'to_field\']').is(':checked')){
		$('.fieldname').attr('readonly',true);
	}else{
		$('.fieldname').attr('readonly',false);
	}
	
	if($('input[name=\'to_placeholder\']').is(':checked')){
		$('.placeholder').attr('readonly',true);
	}else{
		$('.placeholder').attr('readonly',false);
	}
	
	if($('input[name=\'to_label\']').is(':checked')){
		$('.labeling').attr('readonly',true);
	}else{
		$('.labeling').attr('readonly',false);
	}
	
	if($('input[name=\'to_database\']').is(':checked')){
		$('.fieldtype').attr('readonly',false);
	}else{
		$('.fieldtype').attr('readonly',true);
	}
}

