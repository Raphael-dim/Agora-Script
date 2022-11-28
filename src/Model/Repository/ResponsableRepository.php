<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\Responsable;
use App\Vote\Model\DataObject\Utilisateur as Utilisateur;

class ResponsableRepository extends UtilisateurRepository
{

    protected function construire(array $questionTableau): Responsable
    {
        $responsable = new Responsable(
            (new QuestionRepository())->select($questionTableau['idquestion'])
        );
        $responsable->setIdentifiant($questionTableau['idutilisateur']);
        return $responsable;
    }


    protected function getNomTable(): string
    {
        return "Responsables";
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