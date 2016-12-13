
function Mautocomplete(dom) {
	var row = $('input[name="row'+$(dom).attr('id')+'"]').val();
	$(dom).autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?way=admin/spatial/penginapan/autocomplete&table='+$(dom).attr('id')+'&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				beforeSend: function ( xhr ) {
				},
				success: function(json) {
					//alert(JSON.stringify(this));
					response($.map(json, function(item) {
						return {
							label: item['label'],
							value: item['value'],
							
						}
					}));
				},
				error: function (request,error) { 
					alert(JSON.stringify(request)); 
				},
			});
		},
		'select': function(item) {
			$(dom).val('');
			$(dom).parent().find('.well').find('div').find('input[value="'+ item['value'] +'"]:first').parent().remove();
			$(dom).parent().find('.well').append('<div class="autocompleteminus"><i class="fa fa-minus-circle"></i> '+ item['label'] +'<input type="hidden" name="'+$(dom).attr('id')+'['+row+'][value]" value="' + item['value'] + '" /><input type="hidden" name="'+$(dom).attr('id')+'['+row+'][key]" value="' + item['label'] + '" /></div>'); row++;
		}
	});

	$(document).delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().attr('class','.autocompleteminus').remove();
	});
		
}