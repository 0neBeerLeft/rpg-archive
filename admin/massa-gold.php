<?php
//Admin controle
if($gebruiker['admin'] < 3) header('location: index.php?page=home');
    //Als de knop is aangeklikt
    if(isset($_POST['doneren'])){
        $bedrag = $_POST['bedrag'];
        //Kijken of er een cijfer is ingevuld
        if(ctype_digit($bedrag)){
            //Is het bedrag groter dan 0?
            if($bedrag > 0){
                $melding = '<font color="green">De donatie was succesvol!</font>';
                mysql_query("UPDATE `gebruikers` SET `gold`=`gold`+ ".mysql_real_escape_string($bedrag)." WHERE `user_id` > 0");
            }else{
                $melding = '<font color="red">Het bedrag moet groter zijn dan 0!</font>';
            }
        }else{
            $melding = '<font color="red">Voer een bedrag in.</font>';
        }
    }
	//Als de knop is aangeklikt
    if(isset($_POST['doneren2'])){
        $bedrag = $_POST['bedrag'];
        //Kijken of er een cijfer is ingevuld
        if(ctype_digit($bedrag)){
            //Is het bedrag groter dan 0?
            if($bedrag > 0){
                $melding2 = '<font color="green">De donatie was succesvol!</font>';
                mysql_query("UPDATE `gebruikers` SET `silver`=`silver`+ ".mysql_real_escape_string($bedrag)." WHERE `user_id` > 0");
            }else{
                $melding2 = '<font color="red">Het bedrag moet groter zijn dan 0!</font>';
            }
        }else{
            $melding2 = '<font color="red">Voer een bedrag in.</font>';
        }
    }
?>
<?php echo $melding; ?>
<form method="post">
    <label>Hoeveelheid goud doneren?</label>
    <input type="text" name="bedrag" /><br/><br/>
    <input type="submit" value="doneren" name="doneren" class="button">
</form>
<hr>
<?php echo $melding2; ?>
<form method="post">
    <label>Hoeveelheid silver doneren?</label>
    <input type="text" name="bedrag" /><br/><br/>
    <input type="submit" value="doneren" name="doneren2" class="button">
</form>