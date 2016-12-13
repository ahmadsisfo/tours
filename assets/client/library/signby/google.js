(function() {
    var po = document.createElement('script');
    po.type = 'text/javascript'; po.async = true;
    po.src = 'https://plus.google.com/js/client:plusone.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(po, s);
  })();
  
  
var gpclass = (function(){

//Defining Class Variables here
var response = undefined;
return {
	//Class functions / Objects
	
	mycoddeSignIn:function(response){
		// The user is signed in
		if (response['access_token']) {
		
			//Get User Info from Google Plus API
			gapi.client.load('plus','v1',this.getUserInformation);
			
		} else if (response['error']) {
			// There was an error, which means the user is not signed in.
			//alert('There was an error: ' + authResult['error']);
		}
	},
	
	getUserInformation: function(){
		var request = gapi.client.plus.people.get( {'userId' : 'me'} );
		request.execute( function(profile) {
			var email = profile['emails'].filter(function(v) {
				return v.type === 'account'; // Filter out the primary email
			})[0].value;
			var fName = profile.displayName;
			$("#inputFullname").val(fName);
			$("#inputEmail").val(email);
		});
	}

}; //End of Return
})();

function mycoddeSignIn(gpSignInResponse){
	gpclass.mycoddeSignIn(gpSignInResponse);
}