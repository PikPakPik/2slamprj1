<?php
/**
 * Fournit le nom du contrôleur principal en fonction de l'action choisie
 * @param string $action libellé de l'action, valeur du paramètre GET "action" de l'URL
 * @return string nom du fichier PHP de la couche contrôleur correspondant à l'action
 */
require_once "$racine/includes/gestionErreurs.inc.php";

function controleurPrincipal(string $action): string
{
    $lesActions = [];
    $lesActions["defaut"] = "accueil.php";
    $lesActions["accueil"] = "accueil.php";
    $lesActions["cgu"] = "cgu.php";
    $lesActions["liste"] = "listeRestos.php";
    $lesActions["detail"] = "detailResto.php";
    $lesActions["recherche"] = "rechercheResto.php";
    $lesActions["connexion"] = "connexion.php";
    $lesActions["deconnexion"] = "deconnexion.php";
    $lesActions["profil"] = "monProfil.php";
    $lesActions["updProfil"] = "updProfil.php";
    $lesActions["inscription"] = "inscription.php";
    $lesActions["aimer"] = "aimer.php";
    $lesActions["noter"] = "noter.php";
    $lesActions["commenter"] = "commenter.php";
    $lesActions["supprimerCritique"] = "supprimerCritique.php";
    $lesActions["admin"] = "admin/admin.php";
    $lesActions["gererLesRestaurants"] = "admin/gererLesRestaurants.php";
    $lesActions["modifierRestaurants"] = "admin/modifierRestaurants.php";
    $lesActions["supprimerRestaurants"] = "admin/supprimerRestaurants.php";
    $lesActions["updTypeCuisine"] = "admin/updTypeCuisine.php";
    $lesActions["updProfilAdmin"] = "admin/updProfilAdmin.php";
    $lesActions["ajouterRestaurants"] = "admin/ajouterRestaurants.php";
    $lesActions["gererLesUtilisateurs"] = "admin/gererLesUtilisateurs.php";

    
    if (array_key_exists($action, $lesActions)) {
        return $lesActions[$action];
    } else {
        ajouterMessage("la page demandée n'existe pas");
        return $lesActions["defaut"];
    }
}
