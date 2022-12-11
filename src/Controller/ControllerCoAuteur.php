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

class   ControllerCoAuteur
{

    public static function create()
    {
        FormConfig::startSession();
        //session_start();
        FormConfig::setArr('SessionCoAuteur');
        //if(!isset($_SESSION[FormConfig::$arr]['co-auteur'])){
            $tests = (new CoAuteurRepository())->selectWhere($_GET['idProposition'],'*','idproposition',"Coauteurs");
            if(empty($tests)){
                $_SESSION[FormConfig::$arr]['co-auteur'] = array();
            }
            foreach ($tests as $test){
                $_SESSION[FormConfig::$arr]['co-auteur'][] = $test->getUtilisateur()->getIdentifiant();
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
        //session_start();
        FormConfig::setArr('SessionCoAuteur');
        $questions = (new QuestionRepository())->selectAll();
        $coAuteurs = $_SESSION[FormConfig::$arr]['co-auteur'];
        //var_dump($_SESSION[FormConfig::$arr]);

        foreach ($coAuteurs as $coAuteur) {
            if(is_null((new CoAuteurRepository())->select($coAuteur))){
                $utilisateur = new CoAuteur((new QuestionRepository())->select($_GET["idQuestion"]),(new UtilisateurRepository())->select($coAuteur));
                $responsableBD = (new CoAuteurRepository())->sauvegarder($utilisateur);
            }else{
                $utilisateur =(new CoAuteurRepository())->delete($coAuteur);
            }
        }

        Controller::afficheVue('view.php', ["pagetitle" => "Co-auteurs désigné",
                                                    "cheminVueBody" => "CoAuteur/created.php",
                                                    "questions" => $questions]);
        //session_destroy();
    }

}
