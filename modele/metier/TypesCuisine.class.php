<?php
namespace modele\metier;

/**
 * Description of TypesCuisine
 *
 * @author N. Bourgeois
 * @version 07/2021
 */
class TypesCuisine {
    /**  @var int identifiant du type de cuisine */
    private int $idTC;
    /** @var string libelle du type de cuisine */
     private string $libelle;
    
    function __construct(int $idTC, string $libelle) {
        $this->idTC = $idTC;
        $this->libelle = $libelle;
    }

    function getLibelle(): ?string {
        return $this->libelle;
    }

    function setLibelle(?string $libelle): void {
        $this->libelle = $libelle;
    }
    
    function getIdTC(): ?int {
        return $this->idTC;
    }

    function setIdTC(?int $idTC): void {
        $this->idTC = $idTC;
    }
    
}
