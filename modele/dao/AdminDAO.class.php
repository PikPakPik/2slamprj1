<?php

namespace modele\dao;

use modele\metier\Admin;
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
class AdminDAO {

    /**
     * Retourne un objet Admin d'après son pseudo
     * @param string $psuedo pseudo de l'admin
     * @return Admin l'objet Admin recherché ou null
     * @throws Exception transmission des erreurs PDO éventuelles
     */
    public static function getOneByPseudo(string $pseudo): ?Admin {
        $lAdmin = null;
        try {
            $requete = "SELECT * FROM admin WHERE pseudoA = :pseudoA";
            $stmt = Bdd::getConnexion()->prepare($requete);
            $stmt->bindParam(':pseudoA', $pseudo, PDO::PARAM_STR);
            $ok = $stmt->execute();
            

            // Si au moins un (et un seul) utilisateur (car login est unique), c'est que le mail existe dans la BDD
            if ($stmt->rowCount() > 0) {
                $enreg = $stmt->fetch(PDO::FETCH_ASSOC);
                $idA = $enreg['idA'];
                $pseudo = $enreg['pseudoA'];
                $mdp = $enreg['mdpA'];

                $lAdmin = new Admin($idA, $mdp, $pseudo);
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::getOneByMail : <br/>" . $e->getMessage());
        }
        return $lAdmin;
    }

    /**
     * Retourne un objet Admin d'après son identifiant
     * @param int $idA identifiant de l'admin
     * @return Admin l'objet Utilisateur recherché ou null
     * @throws Exception transmission des erreurs PDO éventuelles
     */
    public static function getOneById(int $idU): ?Admin {
        $lAdmin = null;
        try {
            $requete = "SELECT * FROM admin WHERE idA = :idA";
            $stmt = Bdd::getConnexion()->prepare($requete);
            $stmt->bindParam(':idA', $idA, PDO::PARAM_INT);
            $ok = $stmt->execute();
            // Si au moins un (et un seul) utilisateur (car login est unique), c'est que le mail existe dans la BDD
            if ($stmt->rowCount() > 0) {
                $enreg = $stmt->fetch(PDO::FETCH_ASSOC);
                $idA = $enreg['idA'];
                $pseudo = $enreg['pseudoA'];
                $mdp = $enreg['mdpA'];

                $lAdmin = new Admin($idA, $pseudo, $mdp);
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::getOneById : <br/>" . $e->getMessage());
//            throw new Exception("Erreur dans la méthode " . get_called_class() );
        }
        return $lAdmin;
    }

    /**
     * Ajouter un enregistrement à la table utilisateur d'après un objet Utilisateur
     * N.B. : l'identifiant utilisateur est autoincrémenté
     * Le mot de passe n'est pas enregistré (traitement à part pour maîtriser le hachage)
     * @param Admin $unAdmin l'objet Utilisateur à ajouter
     * @return bool true si l'opération réussit, false sinon
     * @throws Exception transmission des erreurs PDO éventuelles
     */
    public static function insert(Admin $unAdmin): bool {
        $ok = false;
        try {
            $requete = "INSERT INTO admin (pseudoA) VALUES (:pseudoA)";
            $stmt = Bdd::getConnexion()->prepare($requete);
            $stmt->bindValue(':pseudoU',$unAdmin->getPseudoA(), PDO::PARAM_STR);
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


}
