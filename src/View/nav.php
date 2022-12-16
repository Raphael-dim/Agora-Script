<nav>
    <ul id="Menu" style="list-style-type: none">
        <li class="grosmenu"><a href="index.php?action=home&controller=accueil">Accueil</a></li>
        <li class="grosmenu"><a href="index.php?action=create&controller=question">Créer une question</a></li>
        <li class="grosmenu"><a href="index.php?action=readAll&controller=question">Liste des questions</a></li>

        <?php

        if (!isset($_SESSION['user'])) {
            echo '<li class=grosmenu><a href = index.php?action=connexion&controller=utilisateur>Connexion</a></li></ul>';
        } else {
            echo "</ul>
                  <a class=profil href='index.php?action=read&controller=utilisateur'>
                                        <img src='images/profil.png' alt='Profil'></a>";
        } ?>
</nav>