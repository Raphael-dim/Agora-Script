<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\Proposition;

class PropositionRepository extends AbstractRepository
{
    protected function construire(array $propositionTableau) : Proposition
    {
        $proposition = new Proposition(
            $propositionTableau["titre"],
            (new ResponsableRepository())->select($propositionTableau['idresponsable']),
            (new QuestionRepository())->select($propositionTableau['idquestion'])
        );
        $proposition->setId($propositionTableau["idproposition"]);
        return $proposition;
    }

    protected function getNomTable(): string
    {
        return "Propositions";
    }

    protected function getNomClePrimaire(): string
    {
        return "idProposition";
    }

    protected function getNomsColonnes(): array
    {
        return array( "idquestion", "idresponsable", "titre");

    }
}
