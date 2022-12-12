<?php

namespace App\Vote\Controller;


use App\Vote\Lib\ConnexionUtilisateur;
use App\Vote\Lib\MessageFlash;
use App\Vote\Model\DataObject\Votant;
use App\Vote\Model\DataObject\Vote;
use App\Vote\Model\Repository\PropositionRepository;
use App\Vote\Model\Repository\QuestionRepository;
use App\Vote\Model\Repository\VoteRepository;

class ControllerVote
{
    public static function choix(): void
    {
        /* Avant tout chose, on vérifie que l'utilisateur est connecté, qu'il a le droit de voter pour la question,
        que la question est en phase de vote, et que la valeur saisie dans la barre d'adresse est comprise entre 0 et 5.
        Ensuite, on récupère l'objet Vote grâce à la méthode aVoté de la classe Votant.php
        On peut comparer la valeur du vote enregistré avec celle fournie par l'utilisateur, si c'est la même on supprime le vote,
        sinon on modifie simplement la valeur de l'objet Vote et on enregistre dans la base de donnée.
        Des triggers permettent de modifier automatiquement la valeur du nombre de votes dans la table proposition.
        */

        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        $question = (new QuestionRepository())->select($proposition->getIdQuestion());
        $votants = $question->getVotants();
        $bool = true;
        if (!ConnexionUtilisateur::estConnecte() || !Votant::estVotant($votants, ConnexionUtilisateur::getLoginUtilisateurConnecte())) {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas voter, vous n'êtes pas votant pour cette question.");
            $bool = false;
        }
        if ($question->getPhase() != 'vote') {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas voter tant que la phase de vote n'a pas débuté.");
            $bool = false;
        }
        if (!isset($_GET['valeur']) || $_GET['valeur'] > 5 || $_GET['valeur'] < 0) {
            MessageFlash::ajouter('warning', "Valeur de vote invalide");
            $bool = false;
        }
        if (!$bool) {
            $propositions = $question->getPropositions();
            Controller::afficheVue('view.php', ["pagetitle" => "Liste des propositions",
                "cheminVueBody" => "Proposition/list.php",
                "votants" => $votants,
                "propositions" => $propositions,
                "question" => $question]);
        }
        else{
            $vote = Votant::aVote($proposition, Votant::getVotes(ConnexionUtilisateur::getLoginUtilisateurConnecte()));
            if (!is_null($vote) && $vote->getValeur() == $_GET['valeur']) {
                // Supprime un vote
                (new VoteRepository())->delete($vote->getIdvote());
                MessageFlash::ajouter('success', 'Vote supprimé');
            } else if (!is_null($vote)) {
                // Modifie un vote
                $vote->setValeur($_GET['valeur']);
                (new VoteRepository())->update($vote);
                MessageFlash::ajouter('success', 'Vote mis à jour');
            } else {
                // Enregistre un vote
                $votant = new Votant($question);
                $votant->setIdentifiant(ConnexionUtilisateur::getLoginUtilisateurConnecte());
                $vote = new Vote($votant, $proposition, $_GET['valeur']);
                $voteBD = (new VoteRepository())->sauvegarder($vote);
                MessageFlash::ajouter('success', 'Vote pris en compte');
            }
            $propositions = (new PropositionRepository())->selectWhere($question->getId(), '*', 'idquestion');
            Controller::afficheVue('view.php', ["pagetitle" => "Liste des propositions",
                "cheminVueBody" => "Proposition/list.php",
                "votants" => $votants,
                "propositions" => $propositions,
                "question" => $question]);
        }
    }
}