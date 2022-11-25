
<style>
    .blur{
        top:0;
        left:0;
        width:100%;
        height:100%;
        background:gray;
        opacity:0.5;
        position: absolute;
    }
    .over{
        position: absolute;
        top:50%;
        left:50%;
        transform: translate(-50%);
        background: #012e49;
        height:25%;
        width:50%;
    }

</style>
<div class = "blur">
</div>
<div class = "over">
        <form method = "post" action="index.php?action=delete&controller=question&idQuestion=<?php echo $id ?>">
            <p><label><?php echo $message ?></label></p>
            <Button class ="nav" type = "submit" name ="cancel" value = "Annuler">Annuler</Button>
            <Button class ="nav" type = "submit" name = "confirm" value = "Confirmer">Confirmer</Button>

        </form>
</div>
