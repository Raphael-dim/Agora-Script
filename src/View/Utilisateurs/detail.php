<?php
echo '<h1>' . $Utilisateur->getNom() . '</h1>';
?>

<ul>
    <li>
        <p><a href="">Modifier le mot de passe</a></p>

    </li>
    <li>
        <p><a href="">Modifier le nom d'utilisateur</a></p>
    </li>
    <li>
        <p><a href='index.php?action=disconnected&controller=utilisateur'>Déconnexion</a></p>
    </li>
</ul>

<h2>Mes questions : </h2>
<?php foreach ($questions as $question) {
    echo '<a href = index.php?controller=question&action=read&idQuestion=' . $question->getId() . '>
    ' . $question->getTitre() . '</a>';
}
?>
<h2>Mes propositions : </h2>
<?php foreach ($propositions as $proposition) {
    echo '<a href = index.php?controller=proposition&action=read&idProposition=' . $proposition->getId() . '>
    ' . $proposition->getTitre() . '</a>';
}
?>

