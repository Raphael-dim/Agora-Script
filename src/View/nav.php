<nav>
    <ul id="Menu" style="list-style-type: none">
        <li class="grosmenu"><a href="index.php?action=home&controller=accueil">Accueil</a></li>
        <li class="grosmenu"><a href="index.php?action=create&controller=question">Créer une question</a></li>
        <li class="grosmenu"><a href="index.php?action=readAll&controller=question">Liste des questions</a></li>


        <?php

        use App\Vote\Lib\ConnexionUtilisateur;

        if (ConnexionUtilisateur::estAdministrateur()) {
            echo '<li class="grosmenu"><a href="index.php?action=readAll&controller=utilisateur">Liste des utilisateurs</a></li>';
        }
        if (!ConnexionUtilisateur::estConnecte()) {
            echo '<li class=grosmenu><a href="index.php?action=connexion&controller=utilisateur">Connexion</a></li></ul>';
        } else {
            echo '
                   <li class=profil>
                   <a href="index.php?action=readAll&controller=message&idUtilisateur=' . ConnexionUtilisateur::getLoginUtilisateurConnecte() . '"
                    style="margin-right: 80px">
                    <img style="min-width: 40px;"  src=images/logo_lettre.png alt=messagerie></a></li>
                    <a class=profil href = "index.php?action=read&controller=utilisateur">
                                        <img src = images/profil.png alt=Profil ></a></ul> ';
        } ?>
</nav>