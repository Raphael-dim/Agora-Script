<?php

namespace App\Vote\Model\Repository;
use App\Vote\Model\DataObject\Question;
use App\Vote\Model\Repository\AbstractRepository;

class QuestionRepository extends AbstractRepository
{

    protected function construire( $questionTableau) : Question
    {
        return new Question(
            $questionTableau["id"],
            $questionTableau["libelle"],
            $questionTableau["idOrganisateur"]
        );
    }

    protected function getNomTable(): string
    {
        return "Question";
    }

    protected function getNomClePrimaire(): string
    {
        return "idQuestion";
    }
}
