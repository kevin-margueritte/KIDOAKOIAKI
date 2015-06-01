<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Inscription | KI DOA KOI A KI</title>
		<?php include($_SERVER['DOCUMENT_ROOT']."/include/index.php") ?>
        <script type="text/javascript" src="/libraryJS/complexityPass/pwstrength.js"></script>
    </head>
    <body id="formulaireInscription" ng-app="formulaireInscription">
        <div class ="wrapper">
            <?php include($_SERVER['DOCUMENT_ROOT']."/header/index.php") ?>
            <div class="inscription">
                <div class="titresInscription">
                    <p id="titreCreation">Création du compte</p>
                    <p id="titreDescription">Créez en quelques cliques votre compte et profitez de nos services</p>
                </div>
            </div>
            <div class="input-group col-sm-5 formulaireInscription">
                <form method="post "id="formInscription" ng-controller="formInscription as form" ng-submit="formValidation()">
                    <div id="formPseudo">
                        <span>Pseudo</span>
                        <input type="text" ng-model="pseudo" name="pseudo" class="form-control" aria-describedby="basic-addon1">
                    </div>
                    <div id="formMdp">
                        <span>Mot de passe</span>
                        <input type="password" id="password" ng-model="mdp" name="mdp" class="form-control" aria-describedby="basic-addon1">
                    </div>
                    <div id="formMail">
                        <span>Mail</span>
                        <input type="text" ng-model="email" name="email" class="form-control" aria-describedby="basic-addon1">
                    </div>
                    <div class="clearfix"></div>
                    <div class="alert alert-success" role="alert" ng-show="messageSucces"><p>{{messageSucces}}</p></div>
                    <div class="alert alert-danger" role="alert" ng-show="messageFail"><p>{{messageFail}}</p></div>
                    <button type="submit" id="submitInscription" class="btn btn-default center-block"><p class="btn btn-lg">Envoyer</p></button>
                </form>
            </div>
        </div>
        <?php include($_SERVER['DOCUMENT_ROOT']."/footer/index.php") ?>
    </body>
    <?php include($_SERVER['DOCUMENT_ROOT']."/include/script.php") ?>
    <script src="script.js"></script>
</html>