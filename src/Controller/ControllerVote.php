<?php

namespace App\Vote\Controller;


use App\Vote\Lib\ConnexionUtilisateur;
use App\Vote\Lib\MessageFlash;
use App\Vote\Model\DataObject\Votant;
use App\Vote\Model\DataObject\Vote;
use App\Vote\Model\Repository\PropositionRepository;
use App\Vote\Model\Repository\VotantRepository;
use App\Vote\Model\Repository\VoteRepository;

class ControllerVote
{
    public static function choix(): void
    {
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        $question = $proposition->getQuestion();
        $bool = true;
        if (!ConnexionUtilisateur::estConnecte() || !Votant::estVotant($question, ConnexionUtilisateur::getLoginUtilisateurConnecte())) {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas voter, vous n'êtes pas votant pour cette question.");
            $bool = false;
        }
        if ($question->getPhase() != 'vote') {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas voter tant que la phase de vote n'a pas débuté.");
            $bool = false;
        }
        if (!$bool) {
            Controller::redirect('index.php?controller=vote&action=delete&idProposition=' . $_GET['idProposition']);
        }
        if (!isset($_GET['valeur']) || $_GET['valeur'] > 5 || $_GET['valeur'] < 0) {
            MessageFlash::ajouter('warning', "Valeur de vote invalide");
            Controller::redirect('index.php?controller=accueil');
        }
        if (!isset($_GET['valeur']) || $_GET['valeur'] > 5 || $_GET['valeur'] < 0) {
            MessageFlash::ajouter('warning', "Valeur de vote invalide");
            Controller::redirect('index.php?controller=accueil');
        }
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        $question = $proposition->getQuestion();
        $vote = Votant::aVote($proposition, ConnexionUtilisateur::getLoginUtilisateurConnecte());
        if (!is_null($vote) && $vote->getValeur() == $_GET['valeur']) {
            Controller::redirect('index.php?controller=vote&action=delete&idproposition=' . $_GET['idProposition']);
        } else if (!is_null($vote)) {
            $vote->setValeur($_GET['valeur']);
            (new VoteRepository())->update($vote);
            Controller::redirect('index.php?controller=proposition&action=readAll&idQuestion=' . $question->getId());
        } else {
            $votant = (new VotantRepository())->select(ConnexionUtilisateur::getLoginUtilisateurConnecte());
            $vote = new Vote($votant, $proposition, $_GET['valeur']);
            $voteBD = (new VoteRepository())->sauvegarder($vote);
            $votes[] = $vote;
            Controller::redirect('index.php?controller=proposition&action=readAll&idQuestion=' . $question->getId());

        }
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
        Controller::redirect('index.php?controller=proposition&action=readAll&idQuestion=' . $question->getId());

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
        Controller::redirect('index.php?controller=proposition&action=readAll&idQuestion=' . $question->getId());

    }
}