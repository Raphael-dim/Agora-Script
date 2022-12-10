<?php

namespace App\Vote\Controller;

use App\Vote\Lib\ConnexionUtilisateur;
use App\Vote\Config\FormConfig;
use App\Vote\Lib\MessageFlash;
use App\Vote\Model\DataObject\Calendrier;
use App\Vote\Model\DataObject\CoAuteur;
use App\Vote\Model\DataObject\Proposition;
use App\Vote\Model\DataObject\PropositionSection;
use App\Vote\Model\DataObject\Responsable;
use App\Vote\Model\Repository\CoAuteurRepository;
use App\Vote\Model\Repository\PropositionRepository;
use App\Vote\Model\Repository\PropositionSectionRepository;
use App\Vote\Model\Repository\QuestionRepository;
use App\Vote\Model\Repository\ResponsableRepository;

class ControllerProposition
{

    public static function create()
    {
        $user = Session::getInstance()->lire('user');
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $bool = true;
        if (!ConnexionUtilisateur::estConnecte() ||
            !Responsable::estResponsable($question, ConnexionUtilisateur::getLoginUtilisateurConnecte())) {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas créer de proposition, 
            vous n'êtes pas responsable pour cette question.");
            $bool = false;
        }
        if ($question->getPhase() != 'ecriture') {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas créer de proposition tant 
            que la phase d'écriture n'est pas en cours.");
            $bool = false;
        }
        if (Responsable::aCreeProposition($question, ConnexionUtilisateur::getLoginUtilisateurConnecte())) {
            MessageFlash::ajouter("warning", "Vous avez déjà crée une proposition pour cette question.");
            $bool = false;
        }
        if (!$bool) {
            Controller::redirect("index.php?controller=question&action=readAll");
        } else {
            switch($step){
                case 1:
                    Controller::afficheVue('view.php', ["pagetitle" => "Accueil",
                        "cheminVueBody" => "Proposition/step-1.php",
                        "question" => $question]);
                    break;
                case 2:
                    if (isset($_POST["row"]) && isset($_POST["keyword"]) && "row" != "") {
                        $row = $_POST['row'];
                        $keyword = $_POST['keyword'];
                        $utilisateurs = (new UtilisateurRepository())->selectKeyword($keyword, $row);
                    }else{
                        $utilisateurs = [];
                    }
                    Controller::afficheVue('view.php', ["pagetitle" => "Accueil",
                        "cheminVueBody" => "Proposition/step-2.php",
                        "question" => $question,
                        "utilisateurs" => $utilisateurs]);
                    break;
            }

        }
    }

    public static function read()
    {
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        $question = $proposition->getQuestion();
        $coAuts = (new CoAuteurRepository())->selectWhere($_GET['idProposition'],'*','idproposition',"Coauteurs");
        //var_dump((new CoAuteurRepository())->selectAll());
        $sections = $question->getSections();
        $idProposition = $_GET['idProposition'];
        Controller::afficheVue('view.php', [
            "question" => $question,
            "idProposition" => $idProposition,
            "proposition" => $proposition,
            "sections" => $sections,
            "coAuts" => $coAuts,
            "pagetitle" => "Detail proposition",
            "cheminVueBody" => "Proposition/detail.php"]);
    }

    public static function readAll()
    {
        if (!isset($_GET['idQuestion'])) {
            MessageFlash::ajouter("warning", "Veuillez renseigner un ID valide.");
            Controller::redirect('index.php?controller=question&action=readAll');
        }
        $propositions = (new PropositionRepository())->selectWhere($_GET['idQuestion'], '*', 'idquestion');
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $votants = $question->getVotants();
        Controller::afficheVue('view.php', ["pagetitle" => "Liste des propositions",
            "votants" => $votants,
            "cheminVueBody" => "Proposition/list.php",
            "propositions" => $propositions, "question" => $question]);
    }

    public static function created()
    {
        $user = Session::getInstance()->lire('user');
        $question = (new QuestionRepository())->select($_GET["idQuestion"]);
        $bool = true;
        if (!ConnexionUtilisateur::estConnecte() || !Responsable::estResponsable($question, ConnexionUtilisateur::getLoginUtilisateurConnecte())) {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas créer de proposition, 
            vous n'êtes pas responsable pour cette question.");
            $bool = false;
        }
        if ($question->getPhase() != 'ecriture') {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas créer de proposition tant 
            que la phase d'écriture n'est pas en cours.");
            $bool = false;
        }
        if (Responsable::aCreeProposition($question, ConnexionUtilisateur::getLoginUtilisateurConnecte())) {
            MessageFlash::ajouter("warning", "Vous avez déjà crée une proposition pour cette question.");
            $bool = false;
        }
        if (!$bool) {
            Controller::redirect("index.php?controller=question&action=readAll");
        }
        $responsable = (new ResponsableRepository())->select($user['id']);
        $proposition = new Proposition($_POST['titre'], $responsable, $question, 0);
        $propositionBD = (new PropositionRepository())->sauvegarder($proposition);

        $coAuteursSelec = $_SESSION[FormConfig::$arr]['co-auteur'];
        $proposition->setId($propositionBD);
        foreach ($coAuteursSelec as $coAutSelec){
            $aut = new CoAuteur((new UtilisateurRepository())->select($coAutSelec),(new PropositionRepository())->select($propositionBD));
            (new CoAuteurRepository())->sauvegarder($aut);
        }
        $sections = $question->getSections();
        foreach ($sections as $section) {
            $propositionSection = new PropositionSection($proposition, $section, $_SESSION[FormConfig::$arr]['contenu' . $section->getId()]);
            (new PropositionSectionRepository())->sauvegarder($propositionSection);
        }

        MessageFlash::ajouter("success", "La proposition a bien été crée.");
        Controller::redirect("index.php?controller=question&action=readAll");
    }

