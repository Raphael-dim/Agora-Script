<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title> Formulaire utilisateur </title>
</head>

<body>
<form method="get" action='../web/index.php'>
    <input type='hidden' name='action' value='created'>
    <legend>Création d'un utilisateur :</legend>
    <p>
        <label for="nom_id">Nom</label> :
        <input type="text" placeholder="Michael" name="nom" id="nom_id" required/>
    </p>
    <p>
        <label for="marque_id">Prénom</label> :
        <input type="text" placeholder="Jackson" name="prenom" id="prenom_id" required/>
    </p>
    <p>
        <input type="submit" value="Créer"/>
    </p>
</form>
</body>
</html>
