<?php
//Security
include("includes/security.php");
//Admin hozzáférés
if($gebruiker['admin'] < 2) header('location: index.php?page=home');

//Pokeball addolás
    if(isset($_POST['balls'])){
        $bedrag = $_POST['bedrag'];
		$id = $_POST['id'];
        if(ctype_digit($bedrag)){
            if($bedrag > 0){
                $balls = '<div class="green"> De donatie was succesvol!</div>';
                mysql_query("UPDATE `gebruikers_item` SET `".$id."`=`".$id."`+ ".mysql_real_escape_string($bedrag)." WHERE `user_id`='".$_POST['user_id']."'");
				$event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower" /> Ajándékot kaptál:<strong> '.$bedrag.'x '.$id.'</strong>.';
				mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen) 
				VALUES (NULL, NOW(), '".$_POST['user_id']."', '".$event."', '0')");
            } else {
                $balls = '<div class="red"><img src="images/icons/red"> Het bedrag moet groter zijn dan 0!</div>';
            }
		}
		elseif($_POST['user_id'] == '') {
			echo '<div class="red"><img src="images/icons/red.png">Voer het id van een speler in!</div>';
        }
		elseif($_POST['id'] == 'none') {
			echo '<div class="red"><img src="images/icons/red.png">Selecteer een item!</div>';
		}else{
            $balls = '<div class="red"><img src="images/icons/red">Voer het aantal in.</div>';
        }
    }
	
//Felszerelés addolás
    if(isset($_POST['items'])){
        $bedrag = $_POST['bedrag'];
		$id = $_POST['id'];
        if(ctype_digit($bedrag)){
            if($bedrag > 0){
                $items = '<div class="green"> De donatie was succesvol!</div>';
                mysql_query("UPDATE `gebruikers_item` SET `".$id."`=`".$id."`+ ".mysql_real_escape_string($bedrag)." WHERE `user_id`='".$_POST['user_id']."'");
				$event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower" /> Ajándékot kaptál:<strong> '.$bedrag.'x '.$id.'</strong>.';
				mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen) 
				VALUES (NULL, NOW(), '".$_POST['user_id']."', '".$event."', '0')");
            } else {
                $items = '<div class="red"><img src="images/icons/red"> Het bedrag moet groter zijn dan 0!</div>';
            }
		}
		elseif($_POST['user_id'] == '') {
			echo '<div class="red"><img src="images/icons/red.png">Voer het id van een speler in!</div>';
        }
		elseif($_POST['id'] == 'none') {
			echo '<div class="red"><img src="images/icons/red.png">Selecteer een item!</div>';
		}else{
            $balls = '<div class="red"><img src="images/icons/red">Voer het aantal in.</div>';
        }
    }
	
//Bájitalok addolás
    if(isset($_POST['potions'])){
        $bedrag = $_POST['bedrag'];
		$id = $_POST['id'];
        if(ctype_digit($bedrag)){
            if($bedrag > 0){
                $potions = '<div class="green"> De donatie was succesvol!</div>';
                mysql_query("UPDATE `gebruikers_item` SET `".$id."`=`".$id."`+ ".mysql_real_escape_string($bedrag)." WHERE `user_id`='".$_POST['user_id']."'");
				$event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower" /> Ajándékot kaptál:<strong> '.$bedrag.'x '.$id.'</strong>.';
				mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen) 
				VALUES (NULL, NOW(), '".$_POST['user_id']."', '".$event."', '0')");
            } else {
                $potions = '<div class="red"><img src="images/icons/red"> Het bedrag moet groter zijn dan 0!</div>';
            }
		}
		elseif($_POST['user_id'] == '') {
			echo '<div class="red"><img src="images/icons/red.png">Voer het id van een speler in!</div>';
        }
		elseif($_POST['id'] == 'none') {
			echo '<div class="red"><img src="images/icons/red.png">Selecteer een item!</div>';
		}else{
            $potions = '<div class="red"><img src="images/icons/red">Voer het aantal in.</div>';
        }
    }
	
