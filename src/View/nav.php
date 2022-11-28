<nav>
    <ul id="Menu" style="list-style-type: none">
        <li class="grosmenu" ><a href = "index.php?action=home&controller=accueil" >Accueil</a></li>
        <li class="grosmenu" ><a href = "index.php?action=search&controller=utilisateur">Chercher un utilisateur</a></li>
        <li class="grosmenu" ><a href = "index.php?action=create&controller=question">Creer une question</a></li>
        <li class="grosmenu" ><a href = "index.php?action=readAll&controller=question">Liste des questions</a></li>
        <?php
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if(isset($_SESSION['user'])){
            echo "<li class='grosmenu'><a href = 'index.php?action=read&controller=utilisateur'>".$_SESSION['user']['id']."</a></li>";
        }else{
            echo "<li class='grosmenu' ><a href = 'index.php?action=connexion&controller=utilisateur'>Connexion</a></li>";
        } ?>

    </ul>

</nav>