<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\Responsable;
use App\Vote\Model\DataObject\Utilisateur as Utilisateur;

class ResponsableRepository extends UtilisateurRepository
{

    protected function construire(array $questionTableau): Responsable
    {
        $responsable = new Responsable(
            (new QuestionRepository())->select($questionTableau['idQuestion'])
        );
        $responsable->setIdentifiant($questionTableau['idUtilisateur']);
        return $responsable;
    }


    protected function getNomTable(): string
    {
        return "Responsables";
    }

    protected function getNomClePrimaire(): string
    {
        return "idUtilisateur";
    }

    protected function getNomsColonnes(): array
    {
        return array("idquestion", "idutilisateur");
    }
}