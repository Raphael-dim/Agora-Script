<?php

namespace App\Vote\Controller;

use App\Vote\Lib\ConnexionUtilisateur;
use App\Vote\Config\FormConfig;
use App\Vote\Lib\MessageFlash;
use App\Vote\Lib\MotDePasse;
use App\Vote\Model\DataObject\Calendrier;
use App\Vote\Model\DataObject\CoAuteur;
use App\Vote\Model\DataObject\Proposition;
use App\Vote\Model\DataObject\PropositionSection;
use App\Vote\Model\DataObject\Question;
use App\Vote\Model\DataObject\Responsable;
use App\Vote\Model\HTTP\Session;
use App\Vote\Model\Repository\CalendrierRepository;
use App\Vote\Model\Repository\CoAuteurRepository;
use App\Vote\Model\Repository\PropositionRepository;
use App\Vote\Model\Repository\PropositionSectionRepository;
use App\Vote\Model\Repository\QuestionRepository;
use App\Vote\Model\Repository\ResponsableRepository;
use App\Vote\Model\Repository\UtilisateurRepository;
use App\Vote\Model\Repository\VoteRepository;

class ControllerProposition
{

    /**
     * Cette fonction permet à un utilisateur connecté qui est responsable de la question de créer une proposition pour cette question.
     * @return void
     */
    public static function create(): void
    {
        // Vérifie si l'utilisateur est connecté et si c'est le responsable de la question
        $bool = true;
        $question = (new QuestionRepository())->select($_GET["idQuestion"]);
        if (is_null($question)) {
            MessageFlash::ajouter("danger", "Question introuvable");
            Controller::redirect("index.php?controller=question&action=readAll");
        }
        if (!ConnexionUtilisateur::estConnecte() || !Responsable::estResponsable($question->getId(), ConnexionUtilisateur::getLoginUtilisateurConnecte())) {
            MessageFlash::ajouter("danger", "Vous ne pouvez pas créer de proposition, vous n'êtes pas responsable pour cette question.");
            $bool = false;
        }

        // Vérifie si la question est en phase d'écriture et n'a pas dépassé cette phase
        if ($question->getPhase() != 'ecriture' || $question->aPassePhase()) {
            MessageFlash::ajouter("danger", "Vous ne pouvez pas créer de proposition en dehors de la phase d'écriture.");
            $bool = false;
        }

        // Vérifie si l'utilisateur n'a pas déjà créé une proposition pour cette question
        if (Responsable::aCreeProposition($question, ConnexionUtilisateur::getLoginUtilisateurConnecte())) {
            MessageFlash::ajouter("danger", "Vous avez déjà crée une proposition pour cette question.");
            $bool = false;
        }

        // Si aucune condition n'est remplie, l'utilisateur est redirigé vers la liste des questions
        // Sinon, la méthode 'form' est appelée
        if (!$bool) {
            Controller::redirect("index.php?controller=question&action=readAll");
        } else {
            FormConfig::setArr('SessionProposition');
            FormConfig::startSession();
            self::form();
        }
    }

