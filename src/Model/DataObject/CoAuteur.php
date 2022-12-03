<?php

namespace App\Vote\Model\DataObject;

class CoAuteur extends Utilisateur
{
    private Proposition $proposition;

    public function __construct(Proposition $proposition)
    {
        $this->$proposition = $proposition;
    }

    public function formatTableau(): array
    {
        return array(
            "idproposition" => $this->proposition->getId(),
            "idutilisateurTag" => $this->getIdentifiant(),
        );
    }
}
