<h2>Votre messagerie</h2>
<?php
$contacts = array();
$nonVu = array();
foreach ($recus as $recu) {
    if (!in_array($recu->getAuteur(), $contacts)) {
        $contacts[] = $recu->getAuteur();
    }
    if (!$recu->isEstvu() && !in_array($recu->getAuteur(), $nonVu)) {
        $nonVu[] = $recu->getAuteur();
    }
}

foreach ($envoyes as $envoye) {
    if (!in_array($envoye->getDestinataire(), $contacts)) {
        $contacts[] = $envoye->getDestinataire();
    }
}
foreach ($contacts as $contact) {
    if (in_array($contact, $nonVu)) {
        $message = 'Nouveau message';
    } else {
        $message = 'Écrire un message';
    }
    echo '<div class="contact">
            <img style="max-height: 40px" src="images/profil.png" alt="">
            <a class="" href="">' . $contact->getPrenom() . ' ' . $contact->getNom() . '</a>
            <a style="min-width: 100%" href="index.php?controller=message&action=read&idContact=' . $contact->getIdentifiant() . '#dernierMessage">' . $message . '</a>
            ';
    if ($message == 'Nouveau message') {
        echo '<img style="width: 40px" src="images/nouveau-message.png" >';
    }

    echo '</div> ';
}

?>
<a style="display: block; margin-top: 100px" href="index.php?action=create&controller=message">Envoyer un message</a>

