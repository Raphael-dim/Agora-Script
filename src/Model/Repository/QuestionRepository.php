<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\Question;

class QuestionRepository extends AbstractRepository
{
    protected function construire(array $questionTableau) : Question
    {
        return new Question(
            $questionTableau["titre"],
            $questionTableau["description"],
            $questionTableau["calendrier"],
            $questionTableau["auteur"]
        );
    }

    protected function getNomTable(): string
    {
        return "Questions";
    }

    protected function getNomClePrimaire(): string
    {
        return "idQuestion";
    }

    protected function getNomsColonnes(): array
    {
        return array("titre", "description", "idCalendrier", "idAuteur");

    }
}
