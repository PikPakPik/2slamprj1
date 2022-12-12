<?php
use \modele\metier\TypesCuisine;

require_once '../../includes/autoload.inc.php';

$unTypeCuisine = new TypesCuisine(1, "Française");
?>
<h2>Test unitaire de la classe TypeCuisine</h2>
<?php
var_dump($unTypeCuisine);

