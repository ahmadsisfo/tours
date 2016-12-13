Msystemuser();

function Msystemuser() {
	
	
	var dom;
	 
	$(document).delegate('a[data-toggle=\'tooltip\']', 'click', function(e) {
		e.preventDefault();
		//$(dom).popover('toggle');
		if(dom != this){
			$(dom).popover('hide');	
		}
		dom = this;
		$(this).popover({
			html: true,
			placement: 'right',
			trigger: 'manual',
			content: function() {
				html  = '<div class="input-group">';
				html += '  <input type="text" name="menuname" value="" placeholder="Menu Name" class="form-control">';
				html += '  <input type="text" name="menutarget" value="" placeholder="Url Target without *admin/" class="form-control">';
				html += '  <span class="input-group-btn"><button type="button" id="button-create" class="btn btn-success"><i class="fa fa-plus-circle"></i></button></span>';
				html += '</div>';
				return html;
			}
		});
		$(this).popover('toggle');	
	});
	
	$(document).delegate('#button-create', 'click', function(e) {
		e.preventDefault();
		var menuname = encodeURIComponent($(this).parent().parent().find('input[name=\'menuname\']').val());
		var menutarget = $(this).parent().parent().find('input[name=\'menutarget\']').val();
		//alert(JSON.stringify($(this).parent().parent().parent().parent().parent().find('ul:first')));
		var menufix = "";
		if(menutarget == ""){
			menufix = '<a class="parent">'+menuname.replace('%20',' ')+'</a>';
		} else {
			menufix = '<a href="<? echo$url->link(\'admin/'+menutarget+'\', $sign, \'SSL\') ?>">'+menuname.replace('%20',' ')+'</a>';
		}
		
		if($(this).parent().parent().parent().parent().parent().find('ul:first').length){
			$(this).parent().parent().parent().parent().parent().find('ul:first').append(
				'<li>'+menufix+' '
				+'<a data-toggle="tooltip" class="btn btn-success"><i class="fa fa-plus"></i></a> <a data-toggle="tooltip-delete" class="btn btn-danger"><i class="fa fa-trash-o"></i>'
				+'</li>'
			);
		} else {
			if($(this).parent().parent().parent().parent().parent().find('a:first').find('i').length){
				var nameold = $(this).parent().parent().parent().parent().parent().find('a:first').html();
				$(this).parent().parent().parent().parent().parent().find('a:first').replaceWith('<a class="parent">'+nameold+'</a>');
			} else {
				var nameold = $(this).parent().parent().parent().parent().parent().find('a:first').html();
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
	
	$(document).delegate('a[data-toggle=\'tooltip primary\']', 'click', function(e) {
		e.preventDefault();
		if(dom != this){
			$(dom).popover('hide');	
		}
		dom = this;
		$(this).popover({
			
			html: true,
			placement: 'right',
			trigger: 'manual',
			content: function() {
				
				html  = '<div class="input-group">';
				html += '  <input type="text" name="menuname" value="" placeholder="Menu Name" class="form-control">';
				html += '  <input type="text" name="menuicon" value="" placeholder="Icon Bootstrap" class="form-control">';
				html += '  <input type="text" name="menutarget" value="" placeholder="Url Target" class="form-control">';
				html += '  <span class="input-group-btn"><button type="button" id="button-create-primary" class="btn btn-success"><i class="fa fa-plus-circle"></i></button></span>';
				html += '</div>';
				
				return html;
			}
		});
		$(this).popover('toggle');	
	});
	
	$(document).delegate('#button-create-primary', 'click', function(e) {
		e.preventDefault();
		var menuname = encodeURIComponent($(this).parent().parent().find('input[name=\'menuname\']').val());
		var menuicon = $(this).parent().parent().find('input[name=\'menuicon\']').val();
		var menutarget = $(this).parent().parent().find('input[name=\'menutarget\']').val();
		//alert(JSON.stringify($(this).parent().parent().parent().parent().parent().find('ul:first')));
		
		var menufix = "";
		if(menutarget == ""){
			menufix = '<a class="parent"><i class="fa fa-'+menuicon+' fa-fw"></i> <span>'+menuname+'</span></a>';
		} else {
			menufix = '<a href="<? echo$url->link(\'admin/'+menutarget+'\', $sign, \'SSL\') ?>"><i class="fa fa-'+menuicon+' fa-fw"></i> <span>'+menuname.replace('%20',' ')+'</span></a>';
			
		}
		
		$('a[data-toggle=\'tooltip primary\'').parent().find('#ulform').append(
			'<li>'+menufix+' '
			+'<a data-toggle="tooltip" class="btn btn-success"><i class="fa fa-plus"></i></a> <a data-toggle="tooltip-delete" class="btn btn-danger"><i class="fa fa-trash-o"></i>'
			+'</li>'
		);
		
		$(dom).popover('hide');
		$(dom).tooltip('destroy');
		//Msystemuser();
		updateform();
	});	
	
}

function updateform(){
	//alert($('#menus-editor').html());
	$('.popover').remove();
	var menus = $('#ulform').html();
	menus = menus.split('http://localhost/tours/?way=admin/').join('<? echo$url->link(\'admin/');
	menus = menus.split('<ul></ul>').join('');
	menus = menus.split('&amp;sign='+new_localStorage.get('sign')).join('\', $sign, \'SSL\') ?>');
	menus = menus.split('<a data-toggle="tooltip" class="btn btn-success"><i class="fa fa-plus"></i></a> <a data-toggle="tooltip-delete" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>').join('');
	menus = menus.split('<a data-toggle="tooltip" class="btn btn-success" data-original-title="" title=""><i class="fa fa-plus"></i></a> <a data-toggle="tooltip-delete" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>').join('');
	$('#form-menu input[name="menus"]').val(menus);
	 
}