    public static function update()
    {
        FormConfig::setArr('SessionProposition');
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        $calendrier = $proposition->getQuestion()->getCalendrier();
        $date = date("d/m/Y à H:i:s");
        $user = Session::getInstance()->lire('user');
        $bool = true;

        if (!isset($user) || (!Responsable::estResponsable($proposition->getQuestion(), $user['id']) && !CoAuteur::estCoAuteur($user['id'],$proposition))) {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas modifier cette proposition, 
        vous n'êtes ni responsable ni co-auteur pour cette proposition.");
            $bool = false;
        }
        if ($calendrier->getDebutEcriture() > $date || $calendrier->getFinEcriture() < $date) {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas modifier cette proposition tant 
        que la phase d'écriture n'est pas en cours.");
            $bool = false;
        }
        if (!$bool) {
            Controller::redirect("index.php?controller=proposition&action=readAll&idQuestion=" . $proposition->getQuestion()->getId());
        }else {
            $step = $_GET['step'];
            switch ($step) {
                case 1:
                    unset($_SESSION[FormConfig::$arr]['co-auteur']);
                    $propositionSections = (new PropositionSectionRepository())->selectWhere($_GET['idProposition'], '*', 'idproposition');
                    Controller::afficheVue('view.php', ["pagetitle" => "Accueil",
                        "cheminVueBody" => "Proposition/update.php",
                        "proposition" => $proposition,
                        "propositionSections" => $propositionSections]);
                    break;
                case 2:
                    $tests = (new CoAuteurRepository())->selectWhere($_GET['idProposition'], '*', 'idproposition', "Coauteurs");
                    if (!isset($_SESSION[FormConfig::$arr]['co-auteur'])) {
                        $_SESSION[FormConfig::$arr]['co-auteur'] = array();
                        if (!empty($tests)) {
                            foreach ($tests as $test) {
                                if ($_SESSION[FormConfig::$arr]['co-auteur'] != $test->getUtilisateur()->getIdentifiant()) {
                                    $_SESSION[FormConfig::$arr]['co-auteur'][] = $test->getUtilisateur()->getIdentifiant();
                                }
                            }
                        }
                    }
                    var_dump($_SESSION[FormConfig::$arr]['co-auteur']);
                    if (isset($_POST["row"]) && isset($_POST["keyword"]) && "row" != "") {
                        $row = $_POST['row'];
                        $keyword = $_POST['keyword'];
                        $utilisateurs = (new UtilisateurRepository())->selectKeyword($keyword, $row);
                    }else{
                        $utilisateurs = [];
                    }
                    Controller::afficheVue('view.php', ["pagetitle" => "Accueil",
                        "cheminVueBody" => "Proposition/create/step-2.php",
                        "utilisateurs" => $utilisateurs]);
                    break;
            }
        }
    }

    public static function updated()
    {
        //session_start();
        FormConfig::setArr('SessionProposition');
        $proposition = (new PropositionRepository())->select($_GET["idProposition"]);
        $question = $proposition->getQuestion();

        $user = Session::getInstance()->lire('user');
        $date = date('d-m-Y à H:i:s');
        $bool = true;
        $calendrier = $question->getCalendrier();
        if (!isset($user) || (!Responsable::estResponsable($proposition->getQuestion(), $user['id']) && !CoAuteur::estCoAuteur($user['id'],$proposition))) {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas modifier cette proposition, 
        vous n'êtes ni responsable ni co-auteur pour cette proposition.");
            $bool = false;
        }
        if ($calendrier->getDebutEcriture() > $date || $calendrier->getFinEcriture() < $date) {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas modifier cette proposition tant 
        que la phase d'écriture n'a pas débuté.");
            $bool = false;
        }
        if (!$bool) {
            Controller::redirect("index.php?controller=proposition&action=readAll&idQuestion=" . $proposition->getQuestion()->getId());
        }else{
            $sections = $question->getSections();
            (new PropositionSectionRepository())->delete($_GET["idProposition"]);
            foreach ($sections as $section) {
                $propositionSection = new PropositionSection((new PropositionRepository())->select($_GET["idProposition"]), $section, $_SESSION[FormConfig::$arr]['contenu' . $section->getId()]);
                (new PropositionSectionRepository())->sauvegarder($propositionSection);
            }
            $prop = new Proposition($_SESSION[FormConfig::$arr]['titre'],$proposition->getResponsable(),$proposition->getQuestion(),$proposition->getNbVotes());
            $prop->setId($_GET["idProposition"]);
            (new PropositionRepository())->update($prop);

            if(!CoAuteur::estCoAuteur($_SESSION['user']['id'],$proposition)){
                $coAuteursSelec = $_SESSION[FormConfig::$arr]['co-auteur'];
                $coAuteurs = (new CoAuteurRepository())->selectWhere($_GET["idProposition"],'*',"idproposition");
                foreach ($coAuteurs as $coAut){
                    (new CoAuteurRepository())->deleteSpecific($coAut);
                }
                foreach ($coAuteursSelec as $coAutSelec){
                    $aut = new CoAuteur((new UtilisateurRepository())->select($coAutSelec),(new PropositionRepository())->select($_GET["idProposition"]));
                    (new CoAuteurRepository())->sauvegarder($aut);
                }
                unset($_SESSION[FormConfig::$arr]['co-auteur']);
            }

            MessageFlash::ajouter("success", "La proposition a bien été modifié.");
            Controller::redirect("index.php?controller=proposition&action=readAll&idQuestion=" . $proposition->getQuestion()->getId());
        }
    }
}