<h1>Gerer les utilisateurs</h1>

<?php
if(isset($alert)) {
    echo "<p>$alert</p>";
}
    if (count($lesUtilisateurs) == 0) {
        ?>
        <p>Il n'y a pas d'utilisateur</p>
        <?php
    } else {
        ?>
        <table>
            <tr>
                <th>Mail</th>
                <th>Pseudo</th>
                <th>Restos aimé</th>
                <th>Types de cuisine aimé</th>
                <th>Supprimer</th>
            </tr>
            <?php
            foreach ($lesUtilisateurs as $unUtilisateur) {
                if($unUtilisateur->getMailU() == "deleteaccount@jolsio.net") {
                    continue;
                }
                ?>
                <tr>
                    <td><?= $unUtilisateur->getMailU() ?></td>
                    <td><?= $unUtilisateur->getPseudoU() ?></td>
                    <td><?= count($unUtilisateur->getLesRestosAimes()) ?></td>
                    <td><?= count($unUtilisateur->getLesTypesCuisinePreferes()) ?></td>
                    <td><a href="./?action=supprimerUnUtilisateur&id=<?= $unUtilisateur->getIdU() ?>">Supprimer</a></td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }
    ?>