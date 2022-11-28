<?php

namespace App\Vote\Controller;

use App\Vote\Model\DataObject\Vote;
use App\Vote\Model\HTTP\Session;
use App\Vote\Model\Repository\PropositionRepository;
use App\Vote\Model\Repository\QuestionRepository;
use App\Vote\Model\Repository\VotantRepository;
use App\Vote\Model\Repository\VoteRepository;

class ControllerVote
{

    public static function create():void{
        Session::getInstance();
        $proposition = (new PropositionRepository())->select($_GET['idproposition']);
        $votant = (new VotantRepository())->select($_SESSION['user']['id']);
        $vote = new Vote($votant,$proposition);
        (new VoteRepository())->sauvegarder($vote);
        Controller::afficheVue('view.php',['vote'=>$vote,
                                                        'pagetitle'=>'Vote',
                                                        'cheminVueBody'=>'Vote/create.php']);
    }

}