//Támadások addolás
    if(isset($_POST['attacktm'])){
        $bedrag = $_POST['bedrag'];
		$id = $_POST['id'];
        if(ctype_digit($bedrag)){
            if($bedrag > 0){
                $attacktm = '<div class="green"> De donatie was succesvol!</div>';
                mysql_query("UPDATE `gebruikers_tmhm` SET `".$id."`=`".$id."`+ ".mysql_real_escape_string($bedrag)." WHERE `user_id`='".$_POST['user_id']."'");
				$event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower" /> Ajándékot kaptál:<strong> '.$bedrag.'x '.$id.'</strong>.';
				mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen) 
				VALUES (NULL, NOW(), '".$_POST['user_id']."', '".$event."', '0')");
            } else {
                $attacktm = '<div class="red"><img src="images/icons/red"> Het bedrag moet groter zijn dan 0!</div>';
            }
		}
		elseif($_POST['user_id'] == '') {
			echo '<div class="red"><img src="images/icons/red.png">Voer het id van een speler in!</div>';
        }
		elseif($_POST['id'] == 'none') {
			echo '<div class="red"><img src="images/icons/red.png">Selecteer een item!</div>';
		}else{
            $attacktm = '<div class="red"><img src="images/icons/red">Voer het aantal in.</div>';
        }
    }
	if(isset($_POST['attackhm'])){
        $bedrag = $_POST['bedrag'];
		$id = $_POST['id'];
        if(ctype_digit($bedrag)){
            if($bedrag > 0){
                $attackhm = '<div class="green"> De donatie was succesvol!</div>';
                mysql_query("UPDATE `gebruikers_tmhm` SET `".$id."`=`".$id."`+ ".mysql_real_escape_string($bedrag)." WHERE `user_id`='".$_POST['user_id']."'");
				$event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower" /> Ajándékot kaptál:<strong> '.$bedrag.'x '.$id.'</strong>.';
				mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen) 
				VALUES (NULL, NOW(), '".$_POST['user_id']."', '".$event."', '0')");
            } else {
                $attackhm = '<div class="red"><img src="images/icons/red"> Het bedrag moet groter zijn dan 0!</div>';
            }
		}
		elseif($_POST['user_id'] == '') {
			echo '<div class="red"><img src="images/icons/red.png">Voer het id van een speler in!</div>';
        }
		elseif($_POST['id'] == 'none') {
			echo '<div class="red"><img src="images/icons/red.png">Selecteer een item!</div>';
		}else{
            $attackhm = '<div class="red"><img src="images/icons/red">Voer het aantal in.</div>';
        }
    }

