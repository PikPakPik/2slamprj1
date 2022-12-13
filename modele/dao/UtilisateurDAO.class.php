<?php

namespace modele\dao;

use modele\metier\Utilisateur;
use PDO;
use PDOException;
use Exception;

/**
 * Description of UtilisateurDAO
 * N.B. : chargement de type "lazy" pour casser le cycle 
 * "un utilisateur aime des restaurants, un restaurant collectionne des critiques, une critique est émise par un utilisateur, "
 * Donc, pour chaque restaurant, on ne chargera pas les critiques, ni les photos 
 * @author N. Bourgeois
 * @version 07/2021
 */
class UtilisateurDAO {

    /**
     * Retourne un objet Utilisateur d'après son email
     * @param string $mailU mail de l'utilisateur recherché
     * @return Utilisateur l'objet Utilisateur recherché ou null
     * @throws Exception transmission des erreurs PDO éventuelles
     */
    public static function getOneByMail(string $mailU): ?Utilisateur {
        $leUser = null;
        try {
            $requete = "SELECT * FROM utilisateur WHERE mailU = :mailU";
            $stmt = Bdd::getConnexion()->prepare($requete);
            $stmt->bindParam(':mailU', $mailU, PDO::PARAM_STR);
            $ok = $stmt->execute();
            

            // Si au moins un (et un seul) utilisateur (car login est unique), c'est que le mail existe dans la BDD
            if ($stmt->rowCount() > 0) {
                $enreg = $stmt->fetch(PDO::FETCH_ASSOC);
                $idU = $enreg['idU'];
                $lesRestosAimes = RestoDAO::getAimesByIdU($idU);
                $lesTypesCuisinePreferes = TypeCuisineDAO::getPreferesByIdU($idU);
               
                $leUser = new Utilisateur($idU, $enreg['mailU'], $enreg['mdpU'], $enreg['pseudoU']);
                
                $leUser->setLesRestosAimes($lesRestosAimes);
                $leUser->setLesTypesCuisinePreferes($lesTypesCuisinePreferes);
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::getOneByMail : <br/>" . $e->getMessage());
        }
        return $leUser;
    }

    /**
     * Retourne un objet Utilisateur d'après son identifiant
     * @param int $idU identifiant de l'utilisateur recherché
     * @return Utilisateur l'objet Utilisateur recherché ou null
     * @throws Exception transmission des erreurs PDO éventuelles
     */
    public static function getOneById(int $idU): ?Utilisateur {
        $leUser = null;
        try {
            $requete = "SELECT * FROM utilisateur WHERE idU = :idU";
            $stmt = Bdd::connecter()->prepare($requete);
            $stmt->bindParam(':idU', $idU, PDO::PARAM_INT);
            $ok = $stmt->execute();
            // attention, $ok = true pour un select ne retournant aucune ligne
            if ($ok && $stmt->rowCount() > 0) {
                $enreg = $stmt->fetch(PDO::FETCH_ASSOC);
                $lesRestosAimes = RestoDAO::getAimesByIdU($idU);
                $lesTypesCuisinePreferes = TypeCuisineDAO::getPreferesByIdU($idU);
                
                $leUser = new Utilisateur($idU, $enreg['mailU'], $enreg['mdpU'], $enreg['pseudoU']);
               
                $leUser->setLesRestosAimes($lesRestosAimes);
                $leUser->setLesTypesCuisinePreferes($lesTypesCuisinePreferes);
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::getOneById : <br/>" . $e->getMessage());
//            throw new Exception("Erreur dans la méthode " . get_called_class() );
        }
        return $leUser;
    }

    /**
     * Retourne la liste de tous les utilisateurs
     * @return array tableau d'objets Utilisateur
     * @throws Exception transmission des erreurs PDO éventuelles
     */
    public static function getAll(): array {
        $lesUsers = array();
        try {
            $requete = "SELECT * FROM utilisateur";
            $stmt = Bdd::getConnexion()->prepare($requete);
            $ok = $stmt->execute();
            // attention, $ok = true pour un select ne retournant aucune ligne
            if ($ok) {
                while ($enreg = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $idU = $enreg['idU'];
                    $lesRestosAimes = RestoDAO::getAimesByIdU($idU);
                    $lesTypesCuisinePreferes = TypeCuisineDAO::getPreferesByIdU($idU);

                    $leUser = new Utilisateur($idU, $enreg['mailU'], $enreg['mdpU'], $enreg['pseudoU']);

                    $leUser->setLesRestosAimes($lesRestosAimes);
                    $leUser->setLesTypesCuisinePreferes($lesTypesCuisinePreferes);

                    $lesUsers[] = $leUser;
                }
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::getAll : <br/>" . $e->getMessage());
        }
        return $lesUsers;
    }
    

    /**
     * Ajouter un enregistrement à la table utilisateur d'après un objet Utilisateur
     * N.B. : l'identifiant utilisateur est autoincrémenté
     * Le mot de passe n'est pas enregistré (traitement à part pour maîtriser le hachage)
     * @param Utilisateur $unUser
     * @return bool true si l'opération réussit, false sinon
     * @throws Exception transmission des erreurs PDO éventuelles
     */
    public static function insert(Utilisateur $unUser): bool {
        $ok = false;
        try {
            $requete = "INSERT INTO utilisateur (mailU, pseudoU) VALUES (:mailU,:pseudoU)";
            $stmt = Bdd::getConnexion()->prepare($requete);
//            $mdpUCrypt = crypt($unUser->getMdpU(), "sel");
            $stmt->bindValue(':mailU', $unUser->getMailU(), PDO::PARAM_STR);
//            $stmt->bindValue(':mdpU', $mdpUCrypt, PDO::PARAM_STR);
            $stmt->bindValue(':pseudoU', $unUser->getPseudoU(), PDO::PARAM_STR);
            $ok = $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::insert : <br/>" . $e->getMessage());
        }
        return $ok;
    }

    /**
     * Mettre à jour un enregistrement à la table utilisateur d'après un objet Utilisateur
     * Le mot de passe n'est pas enregistré (traitement à part pour maîtriser le hachage)
     * @param Utilisateur $unUser utilisateur contenant les données à mettre à jour
     * @return bool true si l'opération réussit, false sinon
     * @throws Exception transmission des erreurs PDO éventuelles
     */
    public static function update(Utilisateur $unUser): bool {
        $ok = false;
        try {
        $requete = "UPDATE utilisateur SET mailU = :mailU, pseudoU = :pseudoU WHERE idU = :idU";
        $stmt = Bdd::getConnexion()->prepare($requete);
//        $mdpUCrypt = crypt($unUser->getMdpU(), "sel");
        $stmt->bindValue(':mailU', $unUser->getMailU(), PDO::PARAM_STR);
//        $stmt->bindValue(':mdpU', $mdpUCrypt, PDO::PARAM_STR);
        $stmt->bindValue(':pseudoU', $unUser->getPseudoU(), PDO::PARAM_STR);
        $stmt->bindValue(':idU', $unUser->getIdU(), PDO::PARAM_INT);
        $ok = $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::update : <br/>" . $e->getMessage());
        }
        return $ok;
    }
    
    /**
     * Mettre à jour le mot de passe d'un enregistrement à la table utilisateur
     * @param int $idU identifiant de l'utilisateur à mettre à jour
     * @param string $mdpClair nouveau mot de passe non chiffré
     * @return bool true si l'opération réussit, false sinon
     * @throws Exception transmission des erreurs PDO éventuelles
     */
    public static function updateMdp(int $idU, string $mdpClair): bool {
        $ok = false;
        try {
        $requete = "UPDATE utilisateur SET mdpU = :mdpU WHERE idU = :idU";
        $stmt = Bdd::getConnexion()->prepare($requete);
        $mdpUCrypt = hash('sha256', $mdpClair);
        $stmt->bindValue(':idU', $idU, PDO::PARAM_INT);
        $stmt->bindValue(':mdpU', $mdpUCrypt, PDO::PARAM_STR);
        
        $ok = $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::updateMdp : <br/>" . $e->getMessage());
        }
        return $ok;
    }

    public static function checkEmail(string $emailToCheck): bool {
        try {
            $requete = "SELECT mailU FROM utilisateur WHERE mailU = :emailToCheck";
            $stmt = Bdd::connecter()->prepare($requete);
            $stmt->bindParam(':emailToCheck', $emailToCheck, PDO::PARAM_STR);
            $ok = $stmt->execute();
            // attention, $ok = true pour un select ne retournant aucune ligne
            if ($ok && $stmt->rowCount() > 0) {
                return false;
            }
            else {
                return true;
            }
        } 
        catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::checkEmail : <br/>" . $e->getMessage());
        }
    }

    /**
     * Supprimer un enregistrement à la table utilisateur d'après son identifiant
     * @param int $idU identifiant de l'utilisateur à supprimer
     * @return bool true si l'opération réussit, false sinon
     * @throws Exception transmission des erreurs PDO éventuelles
     */

    public static function delete(int $idU): bool
    {
        $ok = false;
        try {
            $updateAllCritique = "UPDATE critiquer SET idU = 0 WHERE idU = :idU";
            $updateAllAime = "UPDATE aimer SET idU = 0 WHERE idU = :idU";
            $deleteTCU = "DELETE FROM utilisateur_typecuisine WHERE idU = :idU";

            $stmt = Bdd::getConnexion()->prepare($updateAllCritique);
            $stmt->bindValue(':idU', $idU, PDO::PARAM_INT);
            $ok = $stmt->execute();

            $stmt = Bdd::getConnexion()->prepare($updateAllAime);
            $stmt->bindValue(':idU', $idU, PDO::PARAM_INT);
            $ok = $stmt->execute();

            $stmt = Bdd::getConnexion()->prepare($deleteTCU);
            $stmt->bindValue(':idU', $idU, PDO::PARAM_INT);
            $ok = $stmt->execute();

            $requete = "DELETE FROM utilisateur WHERE idU = :idU";
            $stmt = Bdd::getConnexion()->prepare($requete);
            $stmt->bindValue(':idU', $idU, PDO::PARAM_INT);
            $ok = $stmt->execute();

        } catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::delete : <br/>" . $e->getMessage());
        }
        return $ok;
    }
}
