<?php

namespace modele\metier;

/**
 * Description of Admin
 * Données relatives à un administrateur
 * @author N. Bourgeois
 * @version 07/2021
 */
class Admin {
    /** @var int identifiant, valeur auto-incrémentée dans la BDD  */
    private int $idA;
    /** @var string mot de passe chiffré */
    private ?string $mdpA;
    /** @var string pseudonyme */
    private ?string $pseudoA;
    
    
    function __construct(int $idA, ?string $mdpA, ?string $pseudoA) {
        $this->idA = $idA;
        $this->mdpA = $mdpA;
        $this->pseudoA = $pseudoA;
    }
    
    public function __toString() {
        return get_class()."{id=".$this->idA." ,mdp=".$this->mdpA." ,pseudo=".$this->pseudoA.", ... }" ;
    }    

    function getIdA(): int {
        return $this->idA;
    }
    function getMdpA(): ?string {
        return $this->mdpA;
    }

    function getPseudoA(): ?string {
        return $this->pseudoA;
    }

    function setIdA(int $idA): void {
        $this->idA = $idA;
    }

    function setMdpA(string $mdpA): void {
        $this->mdpA = $mdpA;
    }

    function setPseudoA(string $pseudoA): void {
        $this->pseudoA = $pseudoA;
    }

}
