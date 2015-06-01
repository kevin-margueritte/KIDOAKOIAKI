<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Création pot | KI DOA KOI A KI</title>
		<?php include($_SERVER['DOCUMENT_ROOT']."/include/index.php") ?>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    </head>
    <body ng-app="creationPot" id="creationPotEtape2">
    	<div class ="wrapper">
		    <?php include($_SERVER['DOCUMENT_ROOT']."/header/headerModeConnecte.php") ?>
		    <div class="descrPot">
	            <div class="titresPot">
	                <p id="titreCreation">Création du pot</p>
	                <p id="titreDescription">Etape 2/3, ajoutez vos amis</p>
	            </div>
	        </div>
		    <div class="input-group" id="divCreationPot">
		        <form method="post" id="formCreationPot" ng-controller="formCreationPotEtape2 as form">
		        	<div id="listeAmis">
		        		<p id="titrePopAmis">Amis ajoutés</p>
		        	</div>
		            <div id="ajoutAmis">
		                <p>Nom</p>
		                <input type="text" ng-model="nom" name="nom" class="form-control" aria-describedby="basic-addon1">
		            </div>
		            <div class="alert alert-success" role="alert" ng-show="messageSucces"><p>{{messageSucces}}</p></div>
		            <div class="alert alert-danger" role="alert" ng-show="messageFail"><p>{{messageFail}}</p></div>
		            <button type="button" class="btn btn-default center-block" id="buttonPrevisualisation" ng-click='ajoutAmi()'>
		            	<p class="btn btn-lg" id="pPrevisualisation">Ajouter cette personne</p>
		            </button>
		            <button class="btn btn-default center-block" id="buttonEtape2" ng-click='passEtape3()'>
		            	<p class="btn btn-lg" role="button">Passer à l'étape suivante 3/3</p>
		            </button>
		        </form>
		    </div>
		</div>
	    <?php include($_SERVER['DOCUMENT_ROOT']."/footer/index.php") ?>
    </body>
    <?php include($_SERVER['DOCUMENT_ROOT']."/include/script.php") ?>
    <script src="/creationPot/script.js"></script>
</html>