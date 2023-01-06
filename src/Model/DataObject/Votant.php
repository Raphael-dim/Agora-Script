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

    public static function aVote($proposition, $votes, $modeScrutin = 'unique'): ?Vote
    {
        /*
         * Cette méthode vérifie si l'utilisateur connecté a déjà voté pour une proposition
         * Pour économiser du temps, on lui fournit un tableau de tous les votes de l'utilisateur qui
         * est utilisé ailleurs dans le code dans le fichier listMajoritaire notamment.
         * Si c'est le cas, elle renvoie l'objet vote.
         * Sinon elle renvoie null si le mode de scrutin est différent de majoritaire.
         * Si l'utilisateur n'a pas encore voté et que le mode de scrutin est majoritaire.
         * On enregistre dans la base de donnée le vote par défaut qui est 'Passable', soit 3.
         */
        foreach ($votes as $vote) {
            if ($vote->getProposition()->getId() == $proposition->getId()) {
                return $vote;
            }
        }
        if ($modeScrutin == 'majoritaire') {
            $votant = new Votant((new QuestionRepository())->select($proposition->getIdQuestion()));
            $votant->setIdentifiant(ConnexionUtilisateur::getLoginUtilisateurConnecte());
            $vote = new Vote($votant, $proposition, 3);
            (new VoteRepository())->sauvegarder($vote, true);
            return $vote;
        }
        return null;
    }

    public function formatTableau(): array
    {
        return array(
            "idquestionTag" => $this->question->getId(),
            "idutilisateurTag" => $this->getIdentifiant());
    }
}