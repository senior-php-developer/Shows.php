function() {
	FB.init({appId: '148223125225042', status: true, cookie: true, xfbml: true});
	FB.Event.subscribe('auth.logout', function(){
		window.location.reload();
	});
	FB.Event.subscribe('auth.sessionChange', function(response) {
    window.location.reload();
	});
}();
