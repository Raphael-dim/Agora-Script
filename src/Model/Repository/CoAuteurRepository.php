<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\CoAuteur;
use App\Vote\Model\DataObject\Responsable;

class CoAuteurRepository extends AbstractRepository
{
    protected function construire(array $coAuteurTableau): CoAuteur
    {
        $truc = new Responsable($coAuteurTableau['idquestion']);
        $truc->setIdentifiant($coAuteurTableau['idutilisateur']);
        return new CoAuteur(
            (new QuestionRepository())->select($coAuteurTableau['idquestion']),
            (new UtilisateurRepository())->select($coAuteurTableau['idutilisateur']),
            (new UtilisateurRepository())->select($coAuteurTableau['idresponsable'])
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
        return array("idutilisateur", "idquestion, idresponsable");
    }
}
