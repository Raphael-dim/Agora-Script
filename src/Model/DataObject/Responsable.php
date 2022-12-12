<?php

namespace App\Vote\Model\DataObject;

use App\Vote\Model\Repository\AbstractRepository;
use App\Vote\Model\Repository\CoAuteurRepository;
use App\Vote\Model\Repository\PropositionRepository;

class Responsable extends Utilisateur
{
    private Question $question;

    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    /* On vérifie si l'utilisateur est responsable pour une question*/
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

    /* On vérifie si le responsable a déjà crée une proposition pour une question*/
    public static function aCreeProposition($question, $utilisateur): bool
    {
        $propositions = (new PropositionRepository())->selectWhere($utilisateur, '*',
            'idresponsable', 'Propositions');
        foreach ($propositions as $proposition) {
            if ($question->getId() == $proposition->getIdQuestion()) {
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