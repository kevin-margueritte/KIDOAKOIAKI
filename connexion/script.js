(function(){
	var app = angular.module('formulaireConnexion',['ngCookies']);

	app.controller('formConnexion', function($scope, $http, $cookies, $cookieStore) {

		angular.element(document).ready(function () {
			var json = {content : null}

			var rqt = {
				method : 'POST',
				url : '/manager/estConnecte.php',
				data : $.param({user: $cookieStore.get('user'), token: $cookieStore.get('token')}),  
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){
				if (data.connecte) {
					window.location.href = '/';
				}
			});
		});

		$scope.messageSucces = '';
		
		$scope.formValidation = function() {
			$scope.messageFail = '';

			var json = {content : null}

			var rqt = {
				method : 'POST',
				url : '/manager/connexion.php',
				data : $.param({pseudo : $scope.pseudo, mdp : $scope.mdp}),  
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){
				if (data.error) {
					$scope.messageFail = data.code;
				}
				else {
					$cookieStore.remove('token');
					$cookieStore.remove('user');
					/*$cookieStore.put('user', $scope.pseudo);
					$cookieStore.put('token', data.token);*/

					//La version 1.3 et inférieur de angular ne prend pas en compte la configuration du path sur un cookie, le problème a été résolu en 1.4 beta mais ne fonctionnant pas
					var cookieConfig = {
						path    : '/',
					};
					$.cookie.raw = true;
					var user = '%22' + $scope.pseudo + '%22';
					var token = '%22' + data.token + '%22'
					//console.debug(user);
					$.cookie("user", user , cookieConfig );
					$.cookie('token', token , cookieConfig );
					window.location.href = '/pot/liste';
				}
			});

    	}

    	$scope.demandeReinit = function(){
    		$scope.messageFail = '';

			var json = {content : null}

			var rqt = {
				method : 'POST',
				url : '/manager/demandeReinitialisationMDP.php',
				data : $.param({pseudo : $scope.pseudo}),  
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			}

			$http(rqt).success(function(data){
				if (data.error) {
					$scope.messageFail = data.code;
				}
				else {
					$scope.messageSucces = data.code;
				}
    		})
    	}

  	});
})();