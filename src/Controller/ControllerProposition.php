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
use App\Vote\Model\HTTP\Session;
use App\Vote\Model\Repository\CalendrierRepository;
use App\Vote\Model\Repository\CoAuteurRepository;
use App\Vote\Model\Repository\PropositionRepository;
use App\Vote\Model\Repository\PropositionSectionRepository;
use App\Vote\Model\Repository\QuestionRepository;
use App\Vote\Model\Repository\ResponsableRepository;
use App\Vote\Model\Repository\UtilisateurRepository;

class ControllerProposition
{

    public static function create(): void
    {
        if (ConnexionUtilisateur::estConnecte()) {
            FormConfig::setArr('SessionProposition');
            FormConfig::startSession();
            self::form();
        } else {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas créer une proposition si vous n'êtes pas connecté.");
            Controller::redirect("index.php?action=connexion&controller=utilisateur");
        }
    }


    public static function form()
    {
        Session::getInstance();
        FormConfig::setArr('SessionProposition');
        $view = "";
        $step = $_GET['step'] ?? 1;
        $params = array();
        $params['question'] = (new QuestionRepository())->select($_GET['idQuestion']);
        switch ($step) {
            case 1:
                $view = "step-1";
                break;
            case 2:
                if (isset($_POST["row"]) && isset($_POST["keyword"]) && "row" != "") {
                    $row = $_POST['row'];
                    $keyword = $_POST['keyword'];
                    $utilisateurs = (new UtilisateurRepository())->selectKeyword($keyword, $row);
                    $params['utilisateurs'] = $utilisateurs;
                }
                $view = "step-2";
                break;
        }

        Controller::afficheVue('view.php',
            array_merge(["pagetitle" => "Créer une question",
                "cheminVueBody" => "Proposition/create/" . $view . ".php"], $params));
    }


    public static function read()
    {
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        $question = $proposition->getQuestion();
        $coAuts = (new CoAuteurRepository())->selectWhere($_GET['idProposition'], '*', 'idproposition', "Coauteurs");
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
        // Au lieu de faire un appel supplémentaire à la base de donnée, on vérifie s'il existe une proposition,
        // si oui, on récupère la question grâce à l'objet Proposition.
        if (sizeof($propositions) > 0) {
            $question = $propositions[0]->getQuestion();
        } else {
            $question = (new QuestionRepository())->select($_GET['idQuestion']);
        }
        $votants = $question->getVotants();
        Controller::afficheVue('view.php', ["pagetitle" => "Liste des propositions",
            "cheminVueBody" => "Proposition/list.php",
            "votants" => $votants,
            "propositions" => $propositions, "question" => $question]);
    }

