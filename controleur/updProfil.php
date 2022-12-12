<?php

use modele\dao\Bdd;
use modele\dao\UtilisateurDAO;
use modele\dao\TypeCuisineDAO;
use modele\dao\AimerDAO;
use modele\dao\PrefererDAO;

/**
 * Contrôleur updProfil
 * Page d'affichage des caractéristiques d'un utilisateur
 * 
 * Vues contrôlées : vueUpdProfil, vueAuthentification.php
 * 
 * @version 07/2021 intégration couche modèle objet
 * @version 08/2021 gestion erreurs
 */
Bdd::connecter();

// creation du menu burger
$menuBurger = array();
$menuBurger[] = Array("url" => "./?action=profil", "label" => "Consulter mon profil");
$menuBurger[] = Array("url" => "./?action=updProfil", "label" => "Modifier mon profil");

// Initialisations 
$titre = "Mon profil";

// Si un utilisateur est connecté
if (isLoggedOn()) {
    // récupérer son identité
    $idU = getIdULoggedOn();
    $util = UtilisateurDAO::getOneById($idU);

    // Mise à jour de l'objet Utilisateur $util en fonction des saisies
    // Nouveau pseudo
    $pseudoU = "";
    if (isset($_POST["pseudoU"])) {
        $pseudoU = $_POST["pseudoU"];
        if ($pseudoU != "") {
            $util->setPseudoU($pseudoU);
            UtilisateurDAO::update($util);
        }
    }

    // Nouveau mot de passe
    $mdpU = "";
    if (isset($_POST["mdpU"]) && isset($_POST["mdpU2"])) {
        if ($_POST["mdpU"] != "") {
            if (($_POST["mdpU"] == $_POST["mdpU2"])) {
                $mdpU = $_POST["mdpU"];
                UtilisateurDAO::updateMdp($idU, $mdpU);
                logout();
            } else {
                ajouterMessage("Mise à jour du profil : erreur de saisie du mot de passe");
            }
        }
    }

    // Restaurants à supprimer de la liste des restaurants aimés
    if (isset($_POST["lstidR"])) {
        $lstidR = $_POST["lstidR"];
        for ($i = 0; $i < count($lstidR); $i++) {
            AimerDAO::delete($idU, $lstidR[$i]);
        }
    }

    // si le boutton deleteAll est cliqué
    if (isset($_POST["deleteAll"])) {
        // supprimer les types de cuisine préférés
        if(PrefererDAO::deleteAllbyId($idU)){
            ajouterMessage("Mise à jour du profil : suppression des types de cuisine préférés");
            header("Location: ./?action=updProfil");
            exit();
        }
        else{
            ajouterMessage("Mise à jour du profil : erreur de suppression des types de cuisine préférés");
        }
    }

    // Types de cuisine à supprimer de la liste des types de cuisine préférés ou à ajouter à la liste des types de cuisine préférés
    if (isset($_POST["lstidTC"])) {
        $typecuisinesnonaimer = TypeCuisineDAO::getAllNonPreferesByIdU($idU);
        $typecuisinesaimer = TypeCuisineDAO::getPreferesByIdU($idU);
        $lstidTC = $_POST["lstidTC"];

        // Si la liste des types de cuisine est vide, on supprime tous les types de cuisine préférés
        if (!isset($_POST["lstidTC"])) {
            echo "liste vide";
            for ($i = 0; $i < count($typecuisinesaimer); $i++) {
                PrefererDAO::delete($idU, $typecuisinesaimer[$i]->getIdTC());
                header("Location: ./?action=updProfile");
            }
        } else {
            // Si la liste des types de cuisine n'est pas vide, on supprime les types de cuisine non préférés
            for ($i = 0; $i < count($typecuisinesaimer); $i++) {
                PrefererDAO::delete($idU, $typecuisinesaimer[$i]->getIdTC());
            }

            // On ajoute les types de cuisine préférés
            for ($i = 0; $i < count($lstidTC); $i++) {
                if (!in_array($lstidTC[$i], $typecuisinesaimer)) {
                    if(!PrefererDAO::estAimeById($idU, $lstidTC[$i])){
                        PrefererDAO::insert($idU, $lstidTC[$i]);
                    }
                }
            }
        }
    }
    

// Si on a changé le mot de passe, il faut se reconnecter
    if (!isLoggedOn()) {
        // Construction de la vue
        require_once "$racine/vue/entete.html.php";
        require_once "$racine/vue/vueAuthentification.php";
        require_once "$racine/vue/pied.html.php";
    } else {
        // Sinon, on revient sur le formulaire
        // Recharger les données
        $util = UtilisateurDAO::getOneById($idU);
        $mesRestosAimes = $util->getLesRestosAimes();
        
        $lesAutresTypesCuisine = TypeCuisineDAO::getAllNonPreferesByIdU($idU);
        $PreferesByIdU = TypeCuisineDAO::getPreferesByIdU($idU);

        // Construction de la vue
        require_once "$racine/vue/entete.html.php";
        require_once "$racine/vue/vueUpdProfil.php";
        require_once "$racine/vue/pied.html.php";
    }
} else {
    // Si aucun utilisateur n'est connecté
    // Construction de la vue vide
    ajouterMessage("Update profil : aucun utilisateur n'est connecté");
    require_once "$racine/vue/entete.html.php";
    require_once "$racine/vue/pied.html.php";
}
?>