<?php
session_start();

use App\Vote\Config\FormConfig as FormConfig;


if (isset($_POST['previous'])) {
    FormConfig::postSession();
    FormConfig::redirect("index.php?controller=question&action=form&step=5");
}


extract($_SESSION);

$_SESSION['Sections']=array();
$ct = 0;
foreach ($_SESSION as $key => $value){
    if (str_starts_with($key,"titre")){
        $_SESSION['Sections'][$ct]['titre'] = $value;
    }
    if (str_starts_with($key,"description")){
        $_SESSION['Sections'][$ct]['description'] = $value;
        $ct++;
    }

}

?>

<p>Titre <?php echo $Titre ?></p>

<div>
    <h2>Calendrier</h2>
    <p>Ecriture du <?=$debutEcriture?> au <?=$finEcriture?></p>
    <p>Votes du <?=$debutVote?> au <?=$finVote?></p>
</div>

<div>
   <h2>Auteurs</h2>
    <?php
    foreach ($_SESSION['auteurs'] as $auteur){
        echo "<p> $auteur </p>";
    }
    ?>
</div>

<div>
    <h2>Votants</h2>
<?php
foreach ($_SESSION['votants'] as $votant){
    echo "<p> $votant </p>";
}
?>
</div>



<div>
    <h2>Sections</h2>
    <?php
        foreach ($_SESSION['Sections'] as $Section){
            echo '<p>'. $Section["titre"]. ' : </p>';
            echo '<p>'. $Section["description"]. ' : </p>';
            echo '&nbsp';
        }
    ?>
</div>

<form method="post">
    <input type="submit" name=next value="Suivant"/>
    <input type="submit" name=previous value="Retour" formnovalidate/>
</form>