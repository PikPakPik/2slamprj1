<h1>Ajout d'un restaurant</h1>
<form id="addResto" action="./?action=ajouterUnRestaurant" method="post">
    <label for="nomR">Nom du restaurant</label>
    <input type="text" name="nomR" id="nomR" required><br>
    <label for="numAdrR">N°</label>
    <input type="number" name="numAdrR" id="numAdrR" required><br>
    <label for="voieAdrR">Voie</label>
    <input type="text" name="voieAdrR" id="voieAdrR" required><br>
    <label for="cpR">Code postal</label>
    <input type="number"  name="cpR" id="cpR" required><br>
    <label for="villeR">Ville</label>
    <input type="text" name="villeR" id="villeR" required><br>
    <label for="latitudeR">Latitude</label>
    <input type="number" step="0.0000000000001" name="latitudeR" id="latitudeR" required><br>
    <label for="longitudeR">Longitude</label>
    <input type="number" step="0.0000000000001" name="longitudeR" id="longitudeR" required><br>
    <label for="descR">Description</label>
    <textarea name="descR" id="descR" cols="50" rows="5" required></textarea><br><br>
    <label for="horairesR">Horaires</label>
    <table>
        <thead>
        <tr>
            <th>Ouverture</th><th>Semaine</th>	<th>Week-end</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="label">Midi</td>
            <td><input type="text" name="ouvertureMidiSemaineR" id="ouvertureMidiSemaineR" required></td>
            <td><input type="text" name="ouvertureMidiWeekendR" id="ouvertureMidiWeekendR" required></td>
        </tr>
        <tr>
            <td class="label">Soir</td>
            <td><input type="text" name="ouvertureSoirSemaineR" id="ouvertureSoirSemaineR" required></td>
            <td><input type="text" name="ouvertureSoirWeekendR" id="ouvertureSoirWeekendR" required></td>
        </tr>
        <tr>
            <td class="label">À emporter</td>
            <td><input type="text" name="ouvertureAEmporterSemaineR" id="ouvertureAEmporterSemaineR" required></td>
            <td><input type="text" name="ouvertureAEmporterWeekendR" id="ouvertureAEmporterWeekendR" required></td>
        </tr>
        </tbody>
    </table>
    <br>
    <label for="TC">Types de cuisine</label>
    <?php
    foreach ($lesTypesCuisine as $unTypeCuisine) {
        ?>
        <div class="TC-block">
            <input type="checkbox" name="TC[]" id="TC-<?= $unTypeCuisine->getIdTC() ?>" value="<?= $unTypeCuisine->getIdTC() ?>">
            <label for="TC-<?= $unTypeCuisine->getIdTC() ?>"><?= $unTypeCuisine->getLibelle() ?></label>
        </div>
        <?php
    }
    ?>
    <br>
    <!-- Ajouter des photos -->
    <label for="photos">Photos</label>
    <input type="file" name="photos[]" id="photos" multiple>
    <br>
    <input type="submit" id="btnSubmit" name="btnSubmit" value="Ajouter">
</form>