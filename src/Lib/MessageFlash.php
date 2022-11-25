<?php

namespace App\Covoiturage\Lib;

class MessageFlash
{

// Les messages sont enregistrés en session associée à la clé suivante
    private static string $cleFlash = "_messagesFlash";

// $type parmi "success", "info", "warning" ou "danger"
    public static function ajouter(string $type, string $message): void
    {
// À compléter
    }

    public static function contientMessage(string $type): bool
    {
// À compléter
    }

// Attention : la lecture doit détruire le message
    public static function lireMessages(string $type): array
    {
// À compléter
    }

    public static function lireTousMessages(): array
    {
// À compléter
    }

}