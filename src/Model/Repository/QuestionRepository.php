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
            $questionTableau["nbSections"],
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
        return "id";
    }

    protected function getNomsColonnes(): array
    {
        return array("id", "titre", "nbSections");

    }
}
