<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Accueil | KI DOA KOI A KI</title>
        <?php include($_SERVER['DOCUMENT_ROOT']."/include/index.php") ?>
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    </head>
    <body id="pageAccueil" ng-app="moduleAcceuil" ng-controller="controllerAcceuil">
        <div class ="wrapper">
        	<?php include($_SERVER['DOCUMENT_ROOT']."/header/index.php") ?>
            <?php include($_SERVER['DOCUMENT_ROOT']."/header/headerModeConnecte.php") ?>
    		<div class="slogan">
    			<p>KI DOA KOI A KI ?</p>
                <p id="ssTitre">évènement, fête, anniversaire... Partagez vos dépenses</p>
    		</div>
            <div id="fonctionnement">
                <p id="titrePrincipal">Comment ça marche ?</p>
                <hr>
                <div class="indicationBlock">
                    <i class="fa fa-code"></i>
                    <p class="titreBlock">Créez</p>
                    <p class="ssTitreBlock">Créez et personnalisez votre évènement</p>
                </div>
                <div class="indicationBlock">
                    <i class="fa fa-child"></i>
                    <p class="titreBlock">Ajoutez</p>
                    <p class="ssTitreBlock">Ajoutez vos amis</p>
                </div>
                <div class="indicationBlock">
                    <i class="fa fa-money"></i>
                    <p class="titreBlock">Affectez</p>
                    <p class="ssTitreBlock">Affectez vos dépenses communes</p>
                </div>
                <div class="indicationBlock">
                    <i class="fa fa-mobile"></i>
                    <p class="titreBlock">Consultez</p>
                    <p class="ssTitreBlock">Consultez et partagez vos évènements avec vos amis</p>
                </div>
            </div>
        </div>
        <?php include($_SERVER['DOCUMENT_ROOT']."/footer/index.php") ?>
    </body>
    <?php include($_SERVER['DOCUMENT_ROOT']."/include/script.php") ?>
    <script src="script.js"></script>
</html>