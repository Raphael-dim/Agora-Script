
<div class = "blur">
</div>
<div class = "over">
    <form method = "post" action="index.php?action=create&controller=vote&idproposition=<?php echo $id ?>">
        <h1><label><?php echo $message ?></label></h1>

        <?php
        require __DIR__ . "..\..\Proposition\detail.php";
        ?>
        <Button id = "bt1" class ="nav" type = "submit" name ="cancel" value = "Annuler">Annuler</Button>
        <Button id = "bt2" class ="nav" type = "submit" name = "confirm" value = "Confirmer">Confirmer</Button>

    </form>
</div>
