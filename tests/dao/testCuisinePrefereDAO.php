<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>testCuisinePrefere : tests unitaires</title>
    </head>

    <body>

        <?php

        use modele\dao\AimerDAO;
        use modele\dao\Bdd;
        use modele\dao\PrefererDAO;

        require_once '../../includes/autoload.inc.php';

        try {
            Bdd::connecter();
            ?>
            <h2>Test CuisinePrefereDAO</h2>

             <h3>1- estPrefereByIdU</h3>
            <?php $unIdTC = 11 ; $unIdU = 74; ?>
            <p>L'utilisateur d'id <?= $unIdU ?> aime le type de cuisine d'id <?= $unIdTC ?> ? </p>
            <?php
             var_dump(PrefererDAO::estAimeById($unIdU, $unIdTC));
            ?>
            <?php $unIdTC = 1 ; $unIdU = 6; ?>
            <p>L'utilisateur d'id <?= $unIdU ?> aime le type de cuisine d'id <?= $unIdTC ?> ? </p>
            <?php
             var_dump(PrefererDAO::estAimeById($unIdU, $unIdTC));
             
             ?>
            <h3>2- insert</h3>
            <?php
            $unIdTC = 1 ; $unIdU = 6;
            $ok = PrefererDAO::insert($unIdU, $unIdTC)
            ?>
            Réussite de l'ajout : 
            <?php var_dump($ok); ?>
            <p>Après ajout, L'utilisateur d'id <?= $unIdU ?> aime-t-il le type de cuisine d'id <?= $unIdTC ?> ? </p>
            <?php
             var_dump(PrefererDAO::estAimeById($unIdU, $unIdTC));

             ?>
            <h3>3- delete</h3>
            <?php
            $unIdTC = 1 ; $unIdU = 6;
            $ok = PrefererDAO::delete($unIdU, $unIdTC)
            ?>
            Réussite de la suppression : 
            <?php var_dump($ok); ?>
            <p>Après suppression, L'utilisateur d'id <?= $unIdU ?> aime-t-il le type de cuisine d'id <?= $unIdTC ?> ? </p>
            <?php
             var_dump(PrefererDAO::estAimeById($unIdU, $unIdTC));
             ?>
            <h3>4- getLesIdTCByIdU</h3>
            <?php $unIdU = 74; ?>
            <p>Les types de cuisine aimés par l'utilisateur d'id <?= $unIdU ?> : </p>
            <?php
             var_dump(PrefererDAO::getLesIdTCByIdU($unIdU));
            ?>

            <?php
            Bdd::deconnecter();

        } catch (Exception $ex) {
            ?>
            <h4>*** Erreur récupérée : <br/> <?= $ex->getMessage() ?> <br/>***</h4>
            <?php
        }
        ?>

    </body>
</html>
