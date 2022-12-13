<h1>Confirmation de suppression</h1>
<p>Êtes-vous sûr de vouloir supprimer l'utilisateur <?= $unUtilisateur->getPseudoU() ?> ?</p>
<form action="./?action=supprimerUnUtilisateur&id=<?= $unUtilisateur->getIdU() ?>" method="post">
    <input type="submit" name="oui" value="Oui">
    <input type="submit" name="non" value="Non">
</form>