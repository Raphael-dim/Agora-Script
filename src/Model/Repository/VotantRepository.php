<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\Votant;
use App\Vote\Model\DataObject\Utilisateur;

class VotantRepository extends AbstractRepository
{
    protected function construire(array $questionTableau): Votant
    {
        $votant = new Votant(
            (new QuestionRepository())->select($questionTableau['idquestion']),
        );
        $votant->setIdentifiant($questionTableau['idutilisateur']);
        return $votant;
    }

    protected function getNomTable(): string
    {
        return "Votants";
    }

    protected function getNomClePrimaire(): string
    {
        return "idutilisateur";
    }

    protected function getNomsColonnes(): array
    {
            return array("idquestion", "idutilisateur");

    }
}
