<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\Proposition;

//J'attend que la bdd soit fini, c'est provisoir

class PropositionRepository extends AbstractRepository
{
    protected function construire(array $propositionTableau) : Proposition
    {
        return new Proposition(
            $propositionTableau["id"],
            $propositionTableau["titre"],
            $propositionTableau["contenu"]
        );
    }

    protected function getNomTable(): string
    {
        return "Proposition";
    }

    protected function getNomClePrimaire(): string
    {
        return "id";
    }

    protected function getNomsColonnes(): array
    {
        return array("id", "titre", "contenu");

    }
}
