<?php

namespace App\Vote\Controller;

use App\Vote\Lib\ConnexionUtilisateur;
use App\Vote\Model\Repository\MessageRepository;

class ControllerMessage
{
    public static function readAll()
    {
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
        $envoyes = (new MessageRepository())->selectWhere(
            array("clef0" => ConnexionUtilisateur::getLoginUtilisateurConnecte(), "clef1" => $_GET['idContact']),
            '*', array("clef0" => 'idAuteur', "clef1" => 'idDestinataire'), 'Messages', 'date');
        $recus = (new MessageRepository())->selectWhere(
            array("clef0" => $_GET['idContact'], "clef1" => ConnexionUtilisateur::getLoginUtilisateurConnecte()),
            '*', array("clef0" => 'idAuteur', "clef1" => 'idDestinataire'), 'Messages', 'date');
        Controller::afficheVue('view.php',
            ["recus" => $recus,
                "envoyes" => $envoyes,
                "pagetitle" => "Conversation",
                "cheminVueBody" => "Message/discussion.php"]);
    }

    public static function create()
    {
        Controller::afficheVue('view.php', [
            "cheminVueBody"=>"Message/create.php",
            "pagetitle" => "Créer un message"
        ]);
    }
}
