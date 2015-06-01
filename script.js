(function(){
	var app = angular.module('moduleAcceuil',['ngCookies']);

	app.controller('controllerAcceuil', function($scope, $http, $cookies, $cookieStore) {

		angular.element(document).ready(function () {
			var json = {content : null}

			var rqt = {
				method : 'POST',
				url : '/manager/estConnecte.php',
				data : $.param({user: $cookieStore.get('user'), token: $cookieStore.get('token')}),  
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){
				if (!data.connecte) {
					$('#modeConnecte').remove();
				}
				else {
					$('#modeNonConnecte').remove();
				}
			});
		});
	});
})();