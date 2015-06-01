<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Mon compte | KI DOA KOI A KI</title>
		<?php include($_SERVER['DOCUMENT_ROOT']."/include/index.php") ?>
        <script type="text/javascript" src="/libraryJS/complexityPass/pwstrength.js"></script>
    </head>
    <body id="formulaireModificationCompte" ng-app="formulaireModificationCompte">
        <div class ="wrapper">
            <?php include($_SERVER['DOCUMENT_ROOT']."/header/headerModeConnecte.php") ?>
            <div class="inscription">
                <div class="titresInscription">
                    <p id="titreCreation">Paramètres de votre compte utilisateur</p>
                </div>
            </div>
            <div class="input-group col-sm-5 formulaireModificationCompte">
                <form method="post "id="formModification" ng-controller="formModification" ng-submit="formValidation()">
                    <div id="formAncienMdp">
                        <span>Entrez votre mot de passe actuel</span>
                        <input type="password" ng-model="ancienMdp" class="form-control" aria-describedby="basic-addon1">
                    </div>
                    <div id="formMdp">
                        <span>Nouveau mot de passe</span>
                        <input type="password" id="password" ng-model="newMdp" class="form-control" aria-describedby="basic-addon1">
                    </div>
                    <div id="formMail">
                        <span>Mail</span>
                        <input type="text" ng-model="email" id="email" class="form-control" aria-describedby="basic-addon1">
                    </div>
                    <div class="clearfix"></div>
                    <div class="alert alert-success" role="alert" ng-show="messageSucces"><p>{{messageSucces}}</p></div>
                    <div class="alert alert-danger" role="alert" ng-show="messageFail"><p>{{messageFail}}</p></div>
                    <button type="submit" id="submitInscription" class="btn btn-default center-block"><p class="btn btn-lg">Modifier</p></button>
                </form>
            </div>
        </div>
        <?php include($_SERVER['DOCUMENT_ROOT']."/footer/index.php") ?>
    </body>
    <?php include($_SERVER['DOCUMENT_ROOT']."/include/script.php") ?>
    <script src="/modificationCompte/script.js"></script>
</html>