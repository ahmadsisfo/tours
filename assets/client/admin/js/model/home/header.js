
var JSNOTFOUND = new Array();

$(document).ready(function() {
	if(!AJAX_MODE) {
		new_localStorage.set({'sign':get_url('sign')});
	} 
	
	//Form Submit for IE Browser
	$('button[type=\'submit\']').on('click', function() {
		$("form[id*='form-']").submit();
	});

	// Highlight any found errors
	$('.text-danger').each(function() {
		var element = $(this).parent().parent();
		
		if (element.hasClass('form-group')) {
			element.addClass('has-error');
		}
	});
	
	$('a').on('click', function(e) {		
		if($(this).attr('href')== ''){
			e.preventDefault();
			return false;
		}
	});
	// tooltips on hover
	$('[data-toggle=\'tooltip\']').tooltip({container: 'body', html: true});

	// Makes tooltips work on ajax generated content
	$(document).ajaxStop(function() {
		$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});
	});	
	
	$('#menu a[href], #header a[href]').on('click', function(e) {
		sessionStorage.setItem('menu', $(this).attr('href'));
		if(AJAX_MODE) {
			var way = $(this).attr('href').replace('#','');
			if(way == 'home/logout'){
				if (confirm('Apakah Anda yakin?')) {
				} else {
					return false;
				}
			}
			//alert(way);
			$.ajax({
				url: HTTP_SERVER + 'way='+ way +'&sign=' + new_localStorage.get('sign'),
				dataType: 'html',
				beforeSend: function() {
					$('#content').html('Loading...');
				},
				complete: function() {
					//$('#content').html('');
				},
				success: function(html) {
					if(way == 'home/logout'){window.location = 'login.html';} else {
						$('#content').html(html);
						if(JSNOTFOUND.indexOf(just_way(way)) == -1 || JSNOTFOUND.length == 0){
							$.getScript(HTTP_MODEL + just_way(way) + '.js')
							.fail(function(jqxhr, settings, exception){
								console.log('file : ' +HTTP_MODEL + just_way(way) + '.js tidak ditemukan');
								
							});
							JSNOTFOUND.push(just_way(way));
						}
						modifDOM();
						menucollapse();
						bindButtonClick();

					}
				},
				error 		: function (result, textStatus, xhr) {
					alert('textStatus : '+ textStatus + '\nresult : ' + JSON.stringify(result) + '\nxhr : ' + xhr);
				}
			});
	
			e.preventDefault();
			return false;
		}
	});
	
	if (!sessionStorage.getItem('menu')) {
		$('#menu #dashboard').addClass('active');
	} else {
		// Sets active and open to selected page in the left column menu.
		$('#menu a[href=\'' + sessionStorage.getItem('menu') + '\']').parents('li').addClass('active open');
	}

	if (localStorage.getItem('column-left') == 'active') {
		$('#button-menu i').replaceWith('<i class="fa fa-dedent fa-lg"></i>');
		
		$('#column-left').addClass('active');
		
		// Slide Down Menu
		$('#menu li.active').has('ul').children('ul').addClass('collapse in');
		$('#menu li').not('.active').has('ul').children('ul').addClass('collapse');
	} else {
		$('#button-menu i').replaceWith('<i class="fa fa-indent fa-lg"></i>');
		
		$('#menu li li.active').has('ul').children('ul').addClass('collapse in');
		$('#menu li li').not('.active').has('ul').children('ul').addClass('collapse');
	}

	// Menu button
	$('#button-menu').on('click', function() {
		// Checks if the left column is active or not.
		if ($('#column-left').hasClass('active')) {
			localStorage.setItem('column-left', '');

			$('#button-menu i').replaceWith('<i class="fa fa-indent fa-lg"></i>');

			$('#column-left').removeClass('active');

			$('#menu > li > ul').removeClass('in collapse');
			$('#menu > li > ul').removeAttr('style');
			
		} else {
			localStorage.setItem('column-left', 'active');

			$('#button-menu i').replaceWith('<i class="fa fa-dedent fa-lg"></i>');
			
			$('#column-left').addClass('active');

			// Add the slide down to open menu items
			$('#menu li.open').has('ul').children('ul').addClass('collapse in');
			$('#menu li').not('.open').has('ul').children('ul').addClass('collapse');
			
		}
	});
	
	// panel swip
	$(window).on( "swipeleft", function(e){
		if ($('#column-left').hasClass('active')){
			localStorage.setItem('column-left', '');
			$('#button-menu i').replaceWith('<i class="fa fa-indent fa-lg"></i>');
			$('#column-left').removeClass('active');
			$('#menu > li > ul').removeClass('in collapse');
			$('#menu > li > ul').removeAttr('style');
		}
    });
	
	$(window).on( "swiperight", function(e){
		if (!$('#column-left').hasClass('active')){
			localStorage.setItem('column-left', 'active');
			$('#button-menu i').replaceWith('<i class="fa fa-dedent fa-lg"></i>');
			$('#column-left').addClass('active');
		// Add the slide down to open menu items
			$('#menu li.open').has('ul').children('ul').addClass('collapse in');
			$('#menu li').not('.open').has('ul').children('ul').addClass('collapse');
		}
    });

	// Menu
	$('#menu').find('li').has('ul').children('a').on('click', function() {
		if ($('#column-left').hasClass('active')) {
			$(this).parent('li').toggleClass('open').children('ul').collapse('toggle');
			$(this).parent('li').siblings().removeClass('open').children('ul.in').collapse('hide');
		} else if (!$(this).parent().parent().is('#menu')) {
			$(this).parent('li').toggleClass('open').children('ul').collapse('toggle');
			$(this).parent('li').siblings().removeClass('open').children('ul.in').collapse('hide');
		}
	});
	
});

