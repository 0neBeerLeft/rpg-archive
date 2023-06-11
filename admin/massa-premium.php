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
                mysql_query("UPDATE `gebruikers` SET `premiumaccount`=`premiumaccount`+ ".mysql_real_escape_string($bedrag)." WHERE `user_id` > 0");
				$event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower" /> <a href="?page=profile&player='.$gebruiker['username'].'"> heeft je gratis Premium gegeven.';
				mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
	VALUES (NULL, NOW(), '".$_SESSION['id']."', '".$event."', '0')");
            }else{
                $melding = '<font color="red">Het aantal moet groter zijn dan 0!</font>';
            }
        }else{
            $melding = '<font color="red">Voer een getal in.</font>';
        }
    }
?>
<?php echo $melding; ?>
<form method="post">
    <label>Hoeveelheid premium doneren?</label>
    <input type="text" name="bedrag" /><br/><br/>
    <input type="submit" value="doneren" name="doneren" class="button">
</form>