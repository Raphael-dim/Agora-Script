<h2>Votre messagerie</h2>
<?php
$contacts = array();
foreach ($recus as $recu) {
    if (!in_array($recu->getAuteur(), $contacts)) {
        $contacts[] = $recu->getAuteur();
    }
}

foreach ($envoyes as $envoye) {
    if (!in_array($envoye->getDestinataire(), $contacts)) {
        $contacts[] = $envoye->getDestinataire();
    }
}
foreach ($contacts as $contact) {
    echo '<div class="contact">
            <img style="max-height: 40px" src="images/profil.png" alt="">
            <a class="" href="">' . $contact->getPrenom() . ' ' . $contact->getNom() . '</a>
            <a style="min-width: 100%" href="index.php?controller=message&action=read&idContact=' . $contact->getIdentifiant() . '">Ecrire un message</a>
             </div> ';
}

?>
<a style="display: block; margin-top: 100px" href="index.php?action=create&controller=message">Envoyer un message</a>

