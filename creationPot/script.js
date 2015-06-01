(function(){
	var app = angular.module('creationPot',['ngCookies']);

  	function getUrlParameter(sParam) { ///http://stackoverflow.com/questions/19491336/get-url-parameter-jquery
	    var sPageURL = window.location.search.substring(1);
	    var sURLVariables = sPageURL.split('&');
	    for (var i = 0; i < sURLVariables.length; i++) 
	    {
	        var sParameterName = sURLVariables[i].split('=');
	        if (sParameterName[0] == sParam) 
	        {
	            return sParameterName[1];
	        }
	    }
	}

	app.controller('formCreationPotEtape1', function($scope, $http, $cookies, $cookieStore) {

		$('#summernote').summernote();

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
		});

		$(document).delegate('#buttonPrevisualisation', 'click', function() {
			console.debug("call");
			$( "#previsualisation" ).empty();
			var titre = $("#titrePot").val();
			$( "#previsualisation" ).append( '<p id="titrePotPrevisualisation">'+titre+'<p>'+
				getWysiwyg()
			);
		});

		function getWysiwyg() {
			var sHTML = $('#summernote').code();
			return sHTML;
		}
		
		$scope.formValidation = function() {
			$scope.messageFail = '';
			console.debug($cookieStore.get('token'));

			var json = {content : null}

			var rqt = {
				method : 'POST',
				url : '/manager/creerInfoDescriptionPot.php',
				data : $.param({titre : $scope.titre, description : getWysiwyg(), user: $cookieStore.get('user'), token: $cookieStore.get('token')}),  
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){
				if (data.error) {
					$scope.messageFail = data.code;
				}
				else {
					window.location.href = '2?' + 'id=' + data.id;
				}
			});

    	};
  	});

  	app.controller('formCreationPotEtape2', function($scope, $http, $cookies, $cookieStore) {

  		$("#titrePopAmis").hide();

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
		});

  		var numPot = getUrlParameter('id');

  		$scope.passEtape3 = function() {
  			window.location.href = '3?' + 'id=' + numPot;
  		}

  		$scope.ajoutAmi = function() {

			var json = {content : null};

			var rqt = {
				method : 'POST',
				url : '/manager/ajouterAmi.php',
				data : $.param({nomAmi : $scope.nom, idPot : numPot,  user : $cookieStore.get('user'), token : $cookieStore.get('token')}),
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){
				$("#listeAmis").append(function() {
					var ajoutHTMLSucces = false;
					if (data.error) {
						$scope.messageSucces = '';
						$scope.messageFail = data.code;
					}
					else {
						$scope.messageSucces = data.code;
						$scope.messageFail = '';
						$("#titrePopAmis").show();
						ajoutHTMLSucces = '<div class="popAmi" id="'+ $scope.nom +'"><span>'+ $scope.nom +'</span> <span class="glyphicon glyphicon-remove" aria-hidden="true"></span></div>'
					}
					return ajoutHTMLSucces;
				});
			});
		}

		$(document).delegate('.glyphicon-remove', 'click', function() {
			var json = {content : null};

			var nom = $(this).parent().attr('id');

			var rqt = {
				method : 'POST',
				url : '/manager/supressionAmi.php',
				data : $.param({idPot : numPot, nomAmi : nom, user : $cookieStore.get('user'), token : $cookieStore.get('token')}),
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){
				if (data.error) {
					$scope.messageSucces = '';
					$scope.messageFail = data.code;
				}
				else {
					$scope.messageSucces = data.code;
					$scope.messageFail = '';
					$('#' + data.nom).remove();
					var nbAmis = $('.popAmi').length; ///compte le nombre d'amis ajoutés
				    if (nbAmis == 0) { //Si on a supprimé tout les amis on supprime la div "amis ajoutés"
				    	$("#titrePopAmis").hide();
				    }
				}
			});
		});
  	});

	app.controller('formCreationPotEtape3', function($scope, $http, $cookies, $cookieStore) {
		$("#titreObjets").hide();

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
		});

		$(document).delegate('#terminer', 'click', function() {
			window.location.href = '/pot/lecture/?id=' + getUrlParameter('id');
		});

  		$scope.ajoutObjet = function() {

			var json = {content : null};

			var rqt = {
				method : 'POST',
				url : '/manager/creationObjet.php',
				data : $.param({nomObjet : $scope.nom, prixObjet : $scope.prix, idPot : getUrlParameter('id'),  user : $cookieStore.get('user'), token : $cookieStore.get('token')}),
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){
				$("#listeObjets").append(function() {
					var ajoutHTMLSucces = false;
					if (data.error) {
						$scope.messageSucces = '';
						$scope.messageFail = data.code;
					}
					else {
						$scope.messageSucces = data.code;
						$scope.messageFail = '';
						$("#titreObjets").show();
						ajoutHTMLSucces = '<div class="popObjet" nom="' + $scope.nom + '" prix="'+ $scope.prix +'" id="'+ data.idObjet +'"><p id="nomPopObjet">'+ $scope.nom +'</p><p class="glyphicon glyphicon-pencil" aria-hidden="true"></p><p class="glyphicon glyphicon-remove" aria-hidden="true"></p></div>'
					}
					return ajoutHTMLSucces;
				});
			});
		}

		$(document).delegate('.glyphicon-remove', 'click', function() {
			var json = {content : null};
			var idObjet = $(this).parent().attr('id');
			var numPot = getUrlParameter('id');
			console.debug(idObjet);

			var rqt = {
				method : 'POST',
				url : '/manager/supressionObjet.php',
				data : $.param({idPot : numPot, idObjet : idObjet, user : $cookieStore.get('user'), token : $cookieStore.get('token')}),
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){
				if (data.error) {
					$scope.messageSucces = '';
					$scope.messageFail = data.code;
				}
				else {
					$scope.messageSucces = data.code;
					$scope.messageFail = '';
					$("#" + idObjet).remove();
				}
			});
		});

		$(document).delegate('#modifierObjet', 'click', function() {

			var json = {content : null}
			var idObjet = $(this).attr('idObjet');
			var newNom = $("#modifNom").val();
			var newPrix = $("#inputModifPrix").val();

			var rqt = {
				method : 'POST',
				url : '/manager/modificationObjet.php',
				data : $.param({nomObjet: newNom ,montantObjet: newPrix ,idObjet: idObjet ,idPot : getUrlParameter('id'), user: $cookieStore.get('user'), token: $cookieStore.get('token')}),  
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){
				if ( !data.error) {
					$("div[id='"+idObjet+"'] #nomPopObjet").text(newNom);
					$("div[id='"+idObjet+"']").attr("nom", newNom);
					$("div[id='"+idObjet+"']").attr("prix", newPrix);

					/////////////
					$(".listeAmis").each(function(){
						var inputPrix = $(this).attr('id');
						if ( $("#" + inputPrix + " #checkboxConfAmis"+inputPrix).is(":checked") ) {
							var json = {content : null};
							var idAmi = inputPrix;
							var idObj = $("#modifNom").attr('idObjet');
							var montant = $("#" + inputPrix + " input[name='"+inputPrix+"']").val();
							montant = parseFloat(montant).toFixed(2);

							var rqt = {
								method : 'POST',
								url : '/manager/creationTransaction.php',
								data : $.param({idObjet : idObj, idAmi : idAmi.replace("€"," "), montant : montant,  user : $cookieStore.get('user'), token : $cookieStore.get('token')}),
								headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
							};

							$http(rqt).success(function(data){
								if (data.error) {
									$scope.messageSucces = '';
									$scope.messageFail = data.code;
								}
							});
						}
					});
					$scope.messageSucces = 'La modification a bien été effectuée';
					$scope.messageFail = '';
					$('.checkboxAmis').remove();
					$('#confNomObjet').remove();
				}
				else {
					$scope.messageSucces = '';
					$scope.messageFail = data.code;
				}
			});
		});

		$(document).delegate('.glyphicon-pencil', 'click', function() {
			var idObjet = $(this).parent().attr('id');
			var prixObjet = $(this).parent().attr('prix');
			var nomObjet = $(this).parent().attr('nom');


			if( $('.checkboxAmis').length == 0 ) {

				$('#' + idObjet).append(function() {
					var idObjet = $(this).attr('id');

					function getListAmis() {
						var listAmis = '';

						var rqt = {
							method : 'POST',
							url : '/manager/lectureModificationObjet.php',
							data : $.param({idObjet : idObjet, idPot : getUrlParameter('id'), user : $cookieStore.get('user'), token : $cookieStore.get('token')}),
							headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
						};

						$http(rqt).success(function(data){
							if (!data.error) {
								var index;
								var prix;
								var listeAmisHtml ='<p>Cliquez pour affecter vos amis</p>';
								for (index = 0; index < data.listAmis.length; ++index) {
									prix = data.listAmis[index]['montant'];
									listeAmisHtml += '<div class="listeAmis" id="'+ data.listAmis[index]['nom'].replace(" ","€") +'">'+
										'<input type="checkbox" id="checkboxConfAmis'+ data.listAmis[index]['nom'].replace(" ","€") +'" name="checkboxConfAmis"><span>'+ data.listAmis[index]['nom'] +'</span>' +
										'<input type="number" min="0" step="0.01" max="2500" value="'+prix+'" name="'+ data.listAmis[index]['nom'].replace(" ","€") +'" class="form-control" aria-describedby="basic-addon1"></div>';
								}
								listeAmisHtml += 
									'<button type="button" class="btn btn-default center-block" id="equerepartir" >' + 
										'<p class="btn btn-lg">Equirepartir</p>' +
									'</button></div>' + 
									'<button type="button" class="btn btn-default center-block" idObjet="'+idObjet+'" id="modifierObjet">' + 
										'<p class="btn btn-lg">Enregistrer</p>' +
									'</button></div><div class="clearfix"></div>';

								$('.checkboxAmis').append(function() {
									return listeAmisHtml;
								});
							}
						});
					};

					getListAmis();

					var html = '<div id="confNomObjet">' +
		                '<p id="nomObjet">Modifier le nom</p>' +
		                '<input type="text" class="form-control" aria-describedby="basic-addon1" id="modifNom" idObjet="'+idObjet+'" value="' + nomObjet + '">' +
		                '<p id="prixObj">Modifier le prix</p>' + 
		                '<input type="number" min="0" step="0.01" max="2500" value="' + prixObjet + '"id="inputModifPrix" name="prix" class="form-control" aria-describedby="basic-addon1">' +
		            '</div><div class="checkboxAmis"></div>'

					return html;
				});

				$(document).delegate('#equerepartir', 'click', function() {
					var nbCheckboxChecked = $('.checkboxAmis').find('input[name="checkboxConfAmis"]:checked').length;
					var prixObjet = $('#confNomObjet #inputModifPrix').val();
					prixObjet = parseFloat(prixObjet).toFixed(2);
					var prixunitaire = parseFloat(prixObjet/nbCheckboxChecked).toFixed(2);
					var nbModif = 0; ///Permet le troncage des floats
					$(".listeAmis").each(function(){
						var inputPrix = $(this).attr('id');
						if ( $("#" + inputPrix + " #checkboxConfAmis"+inputPrix).is(":checked") ) {
							console.debug("check");
							nbModif++;
							$("#" + inputPrix + " input[name='"+inputPrix+"']").val(prixunitaire);
							if (nbModif == nbCheckboxChecked  && prixunitaire*nbCheckboxChecked < prixObjet) {
								$("#" + inputPrix + " input[name='"+inputPrix+"']").val(parseFloat(prixunitaire) + parseFloat(0.01));
							}
							if (nbModif == nbCheckboxChecked  && prixunitaire*nbCheckboxChecked > prixObjet) {
								$("#" + inputPrix + " input[name='"+inputPrix+"']").val(parseFloat(prixunitaire) - parseFloat(0.01));
							}
						}
						else {
							$("#" + inputPrix + " input[name='"+inputPrix+"']").val(0);
						}
					});
				});
			}
			else {
				$('.checkboxAmis').remove();
				$('#confNomObjet').remove();
			}
		});
	});
})();