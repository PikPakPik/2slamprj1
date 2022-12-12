<?php

namespace modele\dao;

use modele\dao\Bdd;
use modele\metier\TypesCuisine;
use PDO;
use PDOException;
use Exception;

/**
 * Description of AimerDAO
 *
 * @author N. Bourgeois
 * @version 07/2021
 */
class PrefererDAO {

    /**
     * Vérifie si un type de cuisine est "preferé" d'un utilisateur
     * @param int $idTC identifiant du Type de cuisine concerné
     * @param int $idU identifiant de l'utilisateur concerné
     * @return bool =true si "aimé", = false sinon
     * @throws Exception transmission des erreurs PDO éventuelles
     */
    public static function estAimeById(int $idU, int $idTC): bool {
        $retour = false;
        try {
            $requete = "SELECT * FROM utilisateur_typecuisine WHERE idTC=:idTC AND idU=:idU";
            $stmt = Bdd::getConnexion()->prepare($requete);
            $stmt->bindParam(':idU', $idU, PDO::PARAM_INT);
            $stmt->bindParam(':idTC', $idTC, PDO::PARAM_INT);
            $ok = $stmt->execute();
            // attention, $ok = true pour un select ne retournant aucune ligne
            if ($ok && $stmt->rowCount() > 0) {
                $retour = true;
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::estPrefererByIdU : <br/>" . $e->getMessage());
        }
        return $retour;
    }

    /**
     * Ajouter un couple (idU, idTC) à la table aimer
     * @param int $idU identifiant de l'utilisateur qui aime le type de cuisine
     * @param int $idR identifiant du Type de cuisine aimé
     * @return bool true si l'opération réussit, false sinon
     * @throws Exception transmission des erreurs PDO éventuelles
     */
    public static function insert(int $idU, int $idTC): bool {
        $resultat = false;
        try {
            if(!self::estAimeById($idU, $idTC)) {
                $requete = "INSERT INTO utilisateur_typecuisine (idU, idTC) VALUES (:idU, :idTC)";
                $stmt = Bdd::getConnexion()->prepare($requete);
                $stmt->bindParam(':idU', $idU, PDO::PARAM_INT);
                $stmt->bindParam(':idTC', $idTC, PDO::PARAM_INT);
                $resultat = $stmt->execute();
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::insert : <br/>" . $e->getMessage());
        }
        return $resultat;
    }

    /**
     * Suppimer un couple (idU, idR) de la table aimer
     * @param int $idU identifiant de l'utilisateur
     * @param int $idR identifiant du restaurant
     * @return bool true si réussite, false sinon
     * @throws Exception transmission des erreurs PDO éventuelles
     */
    public static function delete(int $idU, int $idTC): bool {
        $resultat = false;
        try {
            $stmt = Bdd::getConnexion()->prepare("DELETE FROM utilisateur_typecuisine WHERE idU=:idU AND idTC=:idTC");
            $stmt->bindParam(':idTC', $idTC, PDO::PARAM_INT);
            $stmt->bindParam(':idU', $idU, PDO::PARAM_INT);
            $resultat = $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::delete : <br/>" . $e->getMessage());
        }
        return $resultat;
    }

    public static function deleteAllbyId(int $idU)
    {
        $resultat = false;
        try {
            $stmt = Bdd::getConnexion()->prepare("DELETE FROM utilisateur_typecuisine WHERE idU=:idU");
            $stmt->bindParam(':idU', $idU, PDO::PARAM_INT);
            $resultat = $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::delete : <br/>" . $e->getMessage());
        }
        return $resultat;
    }

    public static function getLesIdTCByIdU(int $idU): array {
        $lesIdTC = array();
        try {
            $requete = "SELECT idTC FROM utilisateur_typecuisine WHERE idU=:idU";
            $stmt = Bdd::getConnexion()->prepare($requete);
            $stmt->bindParam(':idU', $idU, PDO::PARAM_INT);
            $ok = $stmt->execute();
            if ($ok) {
                while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $TC = TypeCuisineDAO::getOneById($ligne['idTC']);
                    $lesIdTC[] = $TC;
                }
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::getLesIdTCByIdU : <br/>" . $e->getMessage());
        }
        return $lesIdTC;
    }

}
