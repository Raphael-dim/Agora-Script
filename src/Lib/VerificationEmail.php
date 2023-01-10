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
        $corpsEmail = '<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>Vérification de compte</title>
</head>
<body style="background-color: #012e49; margin-left: 15px; padding: 15px">
    <h1 style="color: white">Bienvenue sur notre plateforme de vote en ligne !</h1>
    <p style="color: white">Merci de vous être inscrit sur notre plateforme. Pour finaliser votre inscription, veuillez cliquer sur le bouton
        ci-dessous pour vérifier votre compte.</p>
    <a style="color: #3c763d" href="' . $lienValidationEmail . '">Vérifier mon compte</a>
    <p style="color: white">Si vous n\'avez pas demandé à vous inscrire sur notre plateforme, veuillez ignorer cet e-mail.</p>
    <p style="color: white">Cordialement,</p>
    <p style="color: white">L\'équipe de vote en ligne</p>
</body>
</html>';

        $transport = Transport::fromDsn('smtp://vote.IUTms@gmail.com:kilbhfnytfuxgsuu@smtp.gmail.com:587?verify_peer=0');

        $mailer = new Mailer($transport);

        $email = (new Email())
            ->from('vote.IUTms@gmail.com')
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
        if ($utilisateur->getEmail() != "") {
            return true;
        }
        return false;
    }
}
