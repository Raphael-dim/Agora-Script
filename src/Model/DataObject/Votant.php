<?php
namespace App\Vote\Model\DataObject;

use App\Vote\Model\Repository\AbstractRepository;

class Votant extends Utilisateur
{
    private Question $question;

    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    /**
     * @return Question
     */
    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function formatTableau(): array
    {
        return array(
            "idquestionTag" =>  $this->question->getId(),
            "idutilisateurTag" => $this->getIdentifiant());
    }
}