function menucollapse () {
	if ($('#column-left').hasClass('active')) {
		localStorage.setItem('column-left', '');
		$('#button-menu i').replaceWith('<i class="fa fa-indent fa-lg"></i>');
		$('#column-left').removeClass('active');
		$('#menu > li > ul').removeClass('in collapse');
		$('#menu > li > ul').removeAttr('style');
	}
}

// Set last page opened on the menu
function bindButtonClick() {	

	$('a[data-toggle=\'tooltip\'], td a :not(.breadcrumb a)').on('click', function(e) {
		sessionStorage.setItem('menu', $(this).attr('href'));
		$(this).tooltip('destroy');
		if(AJAX_MODE) {
			var way = $(this).attr('href').replace('#','');
			$.ajax({
				url: HTTP_SERVER + 'way='+ way +'&sign=' + new_localStorage.get('sign'),
				dataType: 'html',
				beforeSend: function() {
					$('#content').html('Loading...');
				},
				complete: function() {
					//$('#content').html('');
				},
				success: function(html) {
					$('#content').html(html);
					bindButtonClick();
				},
				error 		: function (result, textStatus, xhr) {
					alert('textStatus : '+ textStatus + '\nresult : ' + JSON.stringify(result) + '\nxhr : ' + xhr);
				}
			});
			e.preventDefault();
			return false;
		}
	});
	
	$(document).delegate('a[data-toggle=\'tooltip\']','click', function(e) {
		sessionStorage.setItem('menu', $(this).attr('href'));
		$(this).tooltip('destroy');
		if(AJAX_MODE) {
			var way = $(this).attr('href').replace('#','');
			$.ajax({
				url: HTTP_SERVER + 'way='+ way +'&sign=' + new_localStorage.get('sign'),
				dataType: 'html',
				beforeSend: function() {
					$('#content').html('Loading...');
				},
				complete: function() {
					//$('#content').html('');
				},
				success: function(html) {
					$('#content').html(html);
					bindButtonClick();
				},
				error 		: function (result, textStatus, xhr) {
					alert('textStatus : '+ textStatus + '\nresult : ' + JSON.stringify(result) + '\nxhr : ' + xhr);
				}
			});
			e.preventDefault();
			return false;
		}
	});
	
	$('form:not(#form-backup, #form-restore)').on('submit', function(e) {
		$('button').tooltip('destroy');
		if(AJAX_MODE) {
		
			var way  = $(this).attr('action').replace('#','');
			$.ajax({
				url: HTTP_SERVER + 'way='+ way +'&sign=' + new_localStorage.get('sign'),
				data		: $(this).serialize(),
				type		: 'POST',
				contenType 	: false,
				processData	: false,
				cache		: false,
				beforeSend: function() {
					$('#content').html('Loading...');
				},
				complete: function() {
					//$('#content').html('');
				},
				success: function(html) {
					$('#content').html(html);
					bindButtonClick();
				},
				error 		: function (result, textStatus, xhr) {
					alert('textStatus : '+ textStatus + '\nresult : ' + JSON.stringify(result) + '\nxhr : ' + xhr);
				}
			
			});
			return false;
		}
	});
	
	// tooltips on hover
	$('[data-toggle=\'tooltip\']').tooltip({container: 'body', html: true});

	// Makes tooltips work on ajax generated content
	$(document).ajaxStop(function() {
		$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});
	});	
	
	//Form Submit for IE Browser
	$('button[type=\'submit\']').on('click', function() {
		$("form[id*='form-']").submit();
	});

	// Highlight any found errors
	$('.text-danger').each(function() {
		var element = $(this).parent().parent();
		
		if (element.hasClass('form-group')) {
			element.addClass('has-error');
		}
	});
	
	

}

