<?php

namespace App\Vote\Model\Repository;

class CoAuteurRepository extends AbstractRepository
{
    protected function construire(array $coAuteurTableau): CoAuteur
    {
        $coAuteur = new CoAuteur(
            (new QuestionRepository())->select($coAuteurTableau['idquestion']),
            (new UtilisateurRepository())->select($coAuteurTableau['idutilisateur'])
        );
        return $coAuteurTableau;
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
