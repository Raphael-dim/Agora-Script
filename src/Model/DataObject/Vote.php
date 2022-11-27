<?php

namespace App\Vote\Model\DataObject;

class Vote extends AbstractDataObject
{
    private int $idVote;
    private Votant $votant;
    private Proposition $proposition;


    public function setId(int $id): void{
        $this->idVote = $id;
    }

    public function getProposition(): AbstractDataObject{
        return $this->proposition;
    }

    public function formatTableau(): array
    {
        return array(
            "votantTag" => $this->votant,
            "propositionTag" => $this->proposition
    );
    }
}