<?php

use App\Vote\Lib\ConnexionUtilisateur;
use App\Vote\Model\DataObject\Calendrier;
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
echo '<div id="conversation">';
echo '';
foreach ($messages as $message) {
    $interval = (new DateTime(date("d-m-Y H:i")))->diff(new DateTime($message->getDate()));
    if (Calendrier::diff($interval) == "") {
        $diff = 'quelques secondes.';
    } else {
        $diff = Calendrier::diff($interval);
    }

    if ($message->getAuteur()->getIdentifiant() == ConnexionUtilisateur::getLoginUtilisateurConnecte()) {
        echo '<p style="margin-left: 60%" class="date" >Il y a ' . $diff . '</p>';
        echo '<div style="margin-left: 80%;" class="messageChat" > ' . $message->getContenu() . '</div>';

    } else {
        echo '<p class="date" >Il y a ' . $diff . '</p>';
        echo '<div class="messageChat"> ' . $message->getContenu() . '</div>';
    }
}
?>
</div>
<form class="custom-form" method="post" action="index.php?action=created&controller=message">
    <input type="hidden" name="idContact" value="<?= $_GET['idContact'] ?>">
    <p style="width: 70%; margin-top: 100px" class="champ">
        <label for="message_id">Message : </label>
        <textarea id="message_id" maxlength="350" name="message" rows="7" cols="50" required> </textarea>
        <label>350 caractères maximum</label>
    </p>
    <input id="suivant" type="submit" value="Envoyer" class="nav">

</form>

