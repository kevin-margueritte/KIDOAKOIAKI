(function(){
	var app = angular.module('formulaireInscription',['ngCookies']);

	app.controller('formInscription', function($scope, $http, $cookies, $cookieStore) {

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
		
		$scope.formValidation = function() {
			$scope.messageFail = '';
			$scope.messageSucces = '';

			var json = {content : null}

			var rqt = {
				method : 'POST',
				url : '/manager/creationCompte.php',
				data : $.param({pseudo : $scope.pseudo, mdp : $scope.mdp, email : $scope.email }),  
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){
				if (data.error) {
					$scope.messageFail = data.code;
				}
				else {
					$scope.messageSucces = data.code;
					window.location.href = '/connexion'
				}
			});

    	};
  	});
})();

jQuery(document).ready(function () {
    $('#password').pwstrength({
        ui: { showVerdictsInsideProgressBar: true }
    });
});