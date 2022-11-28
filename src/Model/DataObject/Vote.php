<?php

namespace App\Vote\Model\DataObject;

class Vote extends AbstractDataObject
{
    private int $idVote;
    private Votant $votant;
    private Proposition $proposition;

    /**
     * @param Votant $votant
     * @param Proposition $proposition
     */
    public function __construct(Votant $votant, Proposition $proposition)
    {
        $this->votant = $votant;
        $this->proposition = $proposition;
    }


    public function setId(int $id): void{
        $this->idVote = $id;
    }

    public function getProposition(): AbstractDataObject{
        return $this->proposition;
    }

    public function formatTableau(): array
    {
        return array(
            "idvotantTag" => $this->votant->getId(),
            "idpropositionTag" => $this->proposition->getId()
    );
    }
}