(function($) {
	function Autocomplete(element, options) {
		this.element = element;
		this.options = options;
		this.timer = null;
		this.items = new Array();

		$(element).attr('autocomplete', 'off');
		$(element).on('focus', $.proxy(this.focus, this));
		$(element).on('blur', $.proxy(this.blur, this));
		$(element).on('keydown', $.proxy(this.keydown, this));

		$(element).after('<ul class="dropdown-menu"></ul>');
		$(element).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));
	}

	Autocomplete.prototype = {
		focus: function() {
			this.request();
		},
		blur: function() {
			setTimeout(function(object) {
				object.hide();
			}, 200, this);
		},
		click: function(event) {
			event.preventDefault();

			value = $(event.target).parent().attr('data-value');

			if (value && this.items[value]) {
				this.options.select(this.items[value]);
			}
		},
		keydown: function(event) {
			switch(event.keyCode) {
				case 27: // escape
					this.hide();
					break;
				default:
					this.request();
					break;
			}
		},
		show: function() {
			var pos = $(this.element).position();

			$(this.element).siblings('ul.dropdown-menu').css({
				top: pos.top + $(this.element).outerHeight(),
				left: pos.left
			});

			$(this.element).siblings('ul.dropdown-menu').show();
		},
		hide: function() {
			$(this.element).siblings('ul.dropdown-menu').hide();
		},
		request: function() {
			clearTimeout(this.timer);

			this.timer = setTimeout(function(object) {
				object.options.source($(object.element).val(), $.proxy(object.response, object));
			}, 200, this);
		},
		response: function(json) {
			html = '';

			if (json.length) {
				for (i = 0; i < json.length; i++) {
					this.items[json[i]['value']] = json[i];
				}

				for (i = 0; i < json.length; i++) {
					if (!json[i]['category']) {
						html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
					}
				}

				// Get all the ones with a categories
				var category = new Array();

				for (i = 0; i < json.length; i++) {
					if (json[i]['category']) {
						if (!category[json[i]['category']]) {
							category[json[i]['category']] = new Array();
							category[json[i]['category']]['name'] = json[i]['category'];
							category[json[i]['category']]['item'] = new Array();
						}

						category[json[i]['category']]['item'].push(json[i]);
					}
				}

				for (i in category) {
					html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

					for (j = 0; j < category[i]['item'].length; j++) {
						html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
					}
				}
			}

			if (html) {
				this.show();
			} else {
				this.hide();
			}

			$(this.element).siblings('ul.dropdown-menu').html(html);
		}
	};

	$.fn.autocomplete = function(option) {
		return this.each(function() {
			var data = $(this).data('autocomplete');

			if (!data) {
				data = new Autocomplete(this, option);

				$(this).data('autocomplete', data);
			}
		});
	}
})(window.jQuery);