    /**
     * Cette fonction gère les différents étapes du formulaire de création de proposition
     */
    public static function form()
    {
        // Initialise la session pour la création de proposition
        Session::getInstance();
        FormConfig::setArr('SessionProposition');
        $view = "";
        $step = $_GET['step'] ?? 1; // Récupère l'étape actuelle si elle est définie, sinon définit l'étape 1 par défaut
        $params = array();
        $question = (new QuestionRepository())->select($_GET['idQuestion']);
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        if ($proposition->getIdResponsable() != ConnexionUtilisateur::getLoginUtilisateurConnecte() &&
            !CoAuteur::estCoAuteur(ConnexionUtilisateur::getLoginUtilisateurConnecte(), $_GET["idProposition"])) {
            MessageFlash::ajouter('danger', 'Vous ne pouvez pas modifier cette proposition');
            Controller::redirect('index.php');
        }
        $readOnly = "";
        // Vérifie si une proposition est en cours d'édition
        if (isset($_GET['idProposition'])) {
            // Si l'utilisateur n'est pas responsable, il ne peut pas éditer le titre de la proposition
            if (!Responsable::estResponsable($_GET['idQuestion'], ConnexionUtilisateur::getLoginUtilisateurConnecte())) {
                $readOnly = "readonly";
            }
        }
        // Gère l'affichage en fonction de l'étape actuelle
        switch ($step) {
            case 1:
                $view = "step-1";
                break;
            case 2:
                // Si une proposition est en cours d'édition et que l'utilisateur est co-auteur
                if (isset($_GET["idProposition"]) and CoAuteur::estCoAuteur(ConnexionUtilisateur::getLoginUtilisateurConnecte(), $_GET["idProposition"])) {
                    FormConfig::redirect('index.php?controller=proposition&action=updated');
                }
                if (isset($_POST["row"]) && isset($_POST["keyword"]) && "row" != "") {
                    // Recherche les utilisateurs correspondant au mot-clé
                    $row = $_POST['row'];
                    $keyword = $_POST['keyword'];
                    $utilisateurs = (new UtilisateurRepository())->selectKeywordUtilisateur($keyword);
                    $params['utilisateurs'] = $utilisateurs;
                } else {
                    // Récupère tous les utilisateurs
                    $utilisateurs = (new UtilisateurRepository())->selectAll();
                    $params['utilisateurs'] = $utilisateurs;
                }
                $view = "step-2";
                break;
            default :
                Controller::redirect('index.php');
        }

        Controller::afficheVue('view.php',
            array_merge(["pagetitle" => "Créer une proposition",
                "cheminVueBody" => "Proposition/create/" . $view . ".php",
                "question" => $question, "readOnly" => $readOnly], $params));

    }

    /**
     *Cette fonction affiche les détails de la proposition sélectionnée
     */
    public static function read()
    {
        // Récupère les informations sur la proposition sélectionnée
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        $question = (new QuestionRepository())->select($proposition->getIdQuestion());
        // Récupère les co-auteurs de la proposition
        $coAuts = (new CoAuteurRepository())->selectWhere($_GET['idProposition'], '*', 'idproposition', "Coauteurs");
        // Récupère les sections de la question
        $sections = $question->getSections();
        $idProposition = $_GET['idProposition'];
        // Affiche la vue avec les informations récupérées
        Controller::afficheVue('view.php', [
            "question" => $question,
            "idProposition" => $idProposition,
            "proposition" => $proposition,
            "sections" => $sections,
            "coAuts" => $coAuts,
            "pagetitle" => "Detail proposition",
            "cheminVueBody" => "Proposition/detail.php"]);
    }

    /**
     * Cette fonction affiche toutes les propositions associées à une question
     */
    public static function readAll()
    {
        // Vérifie si l'identifiant de la question est présent dans la requête
        if (!isset($_GET['idQuestion'])) {
            MessageFlash::ajouter("warning", "Veuillez renseigner un ID valide.");
            Controller::redirect('index.php?controller=question&action=readAll');
        }
        // Récupère la question correspondante à l'identifiant
        $question = (new QuestionRepository())->select($_GET['idQuestion']);

        // Récupère les votants de la question
        $votants = $question->getVotants();
        // Récupère les propositions associées à la question, triées par ordre d'insertion
        $propositions = $question->getPropositionsTrie();
        // S'il n'y a pas de propositions pour la question
        if (sizeof($propositions) == 0) {
            MessageFlash::ajouter('info', 'Il n\'y a pas de propositions pour cette question.');
            Controller::redirect('index.php?action=readAll&controller=question');
        }

        // Affiche la vue en fonction du système de vote
        if ($question->getSystemeVote() == 'majoritaire' || $question->getSystemeVote() == 'valeur') {

            Controller::afficheVue('view.php', ["pagetitle" => "Liste des propositions",
                "cheminVueBody" => "Proposition/listMajoritaire.php",
                "votants" => $votants,
                "propositions" => $propositions, "question" => $question]);
        } else {

            Controller::afficheVue('view.php', ["pagetitle" => "Liste des propositions",
                "cheminVueBody" => "Proposition/listUnique.php",
                "votants" => $votants,
                "propositions" => $propositions, "question" => $question]);
        }
    }

