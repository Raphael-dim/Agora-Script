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

        if (!isset($_GET['valeur']) || $_GET['valeur'] > 5 || $_GET['valeur'] < 0) {
            MessageFlash::ajouter('warning', "Valeur de vote invalide");
            Controller::redirect('index.php?controller=accueil');
        }
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        $question = $proposition->getQuestion();
        $vote = Votant::aVote($proposition, ConnexionUtilisateur::getLoginUtilisateurConnecte());
        if (!is_null($vote) && $vote->getValeur() == $_GET['valeur']) {
            MessageFlash::ajouter("success", "Votre vote a été supprimé");
            Controller::redirect('index.php?controller=vote&action=delete&idProposition=' . $_GET['idProposition']);
        } else if (!is_null($vote)) {
            $vote->setValeur($_GET['valeur']);
            (new VoteRepository())->update($vote);
            MessageFlash::ajouter("success", "Votre vote a été modifié");
            Controller::redirect('index.php?controller=proposition&action=readAll&idQuestion=' . $question->getId());
        } else {
            $votant = (new VotantRepository())->select(ConnexionUtilisateur::getLoginUtilisateurConnecte());
            $vote = new Vote($votant, $proposition, $_GET['valeur']);
            $voteBD = (new VoteRepository())->sauvegarder($vote);
            MessageFlash::ajouter("success", "Votre vote a été pris en compte");
            Controller::redirect('index.php?controller=proposition&action=readAll&idQuestion=' . $question->getId());

        }
    }

    public static function update()
    {
        $question = $proposition->getQuestion();
        $votant = (new VotantRepository())->select(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        $vote = (new VoteRepository())->selectWhere(array('clef0' => $proposition->getId(),
            'clef1' => $votant->getIdentifiant()), '*',
            array('idproposition', 'idvotant'), 'Votes');
        (new VoteRepository())->delete($vote[0]->getIdvote());
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        $vote = new Vote($votant, $proposition);
        (new VoteRepository())->sauvegarder($vote);
        Controller::redirect('index.php?controller=proposition&action=readAll&idQuestion=' . $question->getId());

    }

    public static function delete()
    {
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        $question = $proposition->getQuestion();
        $votant = (new VotantRepository())->select(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        $vote = (new VoteRepository())->selectWhere(array('clef0' => $proposition->getId(),
            'clef1' => $votant->getIdentifiant()), '*',
            array('idproposition', 'idvotant'), 'Votes');

        (new VoteRepository())->delete($vote[0]->getIdvote());
        Controller::redirect('index.php?controller=proposition&action=readAll&idQuestion=' . $question->getId());

    }
}