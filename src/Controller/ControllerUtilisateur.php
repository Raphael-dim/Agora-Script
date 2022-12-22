<?php

namespace App\Vote\Controller;

use App\Vote\Lib\ConnexionUtilisateur;
use App\Vote\Lib\MessageFlash;
use App\Vote\Lib\MotDePasse;
use App\Vote\Model\DataObject\Utilisateur;
use App\Vote\Model\HTTP\Session;
use App\Vote\Model\Repository\PropositionRepository;
use App\Vote\Model\Repository\QuestionRepository;
use App\Vote\Model\Repository\UtilisateurRepository;

class ControllerUtilisateur
{
    public static function readAll()
    {
        $utilisateurs = (new UtilisateurRepository())->selectAll();     //appel au modèle pour gerer la BD
        Controller::afficheVue('view.php',
            ["utilisateurs" => $utilisateurs,
                "pagetitle" => "Liste des Utilisateurs",
                "cheminVueBody" => "Utilisateurs/list.php"]); //"redirige" vers la vue
    }

    public static function connexion()
    {
        Controller::afficheVue('view.php',
            ["pagetitle" => "Connexion",
                "cheminVueBody" => "Utilisateurs/formulaireConnexion.php"]);
    }

    public static function disconnected()
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            MessageFlash::ajouter("warning", "Vous pouvez pas vous déconnecter sans être connecté");
            Controller::redirect("index.php?action=connexion&controller=utilisateur");
        }
        Session::getInstance();
        ConnexionUtilisateur::deconnecter();
        MessageFlash::ajouter("success", "Vous avez été déconnecté");
        Controller::redirect('index.php?controller=utilisateur&action=connexion');
    }

    public static function connecter()
    {
        $mdp = $_POST['mdp'];
        $id = $_POST['identifiant'];
        $utilisateur = ((new UtilisateurRepository())->select($id));

        if (!isset($utilisateur)) {
            MessageFlash::ajouter('warning', 'Utilisateur introuvable');
            Controller::redirect('index.php?controller=utilisateur&action=connexion');
        } else {
            if (!MotDePasse::verifier($mdp, $utilisateur->getMdpHache())) {
                MessageFlash::ajouter('warning', 'Mot de passe incorrect');
                Controller::redirect('index.php?controller=utilisateur&action=connexion');
            } else {
                ConnexionUtilisateur::connecter($utilisateur->getIdentifiant());
                MessageFlash::ajouter('success', 'Vous êtes connecté');
                Controller::redirect("index.php?controller=accueil&action=home");
            }
        }
    }

    public static function read()
    {
        Session::getInstance();
        $utilisateur = ((new UtilisateurRepository))->select($_SESSION['user']['id']);
        $questions = (new QuestionRepository())->selectWhere($_SESSION['user']['id'], '*', 'idorganisateur');
        $propositions = (new PropositionRepository())->selectWhere($_SESSION['user']['id'], '*', 'idresponsable');
        Controller::afficheVue('view.php', ['pagetitle' => "Profil",
            "cheminVueBody" => "Utilisateurs/detail.php", "utilisateur" => $utilisateur,
            "questions" => $questions, "propositions" => $propositions]);
    }

    public static function create()
    {
        Controller::afficheVue('view.php',
            ["pagetitle" => "Inscription",
                "cheminVueBody" => "Utilisateurs/create.php"]);
    }

    public static function created()
    {
        $mdp = $_POST['mdp'];
        if (strlen($mdp) < 6) {
            MessageFlash::ajouter('info', 'Votre mot de passe doit contenir au moins 6 caractères.');
            Controller::redirect('index.php?controller=utilisateur&action=create');
        }
        $bool = false;
        for ($i = 0; $i < 10 && !$bool; $i++) {
            if (strpos($mdp, $i)) {
                $bool = true;
            }
        }
        if (!$bool) {
            MessageFlash::ajouter('info', 'Votre mot de passe doit contenir au moins 1 chiffre et une lettre.');
            Controller::redirect('index.php?controller=utilisateur&action=create');
        }
        if ($_POST['mdp'] != $_POST['mdp2']) {
            MessageFlash::ajouter('warning', 'Les mots de passes sont différents');
            Controller::redirect('index.php?controller=utilisateur&action=create');
        } else if (Utilisateur::identifiantExiste($_POST['identifiant'])) {
            MessageFlash::ajouter('warning', 'Cet identifiant existe déjà');
            Controller::redirect('index.php?controller=utilisateur&action=create');
        } else {
            $utilisateur = Utilisateur::construireDepuisFormulaire($_POST);
            (new UtilisateurRepository())->sauvegarder($utilisateur);
            MessageFlash::ajouter("success", "Le compte a bien crée");
            ConnexionUtilisateur::connecter($utilisateur->getIdentifiant());
            Controller::redirect("index.php?controller=accueil");
        }
    }

    public static function search()
    {
        Controller::afficheVue('view.php',
            ["pagetitle" => "Rechercher un utilisateur",
                "cheminVueBody" => "Utilisateurs/search.php"]);
    }

    public static function readKeyword()
    {
        $row = $_POST['row'];
        $keyword = $_POST['keyword'];
        $utilisateurs = (new UtilisateurRepository())->selectKeyword($keyword, $row);
        Controller::afficheVue('view.php',
            ["utilisateurs" => $utilisateurs,
                "pagetitle" => "Liste des Utilisateurs",
                "cheminVueBody" => "Utilisateurs/list.php"]);
    }

    public static function select()
    {
        $row = $_POST['row'];
        $keyword = $_POST['keyword'];
        $utilisateurs = (new UtilisateurRepository())->selectKeyword($keyword, $row);
        Controller::afficheVue('view.php',
            ["utilisateurs" => $utilisateurs,
                "pagetitle" => "Liste des Utilisateurs",
                "cheminVueBody" => "Utilisateurs/step-4.php"]);
    }

    public static function update()
    {
        $utilisateur = (new UtilisateurRepository())->select($_GET['idUtilisateur']);
        if (!ConnexionUtilisateur::estConnecte() ||
            ConnexionUtilisateur::getLoginUtilisateurConnecte() != $utilisateur->getIdentifiant()) {
            MessageFlash::ajouter("warning", "Connectez-vous à votre compte pour le modifier.");
            Controller::redirect("index.php?action=connexion&controller=utilisateur");
        } else {
            Controller::afficheVue('view.php',
                ["utilisateur" => $utilisateur,
                    "pagetitle" => "Modifier les informations",
                    "cheminVueBody" => "Utilisateurs/update.php"]
            );
        }
    }

    public static function updated()
    {
        $utilisateur = (new UtilisateurRepository())->select($_POST['identifiant']);
        if (!ConnexionUtilisateur::estConnecte()) {
            MessageFlash::ajouter("warning", "Connectez-vous à votre compte pour le modifier.");
            Controller::redirect("index.php?action=connexion&controller=utilisateur");
        }
        if (ConnexionUtilisateur::getLoginUtilisateurConnecte() != $utilisateur->getIdentifiant()) {
            MessageFlash::ajouter("warning", "Vous ne pouvez pas modifier un compte qui ne vous appartient pas.");
            Controller::redirect("index.php?action=readAll&controller=question");
        }
        if (!MotDePasse::verifier($_POST['ancienMDP'], $utilisateur->getMdpHache())) {
            MessageFlash::ajouter('warning', 'L\'ancien mot de passe n\'est pas valide');
            Controller::redirect('index.php?controller=utilisateur&action=update&idUtilisateur=' . $utilisateur->getIdentifiant());
        }
        if ($_POST['mdp'] != $_POST['mdp2']) {
            MessageFlash::ajouter('warning', 'Les mots de passes sont différents');
            Controller::redirect('index.php?controller=utilisateur&action=update&idUtilisateur=' . $utilisateur->getIdentifiant());
        } else {
            $utilisateur->setNom($_POST['nom']);
            $utilisateur->setPrenom($_POST['prenom']);
            $utilisateur->setMdpHache($_POST['mdp']);
            (new UtilisateurRepository())->update($utilisateur);
            MessageFlash::ajouter('success', 'Vos informations ont été mises à jour');
            Controller::redirect("index.php?controller=utilisateur&action=read&idUtilisateur=" . $utilisateur->getIdentifiant());
        }

    }

    public static function delete()
    {
        if (!isset($_GET['idUtilisateur'])) {
            MessageFlash::ajouter('info', 'Veuillez saisir un identifiant valide');
            Controller::redirect('index.php?controller=accueil');
        }
        if (!ConnexionUtilisateur::estConnecte() || ConnexionUtilisateur::getLoginUtilisateurConnecte() != $_GET['idUtilisateur']) {
            MessageFlash::ajouter('info', 'Vous ne pouvez pas supprimer ce compte');
            Controller::redirect('index.php?controller=accueil');
        } else if (!isset($_POST["cancel"]) && !isset($_POST["confirm"])) {
            Controller::afficheVue('view.php', ["pagetitle" => "Demande de confirmation ",
                "cheminVueBody" => "confirm.php",
                "url" => "index.php?action=delete&controller=utilisateur&idUtilisateur=" . $_GET['idUtilisateur'],
                "message" => "Êtes vous sûr de vouloir supprimer votre compte ?"]);
        } else if (isset($_POST["cancel"])) {
            Controller::redirect("index.php?controller=utilisateur&action=read");
        } else if (isset($_POST["confirm"])) {
            (new UtilisateurRepository())->delete($_GET['idUtilisateur']);
            MessageFlash::ajouter('success', "Votre compte a bien été supprimé");
            ConnexionUtilisateur::deconnecter();
            Controller::redirect("index.php?controller=accueil");
        }
    }
}