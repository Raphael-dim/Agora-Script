<?php

namespace App\Vote\Lib;
require_once '../vendor/autoload.php';

use App\Vote\Config\Conf;
use App\Vote\Controller\Controller;
use App\Vote\Model\DataObject\Utilisateur;
use App\Vote\Lib\MessageFlash;
use App\Vote\Model\Repository\UtilisateurRepository;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;


class VerificationEmail
{
    /**
     * @throws TransportExceptionInterface
     */
    public static function envoiEmailValidation(Utilisateur $utilisateur): void
    {
        $loginURL = rawurlencode($utilisateur->getIdentifiant());
        $nonceURL = rawurlencode($utilisateur->getNonce());
        $absoluteURL = Conf::getAbsoluteURL();
        $lienValidationEmail = "$absoluteURL?action=validerEmail&controller=utilisateur&login=$loginURL&nonce=$nonceURL";
        $corpsEmail = "<a href=\"$lienValidationEmail\">Validation</a>";

        $transport = Transport::fromDsn('smtp://rafi.dimeck@gmail.com:uvpffmcoklppuwoj@smtp.gmail.com:587?verify_peer=0');

        $mailer = new Mailer($transport);

        $email = (new Email())
            ->from('rafi.dimeck@gmail.com')
            ->to($utilisateur->getEmailAValider())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Lien de vérification')
            //->text($corpsEmail)
            ->html($corpsEmail);

        $mailer->send($email);
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
