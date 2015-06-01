<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Consultation du pot | KI DOA KOI A KI</title>
		<?php include($_SERVER['DOCUMENT_ROOT']."/include/index.php") ?>
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link href="/libraryJS/wysiwyg/summernote.css" rel="stylesheet">
    </head>
    <body id="gestionPot" ng-app="gestionPot">
        <div class ="wrapper">
    	    <?php include($_SERVER['DOCUMENT_ROOT']."/header/index.php") ?>
            <?php include($_SERVER['DOCUMENT_ROOT']."/header/headerModeConnecte.php") ?>
    	    <div id="vuePot" ng-controller="vuePot">
        	    <div id="titrePot"><p ng-bind-html="titrePot"></p></div>
        	    <div id="descriptionPot" ng-bind-html="descriptionPot"></div>
                <div>
                    <div id="modifDescrPot" ng-show="afficherDescPot">
                        <p class="titreModification">Titre pot</p>
                        <input type="text" ng-model="titre" name="titrePot" id="titrePot" class="form-control" aria-describedby="basic-addon1">
                        <p class="titreModification">Description pot</p>
                        <div id="summernote"></div>
                        <button type="button" class="btn btn-default center-block" id="enrModifInfo" ng-click='modifierInfo()'> 
                            <p class="btn btn-lg">Enregistrer</p>
                        </button>
                    </div>
                    <div class="alert alert-success" role="alert" ng-show="messageSuccesModifInfo"><p>{{messageSuccesModifInfo}}</p></div>
                    <div class="alert alert-danger" role="alert" ng-show="messageFailModifInfo"><p>{{messageFailModifInfo}}</p></div>
                    <div id="crayon">
                        <p class="glyphicon glyphicon-pencil" aria-hidden="true" ng-click='modifierInfoPot()'></p>
                    </div>
                    <div id="chevronDown">
                        <p class="glyphicon glyphicon-chevron-down" aria-hidden="true" ng-click='activerChevronUp()'></p>
                    </div>
                    <div id="chevronUp">
                        <p class="glyphicon glyphicon-chevron-up" aria-hidden="true" ng-click='activerChevronDown()'></p>
                    </div>
                </div>
                <div id="montantRembourse">
                    <p ng-show="montantTotalRembourse">Montant total remboursé {{montantTotalRembourse}}</p>
                </div>
                <div id="modificationAmi">
                    <div id="listeAmis">
                        <p>Amis</p>
                    </div>
                    <div ng-show="ajoutAmi" class="inputModification">
                        <p class="titreModification">Nom</p>
                        <input type="text" ng-model="nomAmi" class="form-control" aria-describedby="basic-addon1">
                        <button type="button" class="btn btn-default center-block" id="validerModificationAmi" ng-click='ajouterAmi()'> 
                            <p class="btn btn-lg">Ajouter</p>
                        </button>
                    </div>
                    <div id="addAmi" ng-show="modeConnecte">
                        <p class="glyphicon glyphicon-plus" aria-hidden="true" ng-click='afficherAmi()'></p>
                    </div>
                    <div class="alert alert-success" role="alert" ng-show="messageSuccesAjoutAmi"><p>{{messageSuccesAjoutAmi}}</p></div>
                    <div class="alert alert-danger" role="alert" ng-show="messageFailAjoutAmi"><p>{{messageFailAjoutAmi}}</p></div>
                </div>
                <div id="modificationObjet">
                    <div id="listeObjet">
                        <p>Objets</p>
                    </div>
                    <div id="ajoutObjet" ng-show="ajoutObjet">
                        <div id="ajoutNomObjet">
                            <p class="titreModification">Nom</p>
                            <input type="text" ng-model="nomObjet" name="nom" class="form-control" aria-describedby="basic-addon1">
                        </div>
                        <div id="ajoutPrixObjet">
                            <p class="titreModification">Prix</p>
                            <input type="number" min="0" step="0.01" max="2500" value="0" ng-model="prixObjet" name="prix" class="form-control" aria-describedby="basic-addon1">
                        </div>
                        <button type="button" class="btn btn-default center-block" id="validerAjoutObjet" ng-click='ajouterObjet()'> 
                            <p class="btn btn-lg">Ajouter</p>
                        </button>
                    </div>
                    <div id="addObjet" ng-show="modeConnecte">
                        <p class="glyphicon glyphicon-plus" aria-hidden="true" ng-click='afficherObjet()'></p>
                    </div>
                    <div class="alert alert-success" role="alert" ng-show="messageSuccesModifObjet"><p>{{messageSuccesModifObjet}}</p></div>
                    <div class="alert alert-danger" role="alert" ng-show="messageFailModifObjet"><p>{{messageFailModifObjet}}</p></div>
                </div>
            </div>
        </div>
        <?php include($_SERVER['DOCUMENT_ROOT']."/footer/index.php") ?>
    </body>
    <?php include($_SERVER['DOCUMENT_ROOT']."/include/script.php") ?>
    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.2.1/angular-sanitize.js"></script>
    <script src="/libraryJS/wysiwyg/summernote.min.js"></script>
    <script src="/pot/script.js"></script>
</html>