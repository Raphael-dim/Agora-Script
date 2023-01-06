<?php

namespace App\Vote\Controller;

use App\Vote\Lib\ConnexionUtilisateur;
use App\Vote\Lib\MessageFlash;
use App\Vote\Model\DataObject\Message;
use App\Vote\Model\DataObject\Utilisateur;
use App\Vote\Model\Repository\MessageRepository;
use App\Vote\Model\Repository\UtilisateurRepository;

class ControllerMessage
{
    public static function readAll()
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            MessageFlash::ajouter('warning', 'Vous devez être connecté pour accéder à votre messagerie');
            Controller::redirect('index.php?action=connexion&controller=utilisateur');
        }
        $envoyes = (new MessageRepository())->selectWhere(ConnexionUtilisateur::getLoginUtilisateurConnecte(), '*', 'idAuteur');
        $recus = (new MessageRepository())->selectWhere(ConnexionUtilisateur::getLoginUtilisateurConnecte(), '*', 'idDestinataire');
        Controller::afficheVue('view.php',
            ["recus" => $recus,
                "envoyes" => $envoyes,
                "pagetitle" => "Messagerie",
                "cheminVueBody" => "Message/list.php"]);
    }

    public static function read()
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            MessageFlash::ajouter('warning', 'Vous devez être connecté pour accéder à votre messagerie');
            Controller::redirect('index.php?action=connexion&controller=utilisateur');
        }
        if (!Utilisateur::identifiantExiste($_GET['idContact'])) {
            MessageFlash::ajouter('warning', 'Cet utilisateur est introuvable');
            Controller::redirect('index.php?action=readAll&controller=message');
        }
        $envoyes = (new MessageRepository())->selectWhere(
            array("clef0" => ConnexionUtilisateur::getLoginUtilisateurConnecte(), "clef1" => $_GET['idContact']),
            '*', array("clef0" => 'idAuteur', "clef1" => 'idDestinataire'), 'Messages', 'date');
        $recus = (new MessageRepository())->selectWhere(
            array("clef0" => $_GET['idContact'], "clef1" => ConnexionUtilisateur::getLoginUtilisateurConnecte()),
            '*', array("clef0" => 'idAuteur', "clef1" => 'idDestinataire'), 'Messages', 'date');
        foreach ($recus as $recu) {
            if (!$recu->isEstVu()) {
                $recu->setEstVu(true);
                (new MessageRepository())->update($recu);
            }
        }

        Controller::afficheVue('view.php',
            ["recus" => $recus,
                "envoyes" => $envoyes,
                "pagetitle" => "Conversation",
                "contact" => (new UtilisateurRepository())->select($_GET['idContact']),
                "cheminVueBody" => "Message/discussion.php"]);
    }

    public static function create()
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            MessageFlash::ajouter('warning', 'Vous devez être connecté pour accéder à votre messagerie');
            Controller::redirect('index.php?action=connexion&controller=utilisateur');
        }
        Controller::afficheVue('view.php', [
            "cheminVueBody" => "Message/create.php",
            "pagetitle" => "Créer un message"
        ]);

    }

    public static function readKeyword()
    {
        $keyword = $_POST['keyword'];
        $utilisateurs = (new UtilisateurRepository())->selectKeyword($keyword, 'identifiant');
        Controller::afficheVue('view.php',
            ["utilisateurs" => $utilisateurs,
                "pagetitle" => "Créer un message",
                "cheminVueBody" => "Message/create.php"]);
    }

    public static function created()
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            MessageFlash::ajouter('warning', 'Vous devez être connecté pour accéder à votre messagerie');
            Controller::redirect('index.php?action=connexion&controller=utilisateur');
        }
        if (strlen($_POST['message']) > 350) {
            MessageFlash::ajouter('warning', 'La taille d\'un message est limitée à 350 caractères');
            Controller::redirect('index.php?action=readAll&controller=message');
        }
        $auteur = (new UtilisateurRepository())->select(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        $destinataire = (new UtilisateurRepository())->select($_POST['idContact']);
        $message = new Message($auteur, $destinataire, $_POST['message'], date('Y-m-d H:i:s'), false);
        (new MessageRepository())->sauvegarder($message, true);
        MessageFlash::ajouter('success', 'Message envoyé');
        Controller::redirect('index.php?action=read&controller=message&idContact=' . $_POST['idContact'] . '#dernierMessage');
    }
}
