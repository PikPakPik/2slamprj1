<?php
use modele\dao\Bdd;
use modele\dao\RestoDAO;
use modele\dao\UtilisateurDAO;
use modele\dao\TypeCuisineDAO;
/**
 * Contrôleur rechercheResto
 * Gère la recherche de restaurants par filtrage
 * 
 * Vues contrôlées : vueRechercheResto, vueListeRestos
 * Données GET : critere (nom, adresse, multi) = critere de filtrage
 * 
 * @version 07/2021 intégration couche modèle objet
 */

Bdd::connecter();
    
// creation du menu burger
$menuBurger = array();
$menuBurger[] = Array("url"=>"./?action=recherche&critere=nom","label"=>"Recherche par nom");
$menuBurger[] = Array("url"=>"./?action=recherche&critere=adresse","label"=>"Recherche par adresse");
$menuBurger[] = Array("url"=>"./?action=recherche&critere=multi","label"=>"Recherche multicritère");
$menuBurger[] = Array("url"=>"./?action=recherche&critere=type_de_cuisine","label"=>"Recherche par type de cuisine");

// recuperation des donnees GET, POST, et SESSION
// critere de recherche par defaut : le nom
$critere = "nom";
if (isset($_GET["critere"])) {
    $critere = $_GET["critere"];
}

$nomR="";
if (isset($_POST["nomR"])){
    $nomR = $_POST["nomR"];
}

$typeCuisineR="";
if (isset($_POST["typeCuisineR"])){
    $typeCuisineR = $_POST["typeCuisineR"];
}


$voieAdrR="";
if (isset($_POST["voieAdrR"])){
    $voieAdrR = $_POST["voieAdrR"];
}

$cpR="";
if (isset($_POST["cpR"])){
    if(preg_match("#^[0-9]{5}$#",$_POST['cpR'])) {
        $cpR = $_POST["cpR"];
    }else{
        ajouterMessage("Le code postal saisie n'est pas valide");
        require_once "$racine/vue/entete.html.php";
        require_once "$racine/vue/vueRechercheResto.php"; 
        require_once "$racine/vue/pied.html.php";
        exit();
    }
}

$villeR="";
if (isset($_POST["villeR"])){
    $villeR = $_POST["villeR"];
}

$tabIdTC = array();
if(isset($_POST["tabIdTC"])){
    $tabIdTC = $_POST["tabIdTC"];
}


// appel des fonctions permettant de recuperer les donnees utiles a l'affichage 
switch($critere){
    case 'nom':
        // recherche par nom
        $listeRestos = RestoDAO::getAllByNomR($nomR);
        break;
    case 'adresse':
        // recherche par adresse
        $listeRestos = RestoDAO::getAllByAdresse($voieAdrR, $cpR, $villeR);
        break;
    
    case 'multi':
        // recherche multi-critere
        $listeRestos = RestoDAO::getAllMultiCriteres($nomR,$voieAdrR, $cpR, $villeR,$tabIdTC);
        break;
    
    case 'type_de_cuisine':
        // recherche par cuisine type
        $listeRestos = RestoDAO::getAllByCuisineR($typeCuisineR);
        break;
    
}


// Construction de la vue
$titre = "Recherche d'un restaurant";
require_once "$racine/vue/entete.html.php";
if (empty($_POST)) {
    require_once "$racine/vue/vueRechercheResto.php";    
}else{    
    // affichage des resultats de la recherche
    include "$racine/vue/vueListeRestos.php";
}
require_once "$racine/vue/pied.html.php";


?>
