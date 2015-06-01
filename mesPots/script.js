(function(){
	var app = angular.module('mesPots',['ngCookies']);

	app.controller('vuePot', function($scope, $http, $cookies, $cookieStore) {

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
					window.location.href = '/';
				}
			});

			var json = {content : null}

			var rqt = {
				method : 'POST',
				url : '/manager/lectureTousPots.php',
				data : $.param({user: $cookieStore.get('user'), token: $cookieStore.get('token')}),  
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){
				if (!data.error) {
					$('#vuePot').append(function() {
						var index;
						var html='';
						if (data.listePots != null) {
							for (index = 0; index < data.listePots.length; ++index) {
								html += '<div class="affichagePot" onclick="location.href=\'/pot/lecture/?id='+ data.listePots[index]['id'] +'\'">'+
								'<span id="titrePot">'+data.listePots[index]['titre']+'</span>' +
								'<p class="glyphicon glyphicon-eye-open" aria-hidden="true"></p></div>';
							}
						}
						return html;
					});
				}
				else {
					window.location.href = '/';
				}
			});
		})
	});
})();