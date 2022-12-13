<h1>Gérer les types de cuisines</h1>

<?php

if (count($lesTypesDeCuisines) == 0) {
    ?>
    <p>Il n'y a pas de type de cuisine</p>
    <?php
} else {
    ?>
    <table>
        <tr>
            <th>Libellé</th>
            <th>Supprimer</th>
        </tr>
        <?php
        foreach ($lesTypesDeCuisines as $unTypeDeCuisine) {
            ?>
            <tr>
                <td><?= $unTypeDeCuisine->getLibelle() ?></td>
                <td><a href="./?action=supprimerUnTypeDeCuisine&id=<?= $unTypeDeCuisine->getIdTC() ?>">Supprimer</a></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
}