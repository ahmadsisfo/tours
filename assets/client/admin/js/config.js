var LINK_BASE    = LINK.replace('assets/client/','');
var HTTP_SERVER	 = LINK_BASE + '?ajax_mode&';
var HTTPS_SERVER = LINK_BASE + '?';
var SERVER       = (AJAX_MODE)? HTTP_SERVER : HTTPS_SERVER;

function modifDOM() {
	$('#submit-form-restore').remove();
	$('#form-restore input[name=\'import\']').parent().html('<a id="submit-form-restore" data-toggle="tooltip" title="add file restore" class="btn btn-default"><i class="fa fa-upload"></i></a>');
}