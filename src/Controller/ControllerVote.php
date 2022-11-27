<?php

namespace App\Vote\Controller;

use App\Vote\Model\Repository\QuestionRepository;

class ControllerVote
{

    public static function create():void{
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        Controller::afficheVue('view.php',['question'=>$question,
                                                        'pagetitle'=>'Vote',
                                                        'cheminVueBody'=>'Vote/create.php']);
    }

}