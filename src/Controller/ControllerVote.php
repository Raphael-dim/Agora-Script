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

        On utilise par la méthode redirect pour éviter de répéter les appels à la BD
        */

        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        if (is_null($proposition)) {
            MessageFlash::ajouter('danger', 'Proposition introuvable');
            Controller::redirect('index.php');
        }
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
        if (!isset($_GET['valeur']) || $_GET['valeur'] > 6 || $_GET['valeur'] < 0) {
            MessageFlash::ajouter('warning', "Valeur de vote invalide");
            $bool = false;
        }
        if ($question->getSystemeVote() == 'unique') {
            MessageFlash::ajouter('danger', 'Système de vote incorrecte');
            $bool = false;
        }
        if ($proposition->isEstEliminee()) {
            MessageFlash::ajouter('danger', 'Cette proposition est éliminée.');
            $bool = false;
        }
        if (!$bool) {
            Controller::redirect('index.php?action=readAll&controller=proposition&idQuestion=' . $question->getId());

        } else {
            $vote = Votant::aVote($proposition, Votant::getVotes(ConnexionUtilisateur::getLoginUtilisateurConnecte()));
            if (!is_null($vote) && $vote->getValeur() == $_GET['valeur']) {
                // Supprime un vote
                MessageFlash::ajouter('info', 'Vous ne pouvez pas supprimer votre vote');
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
                $voteBD = (new VoteRepository())->sauvegarder($vote, true);
                MessageFlash::ajouter('success', 'Vote pris en compte');
            }
            $propositions = (new PropositionRepository())->selectWhere($question->getId(), '*', 'idquestion');
            Controller::afficheVue('view.php', ["pagetitle" => "Liste des propositions",
                "cheminVueBody" => "Proposition/listMajoritaire.php",
                "votants" => $votants,
                "propositions" => $propositions,
                "question" => $question]);
        }
    }

    public static function create()
    {
        // Booléen indiquant si la requête de vote est valide ou non

        $bool = true;
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        if (is_null($proposition)) {
            MessageFlash::ajouter('danger', 'Proposition introuvable');
            Controller::redirect('index.php');
        }
        $question = (new QuestionRepository())->select($proposition->getIdQuestion());
        if ($question->getSystemeVote() != 'unique') {
            MessageFlash::ajouter('danger', 'Système de vote incorrecte');
            $bool = false;
        }
        if ($proposition->isEstEliminee()) {
            MessageFlash::ajouter('danger', 'Cette proposition est éliminée.');
            $bool = false;
        }
        $votants = $question->getVotants();
        if (!ConnexionUtilisateur::estConnecte() || !Votant::estVotant($votants, ConnexionUtilisateur::getLoginUtilisateurConnecte())) {
            MessageFlash::ajouter("danger", "Vous ne pouvez pas voter, vous n'êtes pas votant pour cette question.");
            $bool = false;
        }
        if ($question->getPhase() != 'vote') {
            MessageFlash::ajouter("danger", "Vous ne pouvez pas voter tant que la phase de vote n'a pas débuté.");
            $bool = false;
        }
        if (!$bool) {
            Controller::redirect('index.php?action=readAll&controller=proposition&idQuestion=' . $question->getId());
        }
        $propositions = $question->getPropositions();
        $aVote = null;
        foreach ($propositions as $prop) {
            $vote = Votant::aVote($prop, Votant::getVotes(ConnexionUtilisateur::getLoginUtilisateurConnecte()));
            if (!is_null($vote))
                $aVote = $vote;
        }
        if (is_null($aVote)) {
            $votant = new Votant($question);
            $votant->setIdentifiant(ConnexionUtilisateur::getLoginUtilisateurConnecte());
            $vote = new Vote($votant, $proposition, 1);
            (new VoteRepository())->sauvegarder($vote);
            $propositions[array_search($proposition, $propositions)]->setNbVotes(0, 1);
            MessageFlash::ajouter('success', 'Vote pris en compte');
        } else {
            (new VoteRepository())->delete($aVote->getIdvote());
            $votant = new Votant($question);
            $votant->setIdentifiant(ConnexionUtilisateur::getLoginUtilisateurConnecte());
            $vote = new Vote($votant, $proposition, 1);
            (new VoteRepository())->sauvegarder($vote);
            $propositions[array_search($proposition, $propositions)]->setNbVotes(0, 1);
            $propositions[array_search($aVote->getProposition(), $propositions)]->setNbVotes(0, -1);
            MessageFlash::ajouter('success', 'Vote modifié');
        }
        Controller::afficheVue('view.php', ["pagetitle" => "Liste des propositions",
            "cheminVueBody" => "Proposition/listUnique.php",
            "votants" => $votants,
            "propositions" => $propositions,
            "question" => $question]);
    }

    public static function delete()
    {
        if (!isset($_GET['idProposition'])) {
            MessageFlash::ajouter('danger', 'Proposition introuvable');
            Controller::redirect('index.php');
        }
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        $question = (new QuestionRepository())->select($proposition->getIdQuestion());
        $aVote = Votant::aVote($proposition, Votant::getVotes(ConnexionUtilisateur::getLoginUtilisateurConnecte()));
        if (is_null($aVote)) {
            MessageFlash::ajouter('danger', 'Vous n\'avez pas voté pour cette proposition');
            Controller::redirect('index.php?action=readAll&controller=proposition&idQuestion=' . $question->getId());
        }
        (new VoteRepository())->delete($aVote->getIdvote());
        $votants = $question->getVotants();
        $propositions = $question->getPropositions();
        MessageFlash::ajouter('success', 'Vote supprimé');
        Controller::afficheVue('view.php', ["pagetitle" => "Liste des propositions",
            "cheminVueBody" => "Proposition/listUnique.php",
            "votants" => $votants,
            "propositions" => $propositions,
            "question" => $question]);
    }
}