<?php

namespace App\Vote\Model\DataObject;

use App\Vote\Model\Repository\AbstractRepository;

class Responsable extends Utilisateur
{
    private Utilisateur $responsable;
    private Question $question;

    public function formatTableau(): array
    {
        return array(
            "idutilisateur" => $this->responsable->getIdentifiant(),
            "idquestion" => $this->question->getId());
    }
}