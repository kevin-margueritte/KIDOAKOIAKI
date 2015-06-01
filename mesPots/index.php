<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Mes pots | KI DOA KOI A KI</title>
		<?php include($_SERVER['DOCUMENT_ROOT']."/include/index.php") ?>
    </head>
    <body id="mesPots" ng-app="mesPots">
        <div class ="wrapper">
    	    <?php include($_SERVER['DOCUMENT_ROOT']."/header/headerModeConnecte.php") ?>
            <div class="descrPot">
                <div class="titresPot">
                    <p id="titreCreation">Liste de mes pots</p>
                </div>
            </div>
            <div id="vuePot" ng-controller="vuePot"></div>
            <button type="button" class="btn btn-default center-block" id="creationPot" onclick="location.href='/pot/creation/1'"> 
                <p class="btn btn-lg">Créer un pot</p>
            </button>
        </div>
        <?php include($_SERVER['DOCUMENT_ROOT']."/footer/index.php") ?>
    </body>
    <?php include($_SERVER['DOCUMENT_ROOT']."/include/script.php") ?>
    <script src="/mesPots/script.js"></script>
</html>