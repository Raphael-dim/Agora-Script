<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\CoAuteur;

class CoAuteurRepository extends AbstractRepository
{
    protected function construire(array $coAuteurTableau): CoAuteur
    {
        return new CoAuteur(
            (new QuestionRepository())->select($coAuteurTableau['idquestion']),
            (new UtilisateurRepository())->select($coAuteurTableau['idutilisateur'])
        );
    }

    protected function getNomTable(): string
    {
        return "CoAuteur";
    }

    protected function getNomClePrimaire(): string
    {
        return "idutilisateur";
    }

    protected function getNomsColonnes(): array
    {
        return array("idutilisateur", "idquestion");

    }
}