    public static function created()
    {
        /* On vérifie au préalable si l'utilisateur a le droit de créer une proposition pour la question donnée
        dans l'éventualité où il a tenté de le faire depuis la barre d'adresse. */
        FormConfig::setArr('SessionProposition');
        Session::getInstance();
        $user = Session::getInstance()->lire('user');

        $question = (new QuestionRepository())->select($_GET["idQuestion"]);
        if (is_null($question)) {
            // Si la question n'existe pas dans la base de donnée, on affiche un message d'erreur
            MessageFlash::ajouter("danger", "Question introuvable");
            // Et on redirige l'utilisateur vers la liste des questions
            Controller::redirect("index.php?controller=question&action=readAll");
        }
        $bool = true;

        // On vérifie que l'utilisateur connecté est bien un responsable de la question donnée
        if (!ConnexionUtilisateur::estConnecte() || !Responsable::estResponsable($question->getId(), ConnexionUtilisateur::getLoginUtilisateurConnecte())) {
            MessageFlash::ajouter("danger", "Vous ne pouvez pas créer de proposition, vous n'êtes pas responsable pour cette question.");
            $bool = false;
        }

        if ($question->getPhase() != 'ecriture' || $question->aPassePhase()) {
            MessageFlash::ajouter("danger", "Vous ne pouvez pas créer de proposition en dehors de la phase d'écriture.");
            $bool = false;
        }
        if (Responsable::aCreeProposition($question, ConnexionUtilisateur::getLoginUtilisateurConnecte())) {
            MessageFlash::ajouter("danger", "Vous avez déjà crée une proposition pour cette question.");
            $bool = false;
        }
        if (strlen($_SESSION[FormConfig::$arr]['titre']) > 480) {
            MessageFlash::ajouter("danger", "Vous n'avez pas respecté les contraintes.");
            $bool = false;
        }
        if (!$bool) {
            Controller::redirect("index.php?controller=question&action=readAll");
        }
        $responsable = new Responsable($question);
        $responsable->setIdentifiant(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        $proposition = new Proposition($_SESSION[FormConfig::$arr]['titre'], $responsable->getIdentifiant(), $question->getId(), 0, 0, false);
        $propositionBD = (new PropositionRepository())->sauvegarder($proposition, true);

        $coAuteursSelec = $_SESSION[FormConfig::$arr]['co-auteur'];
        $proposition->setId($propositionBD);
        foreach ($coAuteursSelec as $coAutSelec) {
            if (Responsable::estResponsable($question->getId(), $coAutSelec)) {
                MessageFlash::ajouter('danger', 'Vous n\'avez pas respecté les contraintes');
                (new PropositionRepository())->delete($propositionBD);
                Controller::redirect("index.php?controller=question&action=readAll");
            } else {
                $aut = new CoAuteur((new UtilisateurRepository())->select($coAutSelec), $proposition);
                (new CoAuteurRepository())->sauvegarder($aut);

            }
        }
        $sections = $question->getSections();
        foreach ($sections as $section) {
            if (strlen($_SESSION[FormConfig::$arr]['contenu' . $section->getId()]) > 1500) {
                MessageFlash::ajouter("danger", "Vous n'avez pas respecté les contraintes.");
                Controller::redirect("index.php?controller=question&action=readAll");
            }
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
    public
    static function update(): void
    {
        if (!isset($_GET['idProposition'])) {
            MessageFlash::ajouter("warning", "Veuillez renseigner un ID valide.");
            Controller::redirect('index.php');
        }
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        $question = (new QuestionRepository)->select($proposition->getIdQuestion());
        $bool = true;
        $coauteurs = $proposition->getCoAuteurs();
        $coauteursid = array();
        foreach ($coauteurs as $coauteur) {
            $coauteursid[] = $coauteur->getUtilisateur()->getIdentifiant();
        }

        if (!ConnexionUtilisateur::estConnecte()) {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas modifier une proposition si vous n'êtes pas connecté.");
            $bool = false;
        }
        if (!in_array(ConnexionUtilisateur::getLoginUtilisateurConnecte(), $coauteursid) && ConnexionUtilisateur::getLoginUtilisateurConnecte() != $proposition->getIdResponsable()) {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas modifier une proposition dont vous n'êtes pas co-auteur ou représentant.");
            $bool = false;
        }
        if ($question->getPhase() != 'ecriture') {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas modifier cette proposition en dehors de la phase d'écriture.");
            $bool = false;
        }
        if (!isset($_GET['idProposition'])) {
            MessageFlash::ajouter("warning", "Proposition introuvable");
            $bool = false;
        } else {
            $proposition = (new PropositionRepository())->select($_GET['idProposition']);
            if ($proposition == null) {
                MessageFlash::ajouter("warning", "Proposition introuvable");
                $bool = false;
            }
        }
        if (!$bool) {
            Controller::redirect("index.php?action=readAll&controller=proposition");
        } else {
            FormConfig::setArr('SessionProposition');
            FormConfig::startSession();
            $_SESSION[FormConfig::$arr]['idProposition'] = $_GET['idProposition'];
            FormConfig::initialiserSessionsProposition($proposition);
            FormConfig::redirect("index.php?controller=proposition&action=form&step=1&idProposition=" . $proposition->getId() . "&idQuestion=" . $question->getId());

        }
    }


    public
    static function updated()
    {
        //session_start();
        FormConfig::setArr('SessionProposition');
        $proposition = (new PropositionRepository())->select($_SESSION[FormConfig::$arr]["idProposition"]);
        $idquestion = $proposition->getIdQuestion();
        $question = (new QuestionRepository)->select($idquestion);

        $bool = true;
        if (!ConnexionUtilisateur::estConnecte() || (!Responsable::estResponsable($question->getId(), ConnexionUtilisateur::getLoginUtilisateurConnecte()) && !CoAuteur::estCoAuteur(ConnexionUtilisateur::getLoginUtilisateurConnecte(), $_SESSION[FormConfig::$arr]["idProposition"]))) {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas modifier cette proposition, 
        vous n'êtes ni responsable ni co-auteur pour cette proposition.");
            $bool = false;
        }
        if ($question->getPhase() != 'ecriture') {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas modifier cette proposition en dehors de la phase d'écriture.");
            $bool = false;
        }
        if (strlen($_SESSION[FormConfig::$arr]['titre']) > 480) {
            MessageFlash::ajouter("danger", "Vous n'avez pas respecté les contraintes.");
            $bool = false;
        }
        if (!$bool) {
            Controller::redirect("index.php?controller=proposition&action=readAll");
        } else {
            $sections = $question->getSections();
            (new PropositionSectionRepository())->delete($_SESSION[FormConfig::$arr]["idProposition"]);
            foreach ($sections as $section) {
                if (strlen($_SESSION[FormConfig::$arr]['contenu' . $section->getId()]) > 1500) {
                    MessageFlash::ajouter("danger", "Vous n'avez pas respecté les contraintes.");
                    Controller::redirect("index.php?controller=question&action=readAll");
                } else {
                    $propositionSection = new PropositionSection((new PropositionRepository())->select($_SESSION[FormConfig::$arr]["idProposition"]), $section, $_SESSION[FormConfig::$arr]['contenu' . $section->getId()]);
                    (new PropositionSectionRepository())->sauvegarder($propositionSection);
                }
            }
            $prop = new Proposition($_SESSION[FormConfig::$arr]['titre'], $proposition->getIdResponsable(), $idquestion, $proposition->getNbEtoiles(), $proposition->getNbVotes(), false);
            $prop->setId($_SESSION[FormConfig::$arr]["idProposition"]);
            (new PropositionRepository())->update($prop);

            if (Responsable::estResponsable($question->getId(), ConnexionUtilisateur::getLoginUtilisateurConnecte())) {
                $coAuteursSelec = $_SESSION[FormConfig::$arr]['co-auteur'];
                $coAuteurs = (new CoAuteurRepository())->selectWhere($_SESSION[FormConfig::$arr]["idProposition"], '*', "idproposition");
                foreach ($coAuteurs as $coAut) {
                    (new CoAuteurRepository())->deleteSpecific($coAut);
                }
                foreach ($coAuteursSelec as $coAutSelec) {
                    if (Responsable::estResponsable($question->getId(), $coAutSelec)) {
                        MessageFlash::ajouter('danger', 'Vous n\'avez pas respecté les contraintes');
                        Controller::redirect("index.php?controller=question&action=readAll");
                    } else {
                        $aut = new CoAuteur((new UtilisateurRepository())->select($coAutSelec), (new PropositionRepository())->select($_SESSION[FormConfig::$arr]["idProposition"]));
                        (new CoAuteurRepository())->sauvegarder($aut);
                    }
                }
                unset($_SESSION[FormConfig::$arr]['co-auteur']);
            }

            MessageFlash::ajouter("success", "La proposition a bien été modifié.");
            Controller::redirect("index.php?controller=proposition&action=readAll&idQuestion=" . $proposition->getIdQuestion());
        }
    }

    /**
     * Supprime une proposition
     * Vérifie d'abord si l'utilisateur le peut et affiche ensuite la vue pour confimer la suppression
     * @return void
     */
    public
    static function delete(): void
    {
        /* On vérifie au préalable si l'utilisateur a le droit de supprimer une question
        dans l'éventualité où il a tenté de le faire depuis la barre d'adresse. */

        if (!isset($_GET['idProposition'])) {
            MessageFlash::ajouter("warning", "Veuillez renseigner un ID valide.");
            Controller::redirect('index.php?controller=question&action=readAll');
        }
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        if (!ConnexionUtilisateur::estConnecte() ||
            ConnexionUtilisateur::getLoginUtilisateurConnecte() != $proposition->getIdResponsable()) {
            MessageFlash::ajouter("danger", "Vous ne pouvez pas supprimer une proposition dont vous n'êtes pas le responsable.");
            Controller::redirect('index.php?controller=question&action=readAll');
        } else if (!isset($_POST["cancel"]) && !isset($_POST["confirm"])) {
            Controller::afficheVue('view.php', ["pagetitle" => "Supprimer proposition",
                "cheminVueBody" => "confirm.php",
                "message" => "Êtes vous sûr de vouloir supprimer cette proposition?",
                "mdp" => true,
                "url" => 'index.php?controller=proposition&action=delete&idProposition=' . $_GET['idProposition']]);
        } else if (isset($_POST["cancel"])) {
            Controller::redirect('index.php?controller=question&action=readAll');
        } else if (isset($_POST["confirm"])) {
            $utilisateur = (new UtilisateurRepository())->select(ConnexionUtilisateur::getLoginUtilisateurConnecte());
            if (!MotDePasse::verifier($_POST['mdp'], $utilisateur->getMdpHache())) {
                MessageFlash::ajouter('warning', 'Mot de passe incorrect.');
                Controller::redirect('index.php?controller=proposition&action=delete&idProposition=' . $_GET['idProposition']);
            } else {
                (new PropositionRepository())->delete($_GET['idProposition']);
                MessageFlash::ajouter('success', 'La proposition a bien été supprimée');
                Controller::redirect("index.php?controller=question&action=readAll");
            }
        }
    }

    public
    static function eliminer()
    {
        $bool = false;
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        $question = (new QuestionRepository())->select($proposition->getIdQuestion());
        $propositions = $question->getPropositionsTrie();
        if (ConnexionUtilisateur::getLoginUtilisateurConnecte() == $question->getOrganisateur()->getIdentifiant()
            && ($question->getPhase() == 'debut') & $question->aPassePhase()) {
            $proposition->setEstEliminee(true);
            (new PropositionRepository())->update($proposition);
            foreach ($propositions as $propo) {
                if ($propo->getId() == $_GET['idProposition']) {
                    $bool = true;
                }
                if ($bool && !$propo->isEstEliminee()) {
                    $propo->setEstEliminee(true);
                    (new PropositionRepository())->update($propo);
                }
            }
            MessageFlash::ajouter('success', 'Les propositions sélectionnées ont été éliminées.');
        } else {
            MessageFlash::ajouter('danger', 'Vous n\'êtes pas responsable de cette question');
        }
        Controller::redirect('index.php?controller=proposition&action=readAll&idQuestion=' . $question->getId());
    }

    public
    static function annulerEliminer()
    {
        $bool = true;
        $proposition = (new PropositionRepository())->select($_GET['idProposition']);
        $question = (new QuestionRepository())->select($proposition->getIdQuestion());
        $propositions = $question->getPropositionsTrie();
        if (ConnexionUtilisateur::getLoginUtilisateurConnecte() == $question->getOrganisateur()->getIdentifiant()
            && ($question->getPhase() == 'debut') & $question->aPassePhase()) {
            $proposition->setEstEliminee(false);
            (new PropositionRepository())->update($proposition);
            foreach ($propositions as $propo) {
                if ($propo->getId() == $_GET['idProposition']) {
                    $bool = false;
                }
                if ($bool && $propo->isEstEliminee()) {
                    $propo->setEstEliminee(false);
                    (new PropositionRepository())->update($propo);
                }
            }
            MessageFlash::ajouter('success', 'Vous avez annulé l\'élimination de ces propositions.');
        } else {
            MessageFlash::ajouter('danger', 'Vous n\'êtes pas responsable de cette question');
        }
        Controller::redirect('index.php?controller=proposition&action=readAll&idQuestion=' . $question->getId());
    }


}