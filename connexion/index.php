<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Connexion | KI DOA KOI A KI</title>
		<?php include($_SERVER['DOCUMENT_ROOT']."/include/index.php") ?>
    </head>
    <body id="formulaireConnexion" ng-app="formulaireConnexion">
    	<div class ="wrapper">
		    <?php include($_SERVER['DOCUMENT_ROOT']."/header/index.php") ?>
		    <div class="input-group col-sm-5 formulaireConnexion">
		        <form method="post "id="formConnexion" ng-controller="formConnexion as form" ng-submit="formValidation()">
		            <div id="formPseudo">
		                <p>Votre pseudo</p>
		                <input type="text" ng-model="pseudo" name="pseudo" class="form-control" aria-describedby="basic-addon1">
		            </div>
		            <div id="formMdp">
		                <p>Votre mot de passe</p>
		                <input type="password" ng-model="mdp" name="mdp" class="form-control" aria-describedby="basic-addon1">
		            </div>
		          	<p id="reinitMdp" ng-click='demandeReinit()'>Cliquez ici pour réinitialiser votre mot de passe</p>
		            <div class="alert alert-danger" role="alert" ng-show="messageFail"><p>{{messageFail}}</p></div>
		            <div class="alert alert-success" role="alert" ng-show="messageSucces"><p>{{messageSucces}}</p></div>
		            <button type="submit" id="submit" class="btn btn-default center-block"><p class="btn btn-lg" role="button">Se connecter</p></button>
		            <button id="buttonCreationCompte"><a class="btn btn-lg" href="/inscription/" role="button">Créer un compte</a></button>
		        </form>
		    </div>
		</div>
	    <?php include($_SERVER['DOCUMENT_ROOT']."/footer/index.php") ?>
    </body>
    <?php include($_SERVER['DOCUMENT_ROOT']."/include/script.php") ?>
    <script src="script.js"></script>
</html>