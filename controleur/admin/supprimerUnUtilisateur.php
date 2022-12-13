<?php
use modele\dao\Bdd;
use modele\dao\UtilisateurDAO;

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




// Construction de la vue
if(isLoggedOnAsAdmin()) {
    $titre = "Panel";
    if(!isset($_GET["id"])) {
        header("Location: ./?action=gererLesUtilisateurs");
    } else {
        $unUtilisateur = UtilisateurDAO::getOneById($_GET["id"]);
        if(isset($_POST["oui"])) {
            // Suppression de l'utilisateur
            $id = $_GET["id"];
            UtilisateurDAO::delete($id);
            // Affichage de la vue de confirmation de suppression
            $_SESSION["alert"] = "L'utilisateur " . $unUtilisateur->getPseudoU() . " a bien été supprimé";
            // Redirection vers le contrôleur gererLesUtilisateurs
            header("Location: ./?action=gererLesUtilisateurs");
        } else if(isset($_POST["non"])) {
            // Affichage de la vue de confirmation de suppression
            header("Location: ./?action=gererLesUtilisateurs");
        } else {
            include "vue/entete.html.php";
            include "vue/admin/confirmDeleteUser.php";
            include "vue/pied.html.php";
        }
    }
} else {
    header("Location: index.php?action=admin");
}

?>