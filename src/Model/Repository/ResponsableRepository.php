<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\Utilisateur as Utilisateur;

class ResponsableRepository extends UtilisateurRepository
{

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