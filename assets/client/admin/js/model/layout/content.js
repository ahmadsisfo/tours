Mlayoutcontent();
function Mlayoutcontent() {
	
}

/*var content_row = 0;
function addContent(content_row, no_image) {
	html  = '<tr class="content-row'+content_row+'">';
	html += '<td class="text-left col-sm-2"><input type="text" name="layout_content['+content_row+'][title]" value="" placeholder="judul" class="form-control" /></td>'+
            '    <td class="text-left"><input type="text" name="layout_content['+content_row+'][key]" value="" placeholder="key" class="form-control" /></td>'+
            '    <td class="text-left"><input type="text" name="layout_content['+content_row+'][value]" value="" placeholder="value" class="form-control" /></td>'+
			'	<td class="text-left"><button type="button" onclick="$(\'.content-row'+content_row+'\').remove();" data-toggle="tooltip" title="remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>'+
            '  </tr>'+
			'  <tr class="content-row'+content_row+'">'+
			'	<td class="text-left">'+
			'		<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">image</label><br/><br/>'+
			'		<a href="" id="thumb-image-'+content_row+'" data-toggle="image" class="img-thumbnail">'+
			'			<img src="'+no_image+'" alt="" title="" data-placeholder="'+no_image+'" />'+
			'		</a>'+
			'		<input type="hidden" name="layout_content['+content_row+'][image]" value="" id="input-image-'+content_row+'" />'+
			'	</td>'+
			'	<td class="text-left" colspan="2">'+
			'		<textarea name="layout_content['+content_row+'][description]" id="description-'+content_row+'" class="form-control">'+
			'		</textarea>'+
        	'	</td>'+
			'	<td></td>'+
			' </tr>';
	
	$('#content tbody').append(html);
	$('#description-'+content_row).summernote({height: 100});  
	content_row++;
}*/