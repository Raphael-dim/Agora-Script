<?php

namespace App\Vote\Model\DataObject;

use App\Vote\Model\Repository\AbstractRepository;
use App\Vote\Model\Repository\PropositionRepository;

class Responsable extends Utilisateur
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

    public static function estResponsable($question, $utilisateur): bool
    {
        $responsables = $question->getResponsables();
        foreach ($responsables as $responsable) {
            if ($responsable->getIdentifiant() == $utilisateur) {
                return true;
            }
        }
        return false;
    }

    public static function aCreeProposition($question, $utilisateur): bool
    {
        $propositions = (new PropositionRepository())->selectWhere($utilisateur, '*',
            'idresponsable', 'Propositions');
        foreach ($propositions as $proposition) {
            if ($question == $proposition->getQuestion()) {
                return true;
            }
        }
        return false;
    }

    public function formatTableau(): array
    {
        return array(
            "idquestionTag" => $this->question->getId(),
            "idutilisateurTag" => $this->getIdentifiant());
    }
}