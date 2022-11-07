<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\Votant;
use App\Vote\Model\DataObject\Utilisateur;

class VotantRepository extends AbstractRepository
{
    protected function construire(array $questionTableau): Votant
    {
        $auteur = new Votant(
            (new QuestionRepository())->select($questionTableau['idquestion']),
            (new UtilisateurRepository())->select($questionTableau['idutilisateur'])
        );
        return $auteur;
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
        return array("idquestion", "titre", "description");

    }
}
