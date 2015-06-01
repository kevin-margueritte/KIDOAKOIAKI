(function(){
	var app = angular.module('formulaireModificationCompte',['ngCookies']);

	app.controller('formModification', function($scope, $http, $cookies, $cookieStore) {

		angular.element(document).ready(function () {
			$scope.messageFail = '';
			$scope.messageSucces = '';

			var json = {content : null}

			var rqt = {
				method : 'POST',
				url : '/manager/lireMailPersonne.php',
				data : $.param({user: $cookieStore.get('user'), token: $cookieStore.get('token')}),  
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){
				if (data.error) {
					window.location.href = '/';
				}
				else {
					$("#email").val(data.mail);
				}
			});
		});
		
		$scope.formValidation = function() {
			$scope.messageFail = '';
			$scope.messageSucces = '';
			var email = $("#email").val();

			var json = {content : null}

			var rqt = {
				method : 'POST',
				url : '/manager/modificationUtilisateur.php',
				data : $.param({user: $cookieStore.get('user'), token: $cookieStore.get('token'), ancienMdp: $scope.ancienMdp, newMdp: $scope.newMdp, mail: email}),  
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){
				if (data.error) {
					$scope.messageFail = data.code;
				}
				else {
					$scope.messageSucces = data.code;
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