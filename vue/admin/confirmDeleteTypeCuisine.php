<h1>Confirmation de suppression</h1>
<p>Êtes-vous sûr de vouloir supprimer le Type de Cuisine <?= $unTypeCuisine->getLibelle() ?> ?</p>
<form action="./?action=supprimerUnTypeDeCuisine&id=<?= $unTypeCuisine->getIdTC() ?>" method="post">
    <input type="submit" name="oui" value="Oui">
    <input type="submit" name="non" value="Non">
</form>