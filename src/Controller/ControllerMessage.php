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
    /**
     * Cette fonction affiche tous les messages de l'utilisateur connecté
     */
    public static function readAll()
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            // vérifie si l'utilisateur est connecté
            MessageFlash::ajouter('warning', 'Vous devez être connecté pour accéder à votre messagerie');
            Controller::redirect('index.php?action=connexion&controller=utilisateur');
        }
        // Récupère les messages envoyés et reçus par l'utilisateur connecté
        $envoyes = (new MessageRepository())->selectWhere(ConnexionUtilisateur::getLoginUtilisateurConnecte(), '*', 'idAuteur');
        $recus = (new MessageRepository())->selectWhere(ConnexionUtilisateur::getLoginUtilisateurConnecte(), '*', 'idDestinataire');
        // Affiche la vue avec les messages récupérés
        Controller::afficheVue('view.php',
            ["recus" => $recus,
                "envoyes" => $envoyes,
                "pagetitle" => "Messagerie",
                "cheminVueBody" => "Message/list.php"]);
    }


    /**
     * Cette fonction affiche la conversation entre l'utilisateur connecté et l'utilisateur spécifié par l'identifiant dans l'URL
     */
    public static function read()
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            // vérifie si l'utilisateur est connecté
            MessageFlash::ajouter('warning', 'Vous devez être connecté pour accéder à votre messagerie');
            Controller::redirect('index.php?action=connexion&controller=utilisateur');
        }
        if (!Utilisateur::identifiantExiste($_GET['idContact'])) {
            // vérifie si l'identifiant de l'utilisateur spécifié existe
            MessageFlash::ajouter('warning', 'Cet utilisateur est introuvable');
            Controller::redirect('index.php?action=readAll&controller=message');
        }
        // Récupère les messages envoyés et reçus entre l'utilisateur connecté et l'utilisateur spécifié par l'identifiant
        $envoyes = (new MessageRepository())->selectWhere(
            array("clef0" => ConnexionUtilisateur::getLoginUtilisateurConnecte(), "clef1" => $_GET['idContact']),
            '*', array("clef0" => 'idAuteur', "clef1" => 'idDestinataire'), 'Messages', 'date');
        $recus = (new MessageRepository())->selectWhere(
            array("clef0" => $_GET['idContact'], "clef1" => ConnexionUtilisateur::getLoginUtilisateurConnecte()),
            '*', array("clef0" => 'idAuteur', "clef1" => 'idDestinataire'), 'Messages', 'date');
        foreach ($recus as $recu) {
            // Boucle pour marquer les messages reçus comme lus
            if (!$recu->isEstVu()) {
                $recu->setEstVu(true);
                (new MessageRepository())->update($recu);
            }
        }

        // Affiche la vue de la conversation avec les messages récupérés et les informations de l'utilisateur spécifié
        Controller::afficheVue('view.php',
            ["recus" => $recus,
                "envoyes" => $envoyes,
                "pagetitle" => "Conversation / " . $_GET['idContact'],
                "contact" => (new UtilisateurRepository())->select($_GET['idContact']),
                "cheminVueBody" => "Message/discussion.php"]);
    }

    /**
     * Cette fonction affiche la vue pour envoyer un message
     * Elle vérifie que l'utilisateur est connecté avant d'afficher la vue
     */
    public static function create()
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            // vérifie si l'utilisateur est connecté
            MessageFlash::ajouter('warning', 'Vous devez être connecté pour accéder à votre messagerie');
            Controller::redirect('index.php?action=connexion&controller=utilisateur');
        }
        // Affiche la vue pour envoyer un message
        Controller::afficheVue('view.php', [
            "cheminVueBody" => "Message/create.php",
            "pagetitle" => "Envoyer un message"
        ]);

    }

    /**
     * Cette fonction affiche les utilisateurs correspondant à un mot-clé donné
     * Elle est utilisée pour rechercher un utilisateur avec lequel envoyer un message
     */
    public static function readKeyword()
    {
        $keyword = $_POST['keyword'];
        // Récupère les utilisateurs correspondant au mot-clé donné
        $utilisateurs = (new UtilisateurRepository())->selectKeywordUtilisateur($keyword);
        // Affiche la vue avec les utilisateurs récupérés
        Controller::afficheVue('view.php',
            ["utilisateurs" => $utilisateurs,
                "pagetitle" => "Envoyer un message",
                "cheminVueBody" => "Message/create.php"]);
    }

    /**
     * Cette fonction permet d'enregistrer un message envoyé par l'utilisateur connecté
     * Elle est appelée lors de la validation d'un formulaire d'envoi de message
     */
    public static function created()
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            // vérifie si l'utilisateur est connecté
            MessageFlash::ajouter('warning', 'Vous devez être connecté pour accéder à votre messagerie');
            Controller::redirect('index.php?action=connexion&controller=utilisateur');
        }
        if (strlen($_POST['message']) > 350) {
            // vérifie la longueur du message
            MessageFlash::ajouter('warning', 'La taille d\'un message est limitée à 350 caractères');
            Controller::redirect('index.php?action=readAll&controller=message');
        }
        // Récupère l'auteur et le destinataire du message
        $auteur = (new UtilisateurRepository())->select(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        $destinataire = (new UtilisateurRepository())->select($_POST['idContact']);
        // Crée un nouvel objet Message
        $message = new Message($auteur, $destinataire, $_POST['message'], date('Y-m-d H:i:s'), false);
        // Sauvegarde le Message dans la base de données
        (new MessageRepository())->sauvegarder($message, true);
        MessageFlash::ajouter('success', 'Message envoyé');

        Controller::redirect('index.php?action=read&controller=message&idContact=' . $_POST['idContact'] . '#dernierMessage');
    }
}