    public static function created()
    {
        /* On vérifie au préalable si l'utilisateur a le droit de créer une proposition pour la question donnée
        dans l'éventualité où il a tenté de le faire depuis la barre d'adresse. */
        FormConfig::setArr('SessionProposition');
        Session::getInstance();
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
        $responsable = new Responsable($question);
        $responsable->setIdentifiant(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        $proposition = new Proposition($_SESSION[FormConfig::$arr]['titre'], $responsable, $question, 0);
        $propositionBD = (new PropositionRepository())->sauvegarder($proposition);

        $coAuteursSelec = $_SESSION[FormConfig::$arr]['co-auteur'];
        $proposition->setId($propositionBD);
        foreach ($coAuteursSelec as $coAutSelec) {
            $aut = new CoAuteur((new UtilisateurRepository())->select($coAutSelec), (new PropositionRepository())->select($propositionBD));
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


    /**
     * Met à jour une proposition
     * Vérifie si l'utilisateur le peut
     *
     * @return void
     */
    public static function update(): void
    {
        if (!isset($_GET['idProposition'])) {
            MessageFlash::ajouter("warning", "Veuillez renseigner un ID valide.");
            Controller::redirect('index.php?controller=proposition&action=readAll');
        }
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        $question = $proposition->getQuestion();
        $bool = true;
        $coauteurs = $proposition->getCoAuteurs();
        $coauteursid = array();
        foreach ($coauteurs as $coauteur) {
            $coauteursid[] = $coauteur->getUtilisateur()->getIdentifiant();
        }

        if (!ConnexionUtilisateur::estConnecte()) {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas modifier une proposition si vous n'etes pas connecté.");
            $bool = false;
        }
        if (!in_array(ConnexionUtilisateur::getLoginUtilisateurConnecte(), $coauteursid) && ConnexionUtilisateur::getLoginUtilisateurConnecte() != $proposition->getResponsable()->getIdentifiant()) {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas modifier une proposition dont vous n'êtes pas co-auteur ou représentant.");
            $bool = false;
        }

        if (!$bool) {
            Controller::redirect("index.php?action=readAll&controller=proposition");
        } else {
            FormConfig::setArr('SessionProposition');
            FormConfig::startSession();
            Controller::afficheVue('view.php', ["pagetitle" => "Modifier une proposition",
                "cheminVueBody" => "Proposition/create/step-1.php",
                "idProposition" => $_GET['idProposition'],
                "question" => $question]);
        }
    }


    public static function updated()
    {
        //session_start();
        FormConfig::setArr('SessionProposition');
        $proposition = (new PropositionRepository())->select($_SESSION[FormConfig::$arr]["idProposition"]);
        $question = $proposition->getQuestion();

        $user = Session::getInstance()->lire('user');
        $date = date('d-m-Y à H:i:s');
        $bool = true;
        $calendrier = $question->getCalendrier();
        if (!isset($user) || (!Responsable::estResponsable($proposition->getQuestion(), $user['id']) && !CoAuteur::estCoAuteur($user['id'],$_SESSION[FormConfig::$arr]["idProposition"]))) {
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
            Controller::redirect("index.php?controller=proposition&action=readAll");
        } else {
            $sections = $question->getSections();
            (new PropositionSectionRepository())->delete($_SESSION[FormConfig::$arr]["idProposition"]);
            foreach ($sections as $section) {
                $propositionSection = new PropositionSection((new PropositionRepository())->select($_SESSION[FormConfig::$arr]["idProposition"]), $section, $_SESSION[FormConfig::$arr]['contenu' . $section->getId()]);
                (new PropositionSectionRepository())->sauvegarder($propositionSection);
            }
            $prop = new Proposition($_SESSION[FormConfig::$arr]['titre'], $proposition->getResponsable(), $proposition->getQuestion(), $proposition->getNbVotes());
            $prop->setId($_SESSION[FormConfig::$arr]["idProposition"]);
            (new PropositionRepository())->update($prop);

            if (Responsable::estResponsable($question, ConnexionUtilisateur::getLoginUtilisateurConnecte())) {
                $coAuteursSelec = $_SESSION[FormConfig::$arr]['co-auteur'];
                $coAuteurs = (new CoAuteurRepository())->selectWhere($_SESSION[FormConfig::$arr]["idProposition"], '*', "idproposition");
                foreach ($coAuteurs as $coAut) {
                    (new CoAuteurRepository())->deleteSpecific($coAut);
                }
                foreach ($coAuteursSelec as $coAutSelec) {
                    $aut = new CoAuteur((new UtilisateurRepository())->select($coAutSelec), (new PropositionRepository())->select($_SESSION[FormConfig::$arr]["idProposition"]));
                    (new CoAuteurRepository())->sauvegarder($aut);
                }
                unset($_SESSION[FormConfig::$arr]['co-auteur']);
            }

            MessageFlash::ajouter("success", "La proposition a bien été modifié.");
            Controller::redirect("index.php?controller=proposition&action=readAll&idQuestion=" . $proposition->getQuestion()->getId());
        }
    }

    /**
     * Supprime une proposition
     * Vérifie d'abord si l'utilisateur le peut et affiche ensuite la vue pour confimer la suppression
     * @return void
     */
    public static function delete(): void
    {
        /* On vérifie au préalable si l'utilisateur a le droit de supprimer une question
        dans l'éventualité où il a tenté de le faire depuis la barre d'adresse. */

        if (!isset($_GET['idProposition'])) {
            MessageFlash::ajouter("warning", "Veuillez renseigner un ID valide.");
            Controller::redirect('index.php?controller=question&action=readAll');
        }
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        if (!ConnexionUtilisateur::estConnecte() ||
            ConnexionUtilisateur::getLoginUtilisateurConnecte() != $proposition->getResponsable()->getIdentifiant()) {
            MessageFlash::ajouter("danger", "Vous ne pouvez pas supprimer une proposition dont vous n'êtes pas le responsable.");
            Controller::redirect('index.php?controller=question&action=readAll');
        } else if (!isset($_POST["cancel"]) && !isset($_POST["confirm"])) {
            Controller::afficheVue('view.php', ["pagetitle" => "Supprimer proposition",
                "cheminVueBody" => "confirmProp.php",
                "message" => "Êtes vous sûr de vouloir supprimer cette proposition?",
                "id" => $_GET['idProposition']]);
        } else if (isset($_POST["cancel"])) {
            Controller::redirect('index.php?controller=question&action=readAll');
        } else if (isset($_POST["confirm"])) {
            (new PropositionRepository())->delete($_GET['idProposition']);
            MessageFlash::ajouter('success', 'La proposition a bien été supprimée');
            Controller::redirect("index.php?controller=question&action=readAll");
        }
    }
}