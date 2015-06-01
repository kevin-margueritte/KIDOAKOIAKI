(function(){
	var app = angular.module('gestionPot',['ngCookies','ngSanitize']);

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

	app.controller('vuePot', function($scope, $http, $cookies, $cookieStore, $sce) {

		$scope.messageSuccesModifObjet ='';
		$scope.messageFailModifObjet ='';
		$scope.messageSuccesModifInfo = '';
		$scope.messageFailModifInfo = '';
		this.afficherDescPot = false; 
		$scope.ajoutObjet = false;
		$scope.ajoutAmi = false;

		var numPot = getUrlParameter('id');

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
			
			var json = {content : null}

			var rqt = {
				method : 'POST',
				url : '/manager/autorisationModification.php',
				data : $.param({idPot : numPot, user: $cookieStore.get('user'), token: $cookieStore.get('token')}),  
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){
				if (data.error) {
					$scope.modeConnecte = false;
				}
				else {
					$scope.modeConnecte = true;
					$("#crayon").show();
				}
				initialisationPage();
			});
		});

		function initialisationPage() {
			$('#summernote').summernote();

			var json = {content : null}

			var rqt = {
				method : 'POST',
				url : '/manager/lecturePot.php',
				data : $.param({idPot : numPot}),  
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){
				if ( !data.error) {
					$scope.titrePot = $sce.trustAsHtml(data.titre);
					$scope.descriptionPot = $sce.trustAsHtml(data.description);
					$scope.montantTotalRembourse = data.montantTotalRembourse + " €/" + data.montantTotalObjet +" €";

					$('#listeObjet').append(function() {
						var index;
						var html='';
						if (data.objet != null) {
							for (index = 0; index < data.objet.length; ++index) {
								html += '<div class="descriptionObjet" prixObjet="'+data.objet[index]['prix']+ '" nomObjet="'+data.objet[index]['nom']+'" idObjet="'+data.objet[index]['id']+'">'+
								'<span id="nomObjet">'+data.objet[index]['nom']+'</span>'+
								'<span id="prix">'+ data.objet[index]['montantTotalRembourse']+' €/'+data.objet[index]['prix']+ ' €</span>';
								if ($scope.modeConnecte) {
									html += '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>' +
								'<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
								}
								html += '</div>'
							}
						}
						return html;
					});

					$('#listeAmis').append(function() {
						var index;
						var html='';
						for (index = 0; index < data.listAmis.length; ++index) {
							html += '<div class="popAmis clearfix" nomAmi="'+data.listAmis[index]['nom']+'"><span id="nomAmis">'+data.listAmis[index]['nom']+'</span>'+
							'<span id="prixDu">doit '+data.listAmis[index]['montantTotal']+ ' €</span>';
							if ($scope.modeConnecte) {
								html += '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
							}
							html += '</div>';
						}
						return html;
					});
					activerChevronUp();
				}
			});
		}

		$(document).delegate('.descriptionObjet .glyphicon-pencil', 'click', function() {
			var idObjet = $(this).parent().attr('idobjet');
			var prixObjet = $(this).parent().attr('prixObjet');
			var nomObjet = $(this).parent().attr('nomObjet');


			if( $('.checkboxAmis').length == 0 ) {
				$('div[idobjet='+idObjet+']').append(function() {
					function getListAmis() {
						var listAmis = '';
						var rqt = {
							method : 'POST',
							url : '/manager/lectureModificationObjet.php',
							data : $.param({idObjet : idObjet, idPot : numPot, user : $cookieStore.get('user'), token : $cookieStore.get('token')}),
							headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
						};

						$http(rqt).success(function(data){
							if (!data.error) {
								var index;
								var listeAmisHtml ='<p class="titreModification">Cliquez pour affecter vos amis</p>';
								for (index = 0; index < data.listAmis.length; ++index) {
									var prix = data.listAmis[index]['montant'];
									var checked ='';
									if (data.listAmis[index]['affecte']) {
										checked = 'checked="checked"';
									}
									listeAmisHtml += '<div class="listeAmis" id="'+ data.listAmis[index]['nom'] +'">'+
										'<input type="checkbox"'+checked+' id="checkboxConfAmis'+ data.listAmis[index]['nom'] +'" name="checkboxConfAmis"><span>'+ data.listAmis[index]['nom'] +'</span>' +
										'<input type="number" min="0" step="0.01" max="2500" value="'+prix+'" name="'+ data.listAmis[index]['nom'] +'" class="form-control" aria-describedby="basic-addon1"></div>';
								}
								listeAmisHtml += 
									'<button type="button" class="btn btn-default center-block" id="equerepartir" >' + 
										'<p class="btn btn-lg">Equirepartir</p>' +
									'</button></div>' + 
									'<button type="button" idObjet="'+ idObjet +'" class="btn btn-default center-block" id="modifierObjet">' + 
										'<p class="btn btn-lg">Enregistrer</p>' +
									'</button></div>';
								$('.checkboxAmis').append(function() {
									return listeAmisHtml;
								});
							}
						});
					};

					getListAmis();

					var html = '<div id="confNomObjet">' +
		                '<p class="titreModification">Modifier le nom</p>' +
		                '<input type="text" class="form-control" aria-describedby="basic-addon1" id="modifNom" idObjet="'+idObjet+'" value="' + nomObjet + '">' +
		                '<p class="titreModification">Modifier le prix</p>' + 
		                '<input type="number" min="0" step="0.01" max="2500" value="' + prixObjet + '"id="inputModifPrix" name="prix" class="form-control" aria-describedby="basic-addon1">' +
		            '</div><div class="checkboxAmis clearfix"></div>'

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
							nbModif++;
							$("#" + inputPrix + " input[name='"+inputPrix+"']").val(prixunitaire);
							if (nbModif == nbCheckboxChecked  && prixunitaire*nbCheckboxChecked < prixObjet) {
								console.debug(parseFloat(prixunitaire) + parseFloat(0.01));
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

		$(document).delegate('#modifierObjet', 'click', function() {
			var json = {content : null}
			var idObjet = $(this).attr('idObjet');
			var newNom = $("#modifNom").val();
			var newPrix = $("#inputModifPrix").val();

			var rqt = {
				method : 'POST',
				url : '/manager/modificationObjet.php',
				data : $.param({nomObjet: newNom ,montantObjet: newPrix ,idObjet: idObjet ,idPot : numPot, user: $cookieStore.get('user'), token: $cookieStore.get('token')}),  
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){

				if ( !data.error) {
					$("div[idobjet='"+idObjet+"'] #nomObjet").text(newNom);
					$("div[idobjet='"+idObjet+"']").attr("prixobjet", newPrix);
					$("div[idobjet='"+idObjet+"']").attr("nomobjet", newNom);
					$("div[idobjet='"+idObjet+"'] #prix").text((newPrix - data.montantTotal) + " €/" + newPrix +" €");

					$(".listeAmis").each(function(){
						var inputPrix = $(this).attr('id');
						if ( $("#" + inputPrix + " #checkboxConfAmis"+inputPrix).is(":checked") ) {
							var json = {content : null};
							var idAmi = inputPrix;
							var idObj = $("#modifNom").attr('idObjet');
							var montant = $("#" + inputPrix + " input[name='"+inputPrix+"']").val();
							montant = parseFloat(montant).toFixed(2);
							/*$("div[idobjet='"+idObjet+"'] #prix").text(data.montantTotalObjet + " €/" + newPrix +" €");
							$scope.montantTotalRembourse = data.montantTotalTransactionPot + " €/" + data.montantTotalObjetPot +" €";*/

							var rqt = {
								method : 'POST',
								url : '/manager/creationTransaction.php',
								data : $.param({idObjet : idObj, idAmi : idAmi, montant : montant,  user : $cookieStore.get('user'), token : $cookieStore.get('token')}),
								headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
							};

							$http(rqt).success(function(data){
								if (data.error) {
									$scope.messageSuccesModifObjet = '';
									$scope.messageFailModifObjet = data.code;
								}
								else {
									$("div[idobjet='"+idObjet+"'] #prix").text(data.montantTotalObjetRembourse + " €/" + newPrix +" €");
									$scope.montantTotalRembourse = data.montantTotalDuPot + " €/" + data.montantTotalObjetPot +" €";
									refreshAmi();
								}
							});
						}
						/*else {
							console.debug("call");
							$("div[idobjet='"+idObjet+"'] #prix").text("0 €/" + newPrix +" €");
						}*/
					});
				}
				else {
					$scope.messageSuccesModifObjet = '';
					$scope.messageFailModifObjet = data.code;
				}
				fermerFormulaireTransaction();
			});
		});

		function fermerFormulaireTransaction() {
			$('.checkboxAmis').remove();
			$('#confNomObjet').remove();
			$scope.messageSuccesModifObjet = 'La modification a bien été effectuée';
			$scope.messageFailModifObjet = '';
		}

		$scope.modifierInfoPot = function() {
			if ( !$scope.afficherDescPot ) {
				$scope.afficherDescPot = true;
				$('#summernote').code($("#descriptionPot").html());
				$scope.titre = $("#titrePot p").html();
			}
			else {
				$scope.afficherDescPot = false;
			}
		}

		$scope.modifierInfo = function() {
			var titre = $scope.titre;
			var description = $('#summernote').code();

			var json = {content : null}

			var rqt = {
				method : 'POST',
				url : '/manager/modifierInfoPot.php',
				data : $.param({titre : titre, description : description, idPot : numPot, user: $cookieStore.get('user'), token: $cookieStore.get('token')}),  
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){
				if ( data.error) {
					$scope.messageSuccesModifInfo = '';
					$scope.messageFailModifInfo = data.code;
				}
				else {
					$scope.messageSuccesModifInfo = data.code;
					$scope.messageFailModifInfo = '';
					$scope.afficherDescPot = false;
					$scope.titrePot = $sce.trustAsHtml(titre);
					$scope.descriptionPot = $sce.trustAsHtml(description);
				}
			});
		}

		$scope.afficherAmi = function() {
			if (!$scope.ajoutAmi) {
				$scope.ajoutAmi = true;
			}
			else {
				$scope.ajoutAmi = false;
			}
		}

		$scope.afficherObjet = function() {
			if (!$scope.ajoutObjet) {
				$scope.ajoutObjet = true;
			}
			else {
				$scope.ajoutObjet = false;
			}
		}

		$scope.ajouterAmi = function() {
			var json = {content : null}

			var rqt = {
				method : 'POST',
				url : '/manager/ajouterAmi.php',
				data : $.param({idPot : numPot, nomAmi : $scope.nomAmi, user: $cookieStore.get('user'), token: $cookieStore.get('token')}),  
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){
				if ( data.error) {
					$scope.messageSuccesAjoutAmi = '';
					$scope.messageFailAjoutAmi = data.code;
				}
				else {
					$scope.messageSuccesAjoutAmi = data.code;
					$scope.messageFailAjoutAmi = '';
					$scope.ajoutAmi = false;
					refreshAmi();
				}
			});
		}

		function refreshAmi() {
			var json = {content : null}

			var rqt = {
				method : 'POST',
				url : '/manager/informationsAmis.php',
				data : $.param({idPot : numPot, user: $cookieStore.get('user'), token: $cookieStore.get('token')}),  
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){
				$('.popAmis').remove();
				$('#listeAmis').append(function() {
					var index;
					var html='';
					for (index = 0; index < data.listAmis.length; ++index) {
						html += '<div class="popAmis clearfix" nomAmi="'+data.listAmis[index]['nom']+'"><span id="nomAmis">'+data.listAmis[index]['nom']+'</span>'+
							'<span id="prixDu">doit '+data.listAmis[index]['montantTotal']+ ' €</span>'+
							'<span class="glyphicon glyphicon-remove" aria-hidden="true"></span></div>';
					}
					return html;
				});
			});
		}

		$scope.activerChevronUp = function() {
			$("#chevronDown").hide();
			$("#chevronUp").show();
			$("#modificationAmi").show();
			$("#modificationObjet").show();
			$("#montantRembourse").show();
		}

		function activerChevronUp() {
			$("#chevronDown").show();
			$("#chevronUp").hide();
			$("#modificationObjet").hide();
			$("#modificationAmi").hide();
			$("#montantRembourse").hide();
		}

		$scope.activerChevronDown = function() {
			activerChevronUp();
		}

		$(document).delegate('.popAmis .glyphicon-remove', 'click', function() {
			var json = {content : null};

			var nom = $(this).parent().attr('nomami');

			var rqt = {
				method : 'POST',
				url : '/manager/supressionAmi.php',
				data : $.param({idPot : numPot, nomAmi : nom, user : $cookieStore.get('user'), token : $cookieStore.get('token')}),
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){
				if (data.error) {
					$scope.messageSuccesAjoutAmi = '';
					$scope.messageFailAjoutAmi = data.code;
				}
				else {
					$scope.montantTotalRembourse = data.montantTotalRemboursePot + " €/" + data.montantTotalObjetPot +" €";
					$scope.messageSuccesAjoutAmi = data.code;
					$scope.messageFailAjoutAmi = '';
					refreshObjet();
					$("div[nomami='"+nom+"']").remove();

				}
			});
		});

		$(document).delegate('.descriptionObjet .glyphicon-remove', 'click', function() {
			var json = {content : null};

			var idObjet = $(this).parent().attr('idobjet');

			var rqt = {
				method : 'POST',
				url : '/manager/supressionObjet.php',
				data : $.param({idPot : numPot, idObjet : idObjet, user : $cookieStore.get('user'), token : $cookieStore.get('token')}),
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){
				if (data.error) {
					$scope.messageSuccesModifObjet = '';
					$scope.messageFailModifObjet = data.code;
				}
				else {
					$scope.montantTotalRembourse = data.montantTotalRemboursePot + " €/" + data.montantTotalObjetPot +" €";
					$scope.messageSuccesModifObjet = data.code;
					$scope.messageFailModifObjet = '';
					$("div[idobjet='"+idObjet+"']").remove();
					refreshAmi();
				}
			});
		});

		function refreshObjet() {

			var json = {content : null};
			$(".descriptionObjet").remove();

			var rqt = {
				method : 'POST',
				url : '/manager/lectureObjet.php',
				data : $.param({idPot : numPot, user: $cookieStore.get('user'), token: $cookieStore.get('token')}),  
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){
				if ( !data.error) {
					$('#listeObjet').append(function() {
						var index;
						var html='';
						if (data.objet != null) {
							for (index = 0; index < data.objet.length; ++index) {
								html += '<div class="descriptionObjet" prixObjet="'+data.objet[index]['prix']+ '" nomObjet="'+data.objet[index]['nom']+'" idObjet="'+data.objet[index]['id']+'">'+
								'<span id="nomObjet">'+data.objet[index]['nom']+'</span>'+
								'<span id="prix">'+ data.objet[index]['montantTotalRembourse']+' €/'+data.objet[index]['prix']+ ' €</span>';
								if ($scope.modeConnecte) {
									html += '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>' +
								'<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
								}
								html += '</div>'
							}
						}
						return html;
					});
				}
			});
		}

		$scope.ajouterObjet = function() {
			var json = {content : null};

			var rqt = {
				method : 'POST',
				url : '/manager/creationObjet.php',
				data : $.param({nomObjet : $scope.nomObjet, prixObjet : $scope.prixObjet, idPot : getUrlParameter('id'),  user : $cookieStore.get('user'), token : $cookieStore.get('token')}),
				headers : { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
			};

			$http(rqt).success(function(data){
				//$("#listeObjet").append(function() {
					var ajoutHTMLSucces = false;
					if (data.error) {
						$scope.messageSuccesModifObjet = '';
						$scope.messageFailModifObjet = data.code;
					}
					else {
						$scope.messageSuccesModifObjet = data.code;
						$scope.messageFailModifObjet = '';
						$scope.ajoutObjet = false;
						/*ajoutHTMLSucces = '<div class="descriptionObjet" prixObjet="'+ $scope.prixObjet+ '" nomObjet="'+$scope.nomObjet+'" idObjet="'+data.idObjet+'">'+
						'<span id="nomObjet">'+$scope.nomObjet+'</span>'+
							'<span id="prix">'+ data.montantTotalRembourse +' €/'+ $scope.prixObjet+ ' €</span>' +
							'<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>' +
							'<span class="glyphicon glyphicon-remove" aria-hidden="true"></span></div>';
						$scope.ajoutObjet=false;*/
						refreshObjet();
					}
					//return ajoutHTMLSucces;
				//});
			});
		}

  	});
})();

$( document ).ready(function() {
    $("#chevronUp").hide();
    $("#crayon").hide();
});