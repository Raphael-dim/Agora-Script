
<?php

if ((isset($_POST["Titre"]) && isset($_POST["nbSection"])) ){
    $Titre = htmlspecialchars($_POST["Titre"]);
    $nbSection = htmlspecialchars($_POST["nbSection"]);
}
else{
    $Titre ="";
    $nbSection="";
}

?>
    <form method="post" action = "index.php?controller=question&action=create2">
        <fieldset>
            <legend>Mon formulaire :</legend>
            <p>
                <label for="titre_id">Titre</label> :
                <input type="text" placeholder="Ex : "value = "<?php echo $Titre; ?>" name="Titre" id="titre_id" required/>
            </p>
            <p>
                <label for="nbSections_id">Nombre de sections</label> :
                <input type="text" placeholder="Ex : " value = "<?php echo$nbSection; ?>"  name="nbSection" id="nbSections_id" required/>
            </p>
        </fieldset>
        <input type="submit" value="Mettre à jour"/>


