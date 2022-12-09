<?php

namespace App\Vote\Controller;

use App\Vote\Config\FormConfig;
use App\Vote\Model\DataObject\CoAuteur;
use App\Vote\Model\DataObject\Proposition;
use App\Vote\Model\DataObject\PropositionSection;
use App\Vote\Model\DataObject\Question;
use App\Vote\Model\DataObject\Responsable;
use App\Vote\Model\DataObject\Utilisateur;
use App\Vote\Model\Repository\CoAuteurRepository;
use App\Vote\Model\Repository\PropositionRepository;
use App\Vote\Model\Repository\PropositionSectionRepository;
use App\Vote\Model\Repository\QuestionRepository;
use App\Vote\Model\Repository\ResponsableRepository;
use App\Vote\Model\Repository\UtilisateurRepository;

class ControllerCoAuteur
{

    public static function create()
    {
        FormConfig::startSession();
        FormConfig::setArr('SessionCoAuteur');
        //if(!isset($_SESSION[FormConfig::$arr]['co-auteur'])){
            $tests = (new CoAuteurRepository())->selectWhere($_GET['idProposition'],'*','idproposition',"Coauteurs");
            $_SESSION[FormConfig::$arr]['co-auteur'] = array();
            if(!empty($tests)){
                foreach ($tests as $test){
                    $_SESSION[FormConfig::$arr]['co-auteur'][] = $test->getUtilisateur()->getIdentifiant();
                }
            }
        //}
        if (isset($_POST["row"]) && isset($_POST["keyword"]) && "row" != "") {
            $row = $_POST['row'];
            $keyword = $_POST['keyword'];
            $utilisateurs = (new UtilisateurRepository())->selectKeyword($keyword, $row);
            $params['utilisateurs'] = $utilisateurs;
        }else{
            $params = array();
        }
        Controller::afficheVue('view.php',  array_merge(["pagetitle" => "Désigner un co-auteur",
            "cheminVueBody" => "CoAuteur/step-1.php"], $params));
    }

    public static function created()
    {
        FormConfig::startSession();
        FormConfig::setArr('SessionCoAuteur');
        $questions = (new QuestionRepository())->selectAll();
        $coAuteursSelec = $_SESSION[FormConfig::$arr]['co-auteur'];
        $coAuteurs = (new CoAuteurRepository())->selectWhere($_GET["idProposition"],'*',"idproposition");

        foreach ($coAuteurs as $coAut){
            (new CoAuteurRepository())->delete($coAut->getUtilisateur()->getIdentifiant());
        }
        foreach ($coAuteursSelec as $coAutSelec){
            $aut = new CoAuteur((new UtilisateurRepository())->select($_SESSION[FormConfig::$arr]['co-auteur']),(new PropositionRepository())->select($_GET["idProposition"]));
            (new CoAuteurRepository())->sauvegarder($aut);
        }


        Controller::afficheVue('view.php', ["pagetitle" => "Co-auteurs désigné",
                                                    "cheminVueBody" => "CoAuteur/created.php",
                                                    "questions" => $questions]);
        //session_destroy();
    }

}
