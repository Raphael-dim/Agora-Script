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
        return new Message(
            (new UtilisateurRepository())->select($objetFormatTableau['idAuteur']),
            (new UtilisateurRepository())->select($objetFormatTableau['idDestinataire']),
            $objetFormatTableau['contenu'], $objetFormatTableau['date']);
    }

    protected function getNomClePrimaire(): string
    {
        return '';
    }

    protected function getNomsColonnes(): array
    {
        return array('idAuteur', 'idDestinataire', 'contenu', 'date');
    }
}
