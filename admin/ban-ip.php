<?php

	//Script laden zodat je nooit pagina buiten de index om kan laden
	include("includes/security.php");
	
	if($gebruiker['admin'] < 2) header("Location: index.php?page=home");
	
	#################################################################
	
	if(isset($_POST['ban'])){
		if(empty($_POST['type']))
			echo '<div class="red"><img src="images/icons/red.png"> Geen type ingevuld.</div>';
		elseif(empty($_POST['gebruiker']))
			echo '<div class="red"><img src="images/icons/red.png"> Geen gebruiker ingevuld.</div>';
		else{
			if($_POST['type'] == 'communicatie') {
				if(empty($_POST['banned'])) {
					echo '<div class="red"><img src="images/icons/red.png"> Geen communicatie ban ingevuld.</div>';
				} else {
					mysql_query("INSERT INTO ban (gebruiker, banned, type, datum)
						VALUES ('" . $_POST['gebruiker'] . "', '" . $_POST['banned'] . "', '" . $_POST['type'] . "', NOW())");
					echo '<div class="green"><img src="images/icons/green.png"> '.$_POST['banned'].' succesvol een communicatieban gegeven.</div>';
				}
			} elseif($_POST['type'] == 'chat') {
				if(empty($_POST['gebruiker'])) {
					echo '<div class="red"><img src="images/icons/red.png"> Geen chat ban ingevuld.</div>';
				} else {
					mysql_query("INSERT INTO ban (gebruiker, banned, type, datum)
						VALUES ('', '" . $_POST['gebruiker'] . "', '" . $_POST['type'] . "', NOW())");
					echo '<div class="green"><img src="images/icons/green.png"> '.$_POST['banned'].' succesvol een chatban gegeven.</div>';
				}
			} elseif($_POST['type'] == 'ipban') {
				$banner = mysql_fetch_array(mysql_query("SELECT ip_ingelogd,username FROM gebruikers WHERE username = '".$_POST['gebruiker']."'"));
				mysql_query("INSERT INTO ban (gebruiker, banned, type, datum)
						VALUES ('".$_POST['gebruiker']."', '', '".$_POST['type']."', NOW())");

				if($banner['username'] == $_POST['gebruiker']) {
					$file = '../.htaccess';
					// Open the file to get existing content
					$current = file_get_contents($file);
					// Append a new person to the file
					$current .= "#Ban ".$_POST['gebruiker']." \nDeny from " . $banner['ip_ingelogd'] . "\n";
					// Write the contents back to the file
					file_put_contents($file, $current);
				}
				echo '<div class="green"><img src="images/icons/green.png"> '.$_POST['gebruiker'].' succesvol verbannen.</div>';
			}
		}
	}
	if(isset($_POST['remove'])){
		mysql_query("DELETE FROM ban WHERE id = '".$_POST['removal']."'");
		echo '<div class="green"><img src="images/icons/green.png"> Verbanning succesvol opgeheven.</div>';
	}

?>
<form method="post" name="ban">
<center>
	<table width="100%" style="border: 1px solid #000;">
    	<tr>
        	<td colspan="2" height="40"><center><strong>Verban gebruikers</strong></center></td>
        </tr>
        <tr>
        	<td>Gebruiker:</td>
            <td><input type="text" name="gebruiker" class="text_long" value="<?php if($_GET['gebruiker'] != '') echo $_GET['gebruiker'];?>"/></td>
        </tr>
		<tr>
			<td>Communicatie ban:</td>
			<td><input type="text" name="banned" class="text_long" value=""/><br/><small>*Vul hier een gebruiker in die niet meer mag chatten met de ingevulde gebruiker.</small></td>
		</tr>
        <tr>
        	<td>Type:</td>
			<td><select name="type" class="text_select">
					<?php if($_GET['ip'] != '') {
						echo '<option value="communicatie">Communicatie</option>
					<option value="ipban" selected>IP Ban</option>
					<option value="chat">Chat Ban</option>';
					} else {
						echo '<option value="communicatie">Communicatie</option>
					<option value="ipban">IP Ban</option>
					<option value="chat">Chat Ban</option>';
					}
					?>
				</select>
			</td>
        </tr>
        <tr>
            <td colspan="2" align="center"><br/><br/><br/><input type="submit" name="ban" class="button" value="Uitvoeren" /></td>
        </tr>
    </table>
</center>
</form>

<div style="padding-top:30px;"></div>

<center>
	<table width="100%" style="border: 1px solid #000;">
    	<tr>
        	<td colspan="4" height="40"><center><strong>Verbannen gebruikers</strong></center></td>
        </tr>
        <tr>
        	<td><strong>Gebruiker:</strong></td>
        	<td><strong>Gebruiker(communicatie ban):</strong></td>
            <td><strong>Type ban:</strong></td>
            <td><strong>Actie:</strong></td>
        </tr>
        <?php
        $query = mysql_query("SELECT * FROM ban ORDER BY id DESC");
		  for($j=$page+1; $ban = mysql_fetch_array($query); $j++)
		  { 
				echo '
					<tr>
						<td>'.$ban['gebruiker'].'</td>
						<td>'.$ban['banned'].'</td>
						<td>'.$ban['type'].'</td>';
				echo '<td><form method="post"><input type="hidden" name="removal" value="'.$ban['id'].'" /><input type="submit" name="remove" class="button_mini" value="Verwijderen"></form></td>';
				echo '</tr>';
		  }
		?>
    </table>
</center>      	