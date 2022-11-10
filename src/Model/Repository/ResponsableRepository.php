<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\Responsable;
use App\Vote\Model\DataObject\Utilisateur as Utilisateur;

class ResponsableRepository extends UtilisateurRepository
{

    protected function construire(array $questionTableau): Responsable
    {
        $auteur = new Responsable(
            (new QuestionRepository())->select($questionTableau['idquestion']),
            (new UtilisateurRepository())->select($questionTableau['idutilisateur'])
        );
        return $auteur;
    }


    protected function getNomTable(): string
    {
        return "responsables";
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