<?php

namespace App\Vote\Model\DataObject;

class Vote extends AbstractDataObject
{
    private int $id;
    private Votant $votant;
    private Proposition $proposition;
    private int $valeur;

    /**
     * @return int
     */
    public function getValeur(): int
    {
        return $this->valeur;
    }

    /**
     * @param int $valeur
     */
    public function setValeur(int $valeur): void
    {
        $this->valeur = $valeur;
    }

    public function getIdvote(): int
    {
        return $this->id;
    }

    public function setIdvote(int $idvote): void
    {
        $this->id = $idvote;
    }

    public function getVotant(): Votant
    {
        return $this->votant;
    }

    public function setVotant(Votant $votant): void
    {
        $this->votant = $votant;
    }

    public function __construct(Votant $votant, Proposition $proposition, int $valeur)
    {
        $this->votant = $votant;
        $this->proposition = $proposition;
        $this->valeur = $valeur;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Proposition
     */
    public function getProposition(): Proposition
    {
        return $this->proposition;
    }

    /**
     * @param Proposition $proposition
     */
    public function setProposition(Proposition $proposition): void
    {
        $this->proposition = $proposition;
    }



    public function formatTableau($update = false): array
    {
        $tab = array(
            "idvotantTag" => $this->votant->getIdentifiant(),
            "idpropositionTag" => $this->proposition->getId(),
            "valeurvoteTag" => $this->valeur
        );
        if ($update) {
            $tab["idvoteTag"] = $this->id;
        }
        return $tab;
    }
}