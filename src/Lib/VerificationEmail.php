<?php

namespace App\Vote\Lib;

use App\Vote\Config\Conf;
use App\Vote\Controller\Controller;
use App\Vote\Model\DataObject\Utilisateur;
use App\Vote\Lib\MessageFlash;
use App\Vote\Model\Repository\UtilisateurRepository;

class VerificationEmail
{
    public static function envoiEmailValidation(Utilisateur $utilisateur): void
    {
        $loginURL = rawurlencode($utilisateur->getIdentifiant());
        $nonceURL = rawurlencode($utilisateur->getNonce());
        $absoluteURL = Conf::getAbsoluteURL();
        $lienValidationEmail = "$absoluteURL?action=validerEmail&controller=utilisateur&login=$loginURL&nonce=$nonceURL";
        $corpsEmail = "<a href=\"$lienValidationEmail\">Validation</a>";

// Temporairement avant d'envoyer un vrai mail
        mail($utilisateur->getEmailAValider(), 'Vérification email', $corpsEmail);
    }

    public static function traiterEmailValidation($login, $nonce): bool
    {
        if (!Utilisateur::identifiantExiste($login)) {
            MessageFlash::ajouter('warning', 'Login introuvable');
            Controller::redirect('index.php');
        }
        $utilisateur = (new UtilisateurRepository())->select($login);
        if ($utilisateur->getNonce() != $nonce) {
            MessageFlash::ajouter('warning', 'Nonce incorrect');
            Controller::redirect('index.php');
        }
        $utilisateur->setEmail($utilisateur->getEmailAValider());
        $utilisateur->setNonce("");
        (new UtilisateurRepository())->update($utilisateur);
        return true;
    }

    public static function aValideEmail(Utilisateur $utilisateur): bool
    {
// À compléter
        return true;
    }
}
