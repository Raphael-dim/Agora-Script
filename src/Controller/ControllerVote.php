<?php

namespace App\Vote\Controller;


use App\Vote\Model\DataObject\Proposition;
use App\Vote\Model\DataObject\Vote;
use App\Vote\Model\HTTP\Session;
use App\Vote\Model\Repository\CalendrierRepository;

use App\Vote\Model\Repository\PropositionRepository;
use App\Vote\Model\Repository\QuestionRepository;
use App\Vote\Model\Repository\VotantRepository;
use App\Vote\Model\Repository\VoteRepository;

class ControllerVote
{

    public static function create(): void
    {

        Session::getInstance();


        $proposition = (new PropositionRepository())->select($_GET['idproposition']);
        $question = $proposition->getQuestion();
        $propositions = (new PropositionRepository())->selectWhere($question->getId(), '*',
            'idquestion', 'Propositions');

        $votants = $question->getVotants();
        $sections = $question->getSections();


        if (!isset($_POST["cancel"]) && !isset($_POST["confirm"])) {
            Controller::afficheVue('view.php', ['pagetitle' => 'Vote',
                "message" => 'Voulez vous vraiment voter pour cette proposition ?',
                "proposition"=>$proposition,
                "question" => $question,
                'proposition' => $proposition,
                "sections" => $sections,
                "id" => $_GET['idproposition'],
                'cheminVueBody' => 'vote/confirmVote.php']);
        } else if (isset($_POST["cancel"])) {
            Controller::afficheVue('view.php', ["propositions" => $propositions,
                "votants" => $votants,
                'question' => $question,

                "pagetitle" => "Liste des propositions",
                "cheminVueBody" => "proposition/list.php"]);
        } else if (isset($_POST["confirm"])) {
            Session::getInstance();
            $votant = (new VotantRepository())->select($_SESSION['user']['id']);
            $vote = new Vote($votant, $proposition);
            (new VoteRepository())->sauvegarder($vote);
            Controller::afficheVue('view.php',
                ['vote' => $vote,
                    'proposition'=>$proposition,
                    'pagetitle' => 'Vote confirmé',
                    'cheminVueBody' => 'Vote/created.php',
                    'question' => $question,
                    'proposition' => $proposition,
                    'sections' => $sections]);
        }
    }
}