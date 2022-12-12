<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\Proposition;

class PropositionRepository extends AbstractRepository
{
    protected function construire(array $propositionTableau) : Proposition
    {

        /*
        On ne construit pas l'objet proposition avec un objet Responsable pour éviter de
        faire un aller-retour inutile à la base de donnée.
        */
        $proposition = new Proposition(
            $propositionTableau["titre"],
            $propositionTableau['idresponsable'],
            $propositionTableau['idquestion'],
            $propositionTableau["nbvotes"]
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
        return "idproposition";
    }

    protected function getNomsColonnes(): array
    {
        return array( "idquestion", "idresponsable", "titre", "nbvotes");
    }
}
