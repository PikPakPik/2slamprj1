<h1>Gerer les restaurants</h1>

<?php
    if (count($lesRestaurants) == 0) {
        ?>
        <p>Il n'y a pas de restaurant</p>
        <?php
    } else {
        ?>
        <table>
            <tr>
                <th>Nom</th>
                <th>Adresse</th>
                <th>Code postal</th>
                <th>Ville</th>
                <th>Type de cuisine</th>
                <th>Photo</th>
                <th>Horaires</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
            <?php
            foreach ($lesRestaurants as $unRestaurant) {
                ?>
                <tr>
                    <td><?= $unRestaurant->getNomR() ?></td>
                    <td><?= $unRestaurant->getVoieAdr() ?></td>
                    <td><?= $unRestaurant->getCpR() ?></td>
                    <td><?= $unRestaurant->getVilleR() ?></td>
                    <td>
                        <?php
                        foreach ($unRestaurant->getLesTypesCuisine() as $unTypeCuisine) {
                            ?>
                            <div class="TC-block">
                                <span class="TC-txt"><b>#</b><i><?= $unTypeCuisine->getLibelle(); ?></i></span>
                            </div>
                            <?php
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        $lesPhotos = $unRestaurant->getLesPhotos();
                        if (count($lesPhotos) > 0) {
                            $unePhoto = $lesPhotos[0];
                            ?>
                            <img src="photos/<?= $unePhoto->getCheminP() ?>" width="100" alt="photo du restaurant" />
                            <?php
                        }
                        ?>
                    </td>
                    <td>
                        <?= $unRestaurant->getHorairesR()?>
                    </td>
                    <td><a href="./?action=modifierUnRestaurant&id=<?= $unRestaurant->getIdR() ?>">Modifier</a></td>
                    <td><a href="./?action=supprimerUnRestaurant&id=<?= $unRestaurant->getIdR() ?>">Supprimer</a></td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }
    ?>
    <br>
    <a href="./?action=ajouterUnRestaurant">Ajouter un restaurant</a>
    <br>
    <a href="./?action=panel">Retour au panel</a>
