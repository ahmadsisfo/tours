Mdataspatial();
function Mdataspatial() {

		$('input[name=\'related\']').autocomplete({
			'source': function(request, response) {
				$.ajax({
					url: 'index.php?way=admin/data/spatial/autocomplete&filter_name=' +  encodeURIComponent(request),
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							return {
								label: item['name'],
								value: item['gid']
							}
						}));
					}
				});
			},
			'select': function(item) {
				$('input[name=\'related\']').val('');
				$('#spatial-related' + item['value']).remove();
				$('#spatial-related').append('<div id="spatial-related' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="spatial_related[]" value="' + item['value'] + '" /></div>');
			}
		});

		$('#spatial-related').delegate('.fa-minus-circle', 'click', function() {
			$(this).parent().remove();
		});
		
		$('.date').datetimepicker({
			pickTime: false,
			
		});

		$('.time').datetimepicker({
			pickDate: false
		});

		$('.datetime').datetimepicker({
			pickDate: true,
			pickTime: true
		});
		
		$('#language a:first').tab('show');
		$('#option a:first').tab('show');

$('input[name=\'filter\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?way=admin/object/filter/autocomplete&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['filter_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter\']').val('');

		$('#spatial-filter' + item['value']).remove();

		$('#spatial-filter').append('<div id="spatial-filter' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="spatial_filter[]" value="' + item['value'] + '" /></div>');
	}
});

$('#spatial-filter').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
		

$('#button-filter').on('click', function() {
	var url = 'index.php?way=admin/data/spatial&sign='+ new_localStorage.get('sign');

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_address = $('input[name=\'filter_address\']').val();

	if (filter_address) {
		url += '&filter_address=' + encodeURIComponent(filter_address);
	}

	var filter_filter = $('input[name=\'filter_filter\']').val();

	if (filter_filter) {
		url += '&filter_filter=' + encodeURIComponent(filter_filter);
	}
	
	/*var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}*/

	location = url;
});


}


