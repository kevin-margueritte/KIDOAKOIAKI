<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Création pot | KI DOA KOI A KI</title>
		<?php include($_SERVER['DOCUMENT_ROOT']."/include/index.php") ?>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    </head>
    <body ng-app="creationPot" id="creationPotEtape3">
    	<div class ="wrapper">
		    <?php include($_SERVER['DOCUMENT_ROOT']."/header/headerModeConnecte.php") ?>
		    <div class="descrPot">
	            <div class="titresPot">
	                <p id="titreCreation">Création du pot</p>
	                <p id="titreDescription">Etape 3/3, ajoutez des objets à votre pot et affectez vos amis</p>
	            </div>
	        </div>
		    <div class="input-group" id="divCreationPot">
		        <form method="post" id="formCreationPot" ng-controller="formCreationPotEtape3 as form">
		        	<div id="listeObjets">
		        		<p id="titreObjets">Objets ajoutés</p>
		        	</div>
		            <div id="ajoutNomObjet">
		                <p>Nom</p>
		                <input type="text" ng-model="nom" name="nom" class="form-control" aria-describedby="basic-addon1">
		            </div>
		            <div id="ajoutPrixObjet">
		                <p>Prix</p>
		                <input type="number" min="0" step="0.01" max="2500" value="0" ng-model="prix" name="prix" class="form-control" aria-describedby="basic-addon1">
		            </div>
		            <div class="clearfix"></div>
		            <div class="alert alert-success" role="alert" ng-show="messageSucces"><p>{{messageSucces}}</p></div>
		            <div class="alert alert-danger" role="alert" ng-show="messageFail"><p>{{messageFail}}</p></div>
		            <button type="button" class="btn btn-default center-block" id="buttonPrevisualisation" ng-click='ajoutObjet()'>
		            	<p class="btn btn-lg" id="pPrevisualisation">Ajouter cet objet</p>
		            </button>
		            <button class="btn btn-default center-block" id="terminer" ng-click='terminer'>
		            	<p class="btn btn-lg" role="button">Terminer</p>
		            </button>
		        </form>
		    </div>
		</div>
	    <?php include($_SERVER['DOCUMENT_ROOT']."/footer/index.php") ?>
    </body>
    <?php include($_SERVER['DOCUMENT_ROOT']."/include/script.php") ?>
    <script src="/creationPot/script.js"></script>
</html>