$( document ).ready(function() {
	$('#deconnexion').click(function(){
		$.removeCookie('user', { path: '/' });
		$.removeCookie('token', { path: '/' });
		window.location.href = '/';
	});
});