$info = mysql_fetch_assoc(mysql_query("SELECT g.user_id, g.username, g.datum, g.email, g.ip_aangemeld, g.ip_ingelogd, g.silver, g.gold, g.bank, g.premiumaccount, g.admin, g.wereld, g.online, CONCAT(g.voornaam,' ',g.achternaam) AS combiname, g.land, g.`character`, g.profiel, g.teamzien, g.badgeszien, g.rank, g.wereld, g.aantalpokemon, g.badges, g.gewonnen, g.verloren, COUNT(DISTINCT g.user_id) AS 'check', gi.`Badge case`																																																						 											FROM gebruikers AS g 
											INNER JOIN gebruikers_item AS gi 
											ON g.user_id = gi.user_id
											WHERE username='" .$_GET['player']."'
											AND account_code != '0'
											GROUP BY `user_id`"));

?>
<p><center><h3>Doneer hier verschillende items</h3></center></p>
<table width="472" border="0" cellspacing="0" cellpadding="0">
            
            <tr>
              <td colspan="5" align="left" valign="top"><h3>Items</h3></td>
            </tr>
            <tr>
              <td width="5" class="top_first_td">&nbsp;</td>
              <td width="50" class="top_td">ID</td>
              <td width="90" class="top_td">Item</td>
              <td width="200" class="top_td">Aantal</td>
            </tr>
</table>
<table width="472" border="0" cellspacing="0" cellpadding="0">

</table>
<?php echo $balls; ?>
<form method="post">

<table width="350">
			<td width="200"><input type="text" name="user_id" class="text_short" value="<?php if($_GET['player'] != '') echo $_GET['player']; ?>"></td>
			<td><select name="id" class="text_select">
				<option value="none">-selecteren-</option>
				<?php 
                  $ballsql = mysql_query("SELECT id, naam FROM markt WHERE soort='balls' ORDER BY naam ASC");
                  while($ballitem = mysql_fetch_array($ballsql)){
                      echo '<option value="'.$ballitem['naam'].'">'.$ballitem['naam'].'</option>';
                  }
                ?>
			</select></td>
			<td width="150"><input type="text" name="bedrag" class="text_short"/><input type="hidden" name="ID" value="<?PHP echo $info['user_id']; ?>" /></td>
			<td><input type="submit" name="balls" value="doneren" class="button" /></td>
</table>
</form>
<?php echo $items; ?>
<form method="post">

<table width="350">
			<td width="200"><input type="text" name="user_id" class="text_short" value="<?php if($_GET['player'] != '') echo $_GET['player']; ?>"></td>
			<td><select name="id" class="text_select">
				<option value="none">-selecteren-</option>
				<?php 
                  $itemsql = mysql_query("SELECT id, naam FROM markt WHERE soort='special items' ORDER BY naam ASC");
                  while($items = mysql_fetch_array($itemsql)){
                      echo '<option value="'.$items['naam'].'">'.$items['naam'].'</option>';
                  }
                ?>
			</select></td>
			<td width="150"><input type="text" name="bedrag" class="text_short"/><input type="hidden" name="ID" value="<?PHP echo $info['user_id']; ?>" /></td>
			<td><input type="submit" name="items" value="doneren" class="button" /></td>
</table>
</form>
<?php echo $potions; ?>
<form method="post">

<table width="350">
			<td width="200"><input type="text" name="user_id" class="text_short" value="<?php if($_GET['player'] != '') echo $_GET['player']; ?>"></td>
			<td><select name="id" class="text_select">
				<option value="none">-selecteren-</option>
				<?php 
                  $potionsql = mysql_query("SELECT id, naam FROM markt WHERE soort='potions' ORDER BY naam ASC");
                  while($potions = mysql_fetch_array($potionsql)){
                      echo '<option value="'.$potions['naam'].'">'.$potions['naam'].'</option>';
                  }
                ?>
			</select></td>
			<td width="150"><input type="text" name="bedrag" class="text_short"/><input type="hidden" name="ID" value="<?PHP echo $info['user_id']; ?>" /></td>
			<td><input type="submit" name="potions" value="doneren" class="button" /></td>
</table>
</form>
<?php echo $attacktm; ?>
<form method="post">

<table width="350">
			<td width="200"><input type="text" name="user_id" class="text_short" value="<?php if($_GET['player'] != '') echo $_GET['player']; ?>"></td>
			<td><select name="id" class="text_select">
				<option value="none">-selecteren-</option>
				<?php 
                  $attacktmsql = mysql_query("SELECT id, naam FROM markt WHERE soort='tm' ORDER BY naam ASC");
                  while($tmattacks = mysql_fetch_array($attacktmsql)){
                      echo '<option value="'.$tmattacks['naam'].'">'.$tmattacks['naam'].'</option>';
                  }
                ?>
			</select></td>
			<td width="150"><input type="text" name="bedrag" class="text_short"/><input type="hidden" name="ID" value="<?PHP echo $info['user_id']; ?>" /></td>
			<td><input type="submit" name="attacktm" value="doneren" class="button" /></td>
</table>
</form>
<?php echo $attackhm; ?>
<form method="post">

<table width="350">
			<td width="200"><input type="text" name="user_id" class="text_short" value="<?php if($_GET['player'] != '') echo $_GET['player']; ?>"></td>
			<td><select name="id" class="text_select">
				<option value="none">-selecteren-</option>
				<?php 
                  $attackhmsql = mysql_query("SELECT id, naam FROM markt WHERE soort='hm' ORDER BY naam ASC");
                  while($hmattacks = mysql_fetch_array($attackhmsql)){
                      echo '<option value="'.$hmattacks['naam'].'">'.$hmattacks['naam'].'</option>';
                  }
                ?>
			</select></td>
			<td width="150"><input type="text" name="bedrag" class="text_short"/><input type="hidden" name="ID" value="<?PHP echo $info['user_id']; ?>" /></td>
			<td><input type="submit" name="attackhm" value="doneren" class="button" /></td>
</table>
</form>
