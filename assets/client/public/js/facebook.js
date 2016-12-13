// Load the SDK asynchronously
  (function(thisdocument, scriptelement, id) {
    var js, fjs = thisdocument.getElementsByTagName(scriptelement)[0];
    if (thisdocument.getElementById(id)) return;
	
    js = thisdocument.createElement(scriptelement); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
	
  window.fbAsyncInit = function() {
  FB.init({
    appId      : '1543900452570619', //Your APP ID
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.1' // use version 2.1
  });

  // These three cases are handled in the callback function.
  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };
	
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
	  _i();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    }
  }  
  
  function facebooklogin() {
	FB.login(function(response) {
	   // handle the response
	   if(response.status==='connected') {
		_i();
	   }
	 }, {scope: 'public_profile,email'});
 }
 
 function _i(){
	 FB.api('/me', function(response) {
		 console.log(response);
		 if($('#buttonfacebookAPI').attr('status')=='click'){
			parsesign(response,'facebook');
		 }
	});
 }
 
 
 function parsesign(datapost,APItype){
	$.ajax({
		url: 'index.php?way=public/index/signAPI&APItype='+APItype,
		data: datapost,
		type: 'post',
		beforeSend: function(){
			$('#waitfor-'+APItype).html('redirecting ...');
		},
		success: function(json){
			window.location = json;
		}
	}).done(function(data){
		//$('#waitfor-google').html('GOOGLE');
	});
	
}