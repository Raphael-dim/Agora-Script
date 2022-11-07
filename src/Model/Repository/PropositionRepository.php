<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\Proposition;

//J'attend que la bdd soit fini, c'est provisoir

class PropositionRepository extends AbstractRepository
{
    protected function construire(array $propositionTableau) : Proposition
    {
        return new Proposition(
            $propositionTableau["titre"],
            $propositionTableau["contenu"],
            $propositionTableau["auteur"],
            $propositionTableau["question"]
        );
    }

    protected function getNomTable(): string
    {
        return "Propositions";
    }

    protected function getNomClePrimaire(): string
    {
        return "idQuestion";
    }

    protected function getNomsColonnes(): array
    {
        return array("idQuestion", "idUtilisateur", "titre","contenu");

    }
}
