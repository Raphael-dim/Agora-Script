<?php

namespace App\Vote\Model\Repository;

use App\Vote\Model\DataObject\Message;
use App\Vote\Model\DataObject\Utilisateur;

class MessageRepository extends AbstractRepository
{

    protected function getNomTable(): string
    {
        return "Messages";
    }

    protected function construire(array $objetFormatTableau): Message
    {
        $message = new Message(
            (new UtilisateurRepository())->select($objetFormatTableau['idAuteur']),
            (new UtilisateurRepository())->select($objetFormatTableau['idDestinataire']),
            $objetFormatTableau['contenu'], $objetFormatTableau['date'], $objetFormatTableau['estVu']);
        $message->setId($objetFormatTableau['idMessage']);
        return $message;
    }

    protected function getNomClePrimaire(): string
    {
        return 'idMessage';
    }

    protected function getNomsColonnes(): array
    {
        return array('idAuteur', 'idDestinataire', 'contenu', 'date', 'estVu');
    }
}
