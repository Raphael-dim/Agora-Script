<?php

namespace App\Vote\Model\DataObject;

class Vote extends AbstractDataObject
{
    private int $id;
    private Votant $votant;
    private Proposition $proposition;

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

    public function __construct(Votant $votant, Proposition $proposition)
    {
        $this->votant = $votant;
        $this->proposition = $proposition;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getProposition(): AbstractDataObject
    {
        return $this->proposition;
    }

    public function formatTableau(): array
    {
        return array(
            "idvotantTag" => $this->votant->getIdentifiant(),
            "idpropositionTag" => $this->proposition->getId()
        );
    }
}