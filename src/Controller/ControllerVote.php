<?php

namespace App\Vote\Controller;


use App\Vote\Lib\ConnexionUtilisateur;
use App\Vote\Lib\MessageFlash;
use App\Vote\Model\DataObject\Proposition;
use App\Vote\Model\DataObject\Votant;
use App\Vote\Model\DataObject\Vote;
use App\Vote\Model\HTTP\Session;
use App\Vote\Model\Repository\CalendrierRepository;

use App\Vote\Model\Repository\PropositionRepository;
use App\Vote\Model\Repository\QuestionRepository;
use App\Vote\Model\Repository\VotantRepository;
use App\Vote\Model\Repository\VoteRepository;

class ControllerVote
{
    public static function choix(): void
    {
        if ($_GET['valeur'] > 5 || $_GET['valeur'] < 0) {
            MessageFlash::ajouter('warning', "Valeur de vote invalide");
            Controller::redirect('index.php?controller=accueil');
        }
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        $valeur = Votant::aVote($proposition, ConnexionUtilisateur::getLoginUtilisateurConnecte());
        if (!is_null($valeur) && $valeur == $_GET['valeur']) {
            Controller::redirect('index.php?controller=vote&action=delete&idproposition=' . $_GET['idProposition']);
        } else {
            $question = $proposition->getQuestion();

            $votant = (new VotantRepository())->select(ConnexionUtilisateur::getLoginUtilisateurConnecte());
            $vote = new Vote($votant, $proposition, $_GET['valeur']);
            $voteBD = (new VoteRepository())->sauvegarder($vote);
            $votes[] = $vote;
            $propositions = (new PropositionRepository())->selectWhere($question->getId(), '*',
                'idquestion', 'Propositions');
            Controller::afficheVue('view.php', [
                "pagetitle" => "Liste des propositions",
                "cheminVueBody" => "proposition/list2.php",
                "propositions" => $propositions,
                "proposition" => $proposition,
                "question" => $question,
                "votes" => $votes
            ]);
        }
    }


    public static function create(): void
    {
        $proposition = (new PropositionRepository())->select($_GET['idproposition']);
        $question = $proposition->getQuestion();
        $votant = (new VotantRepository())->select(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        $vote = new Vote($votant, $proposition);
        (new VoteRepository())->sauvegarder($vote);
        $propositions = (new PropositionRepository())->selectWhere($question->getId(), '*',
            'idquestion', 'Propositions');
        Controller::afficheVue('view.php', [
            "pagetitle" => "Liste des propositions",
            "cheminVueBody" => "proposition/list.php",
            "propositions" => $propositions,
            'question' => $question
        ]);
    }

    public static function update()
    {
        $proposition = (new PropositionRepository())->select($_GET['idpropositionAnc']);
        $question = $proposition->getQuestion();
        $votant = (new VotantRepository())->select(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        $vote = (new VoteRepository())->selectWhere(array('clef0' => $proposition->getId(),
            'clef1' => $votant->getIdentifiant()), '*',
            array('idproposition', 'idvotant'), 'Votes');

        (new VoteRepository())->delete($vote[0]->getIdvote());

        $proposition = (new PropositionRepository())->select($_GET['idproposition']);
        $vote = new Vote($votant, $proposition);

        (new VoteRepository())->sauvegarder($vote);
        $propositions = $question->getPropositions();
        Controller::afficheVue('view.php', [
            "pagetitle" => "Liste des propositions",
            "cheminVueBody" => "proposition/list.php",
            "propositions" => $propositions,
            'question' => $question]);
    }

    public static function delete()
    {
        $proposition = (new PropositionRepository())->select($_GET['idproposition']);
        $question = $proposition->getQuestion();
        $votant = (new VotantRepository())->select(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        $vote = (new VoteRepository())->selectWhere(array('clef0' => $proposition->getId(),
            'clef1' => $votant->getIdentifiant()), '*',
            array('idproposition', 'idvotant'), 'Votes');

        (new VoteRepository())->delete($vote[0]->getIdvote());
        $propositions = $question->getPropositions();
        Controller::afficheVue('view.php', [
            "pagetitle" => "Liste des propositions",
            "cheminVueBody" => "proposition/list.php",
            "propositions" => $propositions,
            'question' => $question]);
    }
}