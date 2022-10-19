<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title> Formulaire question </title>
    <link href="../../../web/css/style.css" rel="stylesheet">

</head>

<body>
<div class="titreEtnbSections">

    <form method="post">
        <fieldset>
            <legend>Mon formulaire :</legend>
            <p>
                <label for="titre_id">Titre</label> :
                <input type="text" placeholder="Ex : " value=<?php echo $_POST['Titre'] ?> name="Titre" id="titre_id" required/>
            </p>
            <p>
                <label for="nbSections_id">Nombre de sections</label> :
                <input type="text" placeholder="Ex : " value=<?php echo $_POST['nbSection'] ?>  name="nbSection" id="nbSections_id" required/>
            </p>
        </fieldset>
        <input type="submit" value="Mettre à jour"/>


    </form>
    <form method=\"get\" action='../web/frontController.php'>
        <input type='hidden' name='action' value='created'>
        <fieldset>

            <?php
            $nb = $_POST['nbSection'];
            for ($i = 1; $i <= $nb; $i++) {
                echo "<p>
            <label for='nbSections_id'>Titre de la section n°" . $i . "</plabel> :
            <input type='text' name=titre".$i." id='' required/>
            <label for='nbSections_id'>Corp du texte" . $i . "</plabel> :
            <input type='text' name=corp".$i." id='nbSections_id' required/>
            </p>";
            }

            ?>
        </fieldset>
    </form>
</div>
<input type="submit" value="Créer"/>

</body>
</html>