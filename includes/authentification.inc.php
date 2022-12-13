<?php

use modele\dao\UtilisateurDAO;
use modele\dao\AdminDAO;

/* * ******************************************************************
 * Fonctions de gestion de l'authentification à l'aide des sessions
 * 
 * @version 07/2021 par NB : intégration couche modèle objet
 * 
 * ******************************************************************* */

/**
 * Authentifier un utilisateur et ouvrir sa session
 * @param string $mailU adresse mail de connexion saisie par l'utilisateur
 * @param string $mdpU  mot de passe saisi par l'utilisateur
 */
function login(string $mailU, string $mdpU): void {
    if (!isset($_SESSION)) {
        session_start();
    } else {
        session_destroy();
        session_start();
    }
    // Rechercher les données relatives à cet utilisateur
    $util = UtilisateurDAO::getOneByMail($mailU);
    // Si l'utilisateur est connu (mail trouvé dans la BDD)
    if (!is_null($util)) {
        $mdpBD = $util->getMdpU();
        $idU = $util->getIdU();

        // Si le mot de passe saisi correspond au mot de passe "haché" de la BDD
        if (hash("sha256", $mdpU) == $mdpBD) {
            // le mot de passe est celui de l'utilisateur dans la base de donnees
            $_SESSION["idU"] = $idU;        // la clef est idU désormais
            $_SESSION["mailU"] = $mailU;
            $_SESSION["mdpU"] = $mdpBD;
        }
    }
}

function loginAsAdmin(string $pseudoA, string $mdpA): void {
    if (!isset($_SESSION)) {
        session_start();
    } else {
        session_destroy();
        session_start();
    }

    // Rechercher les données relatives à cet utilisateur
    $admin = AdminDAO::getOneByPseudo($pseudoA);
    // Si l'utilisateur est connu (mail trouvé dans la BDD)
    if (!is_null($admin)) {
        $mdpBD = $admin->getMdpA();
        $idA = $admin->getIdA();

        ajouterMessage("mdpBD = $mdpBD");
        // Si le mot de passe saisi correspond au mot de passe "haché" de la BDD
        if (hash("sha256", $mdpA) == $mdpBD) {
            // le mot de passe est celui de l'utilisateur dans la base de donnees
            $_SESSION["idA"] = $idA;        // la clef est idU désormais
            $_SESSION["pseudoA"] = $pseudoA;
            $_SESSION["mdpA"] = $mdpBD;
        }
    }
}

/**
 * Fermeture de la session de connexion
 * @return void
 */
function logout(): void {
    if (!isset($_SESSION)) {
        session_start();
    }
    unset($_SESSION["idU"]);
    unset($_SESSION["mailU"]);
    unset($_SESSION["mdpU"]);
}

function logoutAsAdmin(): void {
    if (!isset($_SESSION)) {
        session_start();
    }
    unset($_SESSION["idA"]);
    unset($_SESSION["mdpA"]);
}

/**
 * Identité de l'utilisateur connecté
 * @return string mail de l'utilisateur connecté ou "" si aucun
 */
function getMailULoggedOn(): string {
    if (isLoggedOn()) {
        $ret = $_SESSION["mailU"];
    } else {
        $ret = "";
    }
    return $ret;
}

/**
 * Identité de l'admin connecté
 * @return string pseudo de l'admin connecté ou "" si aucun
 */
function getPseudoALoggedOn(): string
{
    if (isLoggedOnAsAdmin()) {
        $ret = $_SESSION["pseudoA"];
    } else {
        $ret = "";
    }
}

function isLoggedOnAsAdmin()
{
    if (!isset($_SESSION)) {
        session_start();
    }
    return isset($_SESSION["idA"]);
}

/**
 * Identité de l'utilisateur connecté
 * @return int id de l'utilisateur connecté ou 0 si aucun
 */
function getIdULoggedOn(): int {
    if (isLoggedOn()) {
        $ret = intval($_SESSION["idU"]);
    } else {
        $ret = 0;
    }
    return $ret;
}


/**
 * Vérifie si l'utilisateur courant ($util) est bien connecté
 * @return bool = true s'il est bien connecté ; =false sinon
 */
function isLoggedOn(): bool {
    if (!isset($_SESSION)) {
        session_start();
    }
    $ret = false;

    if (isset($_SESSION["idU"])) {
        $util = UtilisateurDAO::getOneById($_SESSION["idU"]);
        if ($util->getMailU() == $_SESSION["mailU"] && $util->getMdpU() == $_SESSION["mdpU"]) {
            $ret = true;
        }
    }
    return $ret;
}
