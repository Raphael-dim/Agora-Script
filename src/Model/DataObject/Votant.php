<?php

namespace App\Vote\Model\DataObject;

use App\Vote\Lib\ConnexionUtilisateur;
use App\Vote\Model\Repository\AbstractRepository;
use App\Vote\Model\Repository\QuestionRepository;
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
     * On vérifie si pour une question donnée, l'utilisateur passé en paramètre est dans la liste des votants
     */
    public static function estVotant($votants, string $utilisateur): bool
    {
        foreach ($votants as $votant) {
            if ($votant->getIdentifiant() == $utilisateur) {
                return true;
            }
        }
        return false;
    }

    public static function getVotes(string $idUtilisateur): array
    {
        return (new VoteRepository())->selectWhere($idUtilisateur, '*', 'idvotant', 'Votes');
    }

    public static function aVote($proposition, $votes): ?Vote
    {
        foreach ($votes as $vote) {
            if ($vote->getProposition()->getId() == $proposition->getId()) {
                return $vote;
            }
        }
        $votant = new Votant((new QuestionRepository())->select($proposition->getIdQuestion()));
        $votant->setIdentifiant(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        $proposition->setNbVotes(1);
        $vote = new Vote($votant, $proposition, 3);
        (new VoteRepository())->sauvegarder($vote, true);
        return $vote;
    }

    public function formatTableau(): array
    {
        return array(
            "idquestionTag" => $this->question->getId(),
            "idutilisateurTag" => $this->getIdentifiant());
    }
}