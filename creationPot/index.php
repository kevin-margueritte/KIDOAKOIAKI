<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Création pot | KI DOA KOI A KI</title>
		<?php include($_SERVER['DOCUMENT_ROOT']."/include/index.php") ?>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
		<link href="/libraryJS/wysiwyg/summernote.css" rel="stylesheet">
		<script src="/libraryJS/wysiwyg/summernote.min.js"></script>
    </head>
    <body id="creationPotEtape1" ng-app="creationPot">
    	<div class ="wrapper">
		    <?php include($_SERVER['DOCUMENT_ROOT']."/header/headerModeConnecte.php") ?>
		    <div class="descrPot">
	            <div class="titresPot">
	                <p id="titreCreation">Création du pot</p>
	                <p id="titreDescription">Etape 1/3, création de votre pot</p>
	            </div>
	        </div>
		    <div class="input-group" id="divCreationPot">
		        <form method="post" id="formCreationPot" ng-controller="formCreationPotEtape1 as form" ng-submit="formValidation()">
		            <div id="titleCreationPot">
		                <p>Titre du pot</p>
		                <input type="text" ng-model="titre" name="titrePot" id="titrePot" class="form-control" aria-describedby="basic-addon1">
		            </div>
		            <div>
		                <p>Description du pot</p>
		                <div id="summernote">Entrez ici la description de votre pot</div>
		            </div>
		            <div id="previsualisation"></div>
		            <div class="alert alert-danger" role="alert" ng-show="messageFail"><p>{{messageFail}}</p></div>
		            <button type="button" class="btn btn-default center-block" id="buttonPrevisualisation">
		            	<p class="btn btn-lg" id="pPrevisualisation">Prévisualisation</p>
		            </button>
		            <button class="btn btn-default center-block" id="buttonEtape2">
		            	<p class="btn btn-lg" role="button">Passer à l'étape suivante 2/3</p>
		            </button>
		        </form>
		    </div>
		</div>
	    <?php include($_SERVER['DOCUMENT_ROOT']."/footer/index.php") ?>
    </body>
    <?php include($_SERVER['DOCUMENT_ROOT']."/include/script.php") ?>
    <script src="/creationPot/script.js"></script>
</html>