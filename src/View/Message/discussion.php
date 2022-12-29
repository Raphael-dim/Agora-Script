<?php

use App\Vote\Lib\ConnexionUtilisateur;
use App\Vote\Model\DataObject\Message;

function compare(Message $message1, Message $message2): int
{
    if ($message1->getDate() == $message2->getDate()) {
        return 0;
    }
    return ($message1->getDate() < $message2->getDate()) ? -1 : 1;
}

$messages = array_merge($recus, $envoyes);
usort($messages, "compare");

foreach ($messages as $message) {
    if ($message->getAuteur()->getIdentifiant() == ConnexionUtilisateur::getLoginUtilisateurConnecte()) {
        echo '<div style="margin-left: 80%" class="messageChat"> ' . $message->getContenu() . '</div>';
    } else {
        echo '<div class="messageChat"> ' . $message->getContenu() . '</div>';
    }
}
