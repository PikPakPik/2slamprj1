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
        header("Location: ./?action=gererLesTypesDeCuisines");
    } else {
        $unTypeCuisine = \modele\dao\TypeCuisineDAO::getOneById($_GET["id"]);
        if(isset($_POST["oui"])) {
            // Suppression de l'utilisateur
            $id = $_GET["id"];
            try {
                \modele\dao\TypeCuisineDAO::delete($id);
                // Affichage de la vue de confirmation de suppression
                $_SESSION["alert"] = "Le Type de cuisine " . $unTypeCuisine->getLibelle(). " a bien été supprimé";
            } catch (Exception $e) {
                ajouterMessage($e->getMessage());
                header("Location: ./?action=gererLesTypesDeCuisines");
            }
            // Redirection vers le contrôleur gererLesUtilisateurs
            header("Location: ./?action=gererLesTypesDeCuisines");
        } else if(isset($_POST["non"])) {
            // Affichage de la vue de confirmation de suppression
            header("Location: ./?action=gererLesTypesDeCuisines");
        } else {
            include "vue/entete.html.php";
            include "vue/admin/confirmDeleteTypeCuisine.php";
            include "vue/pied.html.php";
        }
    }
} else {
    header("Location: index.php?action=admin");
}

?>