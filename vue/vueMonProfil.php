<?php
/**
 * --------------
 * vueMonProfil
 * --------------
 * 
 * @version 07/2021 par NB : intégration couche modèle objet
 * 
 * Variables transmises par le contrôleur detailResto contenant les données à afficher : 
  ---------------------------------------------------------------------------------------- */
/** @var Utilisateur  $util utilisteur à afficher */

/** @var array $mesRestosAimes  */
/** @var int $idU  */
/** @var string $mailU  */
/**
 * Variables supplémentaires :  
  ------------------------- */
/** @var Resto $unResto */

?>

<h1>Mon profil</h1>

Mon adresse électronique : <?= $util->getMailU() ?> <br />
Mon pseudo : <?= $util->getPseudoU() ?> <br />

<hr>

les restaurants que j'aime : <br />
<?php
foreach ($mesRestosAimes as $unResto) {
    ?>
    <a href="./?action=detail&idR=<?= $unResto->getIdR() ?>"><?= $unResto->getNomR() ?></a><br />
    <?php
}
?>

<hr>
<span id="typecuisine">
                <?php
                if(count($mesTypeCuisinePreferes) == 0){ // si l'utilisateur n'a pas de type de cuisine préféré
                    echo "Mes types de cuisine préférés : <i>Aucun type de cuisine préféré</i>";
                }
                else{
                    echo "Mes types de cuisine préférés : ";
                    foreach ($mesTypeCuisinePreferes as $unTypeCuisine) { // sinon afficher les types de cuisine préférés
                        ?>
                        <div class="TC-block">
                            <span class="TC-txt"><b>#</b><i><?= $unTypeCuisine->getLibelle(); ?></i></span>
                        </div>
                        <?php
                    }
                }
                ?>
            </span>
<hr>
<a href="./?action=deconnexion">se deconnecter</a>
<a href="./?action=updProfil">modifier mon profil</a>


