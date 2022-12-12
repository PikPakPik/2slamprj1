<?php

namespace modele\dao;
use modele\metier\TypesCuisine;

use modele\dao\Bdd;
use PDO;
use PDOException;
use Exception;

/**
 * Description of TypeCuisineDAO
 *
 * @author N. Bourgeois
 * @version 07/2021
 */
class TypeCuisineDAO {

    public static function getAll(): array {
        $lesObjets = array();
        try {
            $requete = "SELECT * FROM typescuisine";
            $stmt = Bdd::getConnexion()->prepare($requete);
            $ok = $stmt->execute();
            if ($ok) {
                while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $objet = new TypesCuisine($ligne['idTC'], $ligne['libelleTC']);
                    $lesObjets[] = $objet;
                }
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::getAll : <br/>" . $e->getMessage());
        }
        return $lesObjets;
    }

    public static function getOneById(int $id): ?TypesCuisine {
        $objet = null;
        try {
            $requete = "SELECT * FROM typescuisine WHERE idTC=:id";
            $stmt = Bdd::getConnexion()->prepare($requete);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $ok = $stmt->execute();
            if ($ok && $stmt->rowCount() > 0) {
                $ligne = $stmt->fetch(PDO::FETCH_ASSOC);
                $objet = new TypesCuisine($ligne['idTC'], $ligne['libelleTC']);
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::getOneById : <br/>" . $e->getMessage());
        }
        return $objet;
    }

    public static function getAllByResto(int $idR): array {
        $lesObjets = array();
        try {
            // Do select with join on json table to get all types cuisine of a restaurant
            $requete = "SELECT * FROM typescuisine t INNER JOIN typecresto tr ON t.idTC = tr.idTC WHERE tr.idR = :idR";
            $stmt = Bdd::getConnexion()->prepare($requete);
            $stmt->bindParam(':idR', $idR, PDO::PARAM_INT);
            $ok = $stmt->execute();
            if ($ok) {
                while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $objet = new TypesCuisine($ligne['idTC'], $ligne['libelleTC']);
                    $lesObjets[] = $objet;
                }
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::getAllByResto : <br/>" . $e->getMessage());
        }
        return $lesObjets;
    }

    public static function getPreferesByIdU(int $idU): array {
        $lesObjets = array();
        try {
            // Do select with join on json table to get all types cuisine of a restaurant
            $requete = "SELECT * FROM typescuisine t INNER JOIN utilisateur_typecuisine ut ON t.idTC = ut.idTC WHERE ut.idU = :idU";
            $stmt = Bdd::getConnexion()->prepare($requete);
            $stmt->bindParam(':idU', $idU, PDO::PARAM_INT);
            $ok = $stmt->execute();
            if ($ok) {
                while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $objet = new TypesCuisine($ligne['idTC'], $ligne['libelleTC']);
                    $lesObjets[] = $objet;
                }
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::getPreferesByIdU : <br/>" . $e->getMessage());
        }
        return $lesObjets;
    }

    public static function getAllNonPreferesByIdU(int $idU): array {
        $lesObjets = array();
        try {
            // Do select with join on json table to get all types cuisine of a restaurant
            $requete = "SELECT * FROM typescuisine t WHERE t.idTC NOT IN (SELECT ut.idTC FROM utilisateur_typecuisine ut WHERE ut.idU = :idU)";
            $stmt = Bdd::getConnexion()->prepare($requete);
            $stmt->bindParam(':idU', $idU, PDO::PARAM_INT);
            $ok = $stmt->execute();
            if ($ok) {
                while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $objet = new TypesCuisine($ligne['idTC'], $ligne['libelleTC']);
                    $lesObjets[] = $objet;
                }
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::getAllNonPreferesByIdU : <br/>" . $e->getMessage());
        }
        return $lesObjets;
    }

}
