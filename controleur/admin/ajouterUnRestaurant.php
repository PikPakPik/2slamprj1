<?php
use modele\dao\Bdd;
use modele\dao\RestoDAO;

/**
 * Contrôleur connexion
 * Traitement du formulaire d'authentification
 *
 * Vue contrôlée : vueAuthentification ou redirection vers le contrôleur monProfil
 * @version 08/2021 gestion erreurs
 *
 */

Bdd::connecter();

// Récupération des données GET, POST, et SESSION
//
// Récupération des données utilisées dans la vue
// creation du menu burger
$menuBurger = array();
$menuBurger[] = Array("url" => "./?action=gererLesRestaurants", "label" => "Gérer les restaurants");
$menuBurger[] = Array("url" => "./?action=gererLesTypesDeCuisines", "label" => "Gérer les types de cuisines");
$menuBurger[] = Array("url" => "./?action=gererLesUtilisateurs", "label" => "Gérer les utilisateurs");

try {
    $lesTypesCuisine = \modele\dao\TypeCuisineDAO::getAll();
} catch (Exception $e) {
    throw new Exception($e->getMessage());
}

// Construction de la vue

if(isLoggedOnAsAdmin()) {
    $titre = "Panel";

    if (isset($_POST['btnSubmit'])) {
        if ($_POST['nomR'] || $_POST['numAdrR'] || $_POST['voieAdrR'] || $_POST['cpR'] || $_POST['villeR'] || $_POST['latitudeR'] || $_POST['longitudeR'] || $_POST['descR'] || $_POST['ouvertureMidiSemaineR'] || $_POST['ouvertureMidiWeekendR'] ||  $_POST['ouvertureSoirSemaineR'] || $_POST['ouvertureSoirWeekendR'] || $_POST['fermetureMidiSemaineR'] || $_POST['fermetureMidiWeekendR'] || $_POST['ouvertureAEmporterWeekendR'] || $_POST['ouvertureAEmporterSemaineR'] != null) {
            $nomR = $_POST['nomR'];
            $numAdrR = $_POST['numAdrR'];
            $voieAdrR = $_POST['voieAdrR'];
            $cpR = $_POST['cpR'];
            $villeR = $_POST['villeR'];
            $latitudeR = floatval($_POST['latitudeR']);
            $longitudeR = floatval($_POST['longitudeR']);
            $ouvertureMidiSemaineR = $_POST['ouvertureMidiSemaineR'];
            $ouvertureMidiWeekendR = $_POST['ouvertureMidiWeekendR'];
            $ouvertureSoirSemaineR = $_POST['ouvertureSoirSemaineR'];
            $ouvertureSoirWeekendR = $_POST['ouvertureSoirWeekendR'];
            $ouvertureAEmporterWeekendR = $_POST['ouvertureAEmporterWeekendR'];
            $ouvertureAEmporterSemaineR = $_POST['ouvertureAEmporterSemaineR'];
            $horairesR = '<table><thead><tr><th>Ouverture</th><th>Semaine</th>	<th>Week-end</th></tr></thead><tbody><tr><td class="label">Midi</td><td>' . $ouvertureMidiSemaineR = $_POST['ouvertureMidiSemaineR'] .  '</td><td>' . $ouvertureMidiWeekendR = $_POST['ouvertureMidiWeekendR'] . '</td></tr><tr><td class="label">Soir</td><td>'. $ouvertureSoirSemaineR = $_POST['ouvertureSoirSemaineR'] .'</td><td>'. $ouvertureSoirWeekendR = $_POST['ouvertureSoirWeekendR'] .'</td></tr><tr><td class="label">À emporter</td><td>'. $ouvertureAEmporterWeekendR = $_POST['ouvertureAEmporterWeekendR'] .'</td><td>'. $ouvertureAEmporterSemaineR = $_POST['ouvertureAEmporterSemaineR'] .'</td></tr></tbody></table>';
            $descR = $_POST['descR'];

            $idR = RestoDAO::getLastId();
            $unRestaurant = new \modele\metier\Resto($idR,$nomR, $numAdrR, $voieAdrR, $cpR, $villeR, $latitudeR, $longitudeR, $descR, $horairesR);
            RestoDAO::insert($unRestaurant);

            $resto = null;
            try {
                $resto = RestoDAO::getAllByNomR($unRestaurant->getNomR());

                $resto = $resto[0];
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }

            $TC = $_POST['TC'];
            for($i = 0; $i < count($TC); $i++) {
                $idTC = $TC[$i];
                $unTypeCuisine = \modele\dao\TypeCuisineDAO::getOneById($idTC);
                RestoDAO::addTC($resto, $unTypeCuisine);
            }
            var_dump($unRestaurant);

        }
    }
    require_once "$racine/vue/entete.html.php";
    require_once "$racine/vue/admin/vueAjoutRestaurant.php";
    require_once "$racine/vue/pied.html.php";
} else {
    header("Location: index.php?action=admin");
}

?>