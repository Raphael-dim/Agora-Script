<?php

namespace App\Vote\Model\DataObject;

use App\Vote\Model\Repository\AbstractRepository;
use App\Vote\Model\Repository\VoteRepository;

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

    /**
     * On vérifie si pour une question donnée, l'utilisateur passé en paramètre est dans la liste desvotants
     * @param Question $question
     * @param Utilisateur $utilisateur
     * @return bool
     */
    public static function estVotant($question, $utilisateur): bool
    {
        $votants = $question->getVotants();
        foreach ($votants as $votant) {
            if ($votant->getIdentifiant() == $utilisateur) {
                return true;
            }
        }
        return false;
    }

    /**
     * Si l'utilisateur a voté pour la proposition, on retourne l'objet vote, notamment la valeur
     * du vote pour l'affichage.
     * @param Proposition $proposition
     * @param Utilisateur $utilisateur
     * @return ?Vote
     */
    public static function aVote($proposition, $utilisateur): ?Vote
    {
        $vote = (new VoteRepository())->selectWhere(array('clef0' => $proposition->getId(),
            'clef1' => $utilisateur), '*',
            array('idproposition', 'idvotant'), 'Votes');
        return $vote[0] ?? null;
    }

    public function formatTableau(): array
    {
        return array(
            "idquestionTag" => $this->question->getId(),
            "idutilisateurTag" => $this->getIdentifiant());
    }
}