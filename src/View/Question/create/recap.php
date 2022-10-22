<?php
session_start();
var_dump($_SESSION['post']);
extract($_SESSION['post']);
?>

<p>Titre <?php echo $Titre ?></p>

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
    foreach ($_SESSION['votants'] as $votant){
        echo "<p> $votant </p>";
    }
    ?>
</div>