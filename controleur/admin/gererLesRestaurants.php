<?php
use modele\dao\Bdd;

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

$lesRestaurants = \modele\dao\RestoDAO::getAll();

// Construction de la vue

if(isLoggedOnAsAdmin()) {
    $titre = "Panel";
    require_once "$racine/vue/entete.html.php";
    require_once "$racine/vue/admin/vueGererLesRestaurants.php";
    require_once "$racine/vue/pied.html.php";
} else {
    header("Location: index.php?action=admin");
}

?>