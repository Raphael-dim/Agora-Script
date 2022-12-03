<?php

namespace App\Vote\Lib;

use App\Vote\Model\HTTP\Session;
use http\Message;

class MessageFlash
{

// Les messages sont enregistrés en session associée à la clé suivante
    private static string $cleFlash = "_messagesFlash";

// $type parmi "success", "info", "warning" ou "danger"
    public function __construct()
    {
        Session::getInstance()->enregistrer(self::$cleFlash, array('success' => array(),
            'info' => array(), 'warning' => array(), 'danger' => array()));
    }

    public static function ajouter(string $type, string $message): void
    {
        Session::getInstance()->enregistrerMsgFlash($type, $message);
    }

    public static function contientMessage(string $type): bool
    {
        return count(Session::getInstance()->lire($type)) != 0;
    }

// Attention : la lecture doit détruire le message
    public static function lireMessages(string $type): array
    {
        $message = $_SESSION[self::$cleFlash][$type];
        Session::getInstance()->supprimerMsgFlash($type);
         return $message;
    }

    public static function lireTousMessages(): array
    {
        return Session::getInstance()->lire(self::$cleFlash);
    }

}