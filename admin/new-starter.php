<?php

	//Script laden zodat je nooit pagina buiten de index om kan laden
	include("includes/security.php");
	
	if($gebruiker['admin'] < 2) header("Location: index.php?page=home");
	
	#################################################################
	
	if(isset($_POST['make'])){
		
		if(empty($_POST['new-starter'])) {
			echo '<div class="red"><img src="images/icons/red.png"> Geen inlognaam ingevuld.</div>';
		}
		elseif(mysql_num_rows(mysql_query("SELECT user_id FROM gebruikers WHERE username='".$_POST['new-starter']."'")) == 0){
			echo '<div class="red"><img src="images/icons/red.png"> '.$_POST['new-starter'].' bestaat niet.</div>';
		}
		elseif(mysql_fetch_array(mysql_query("SELECT eigekregen FROM gebruikers WHERE username='".$_POST['new-starter']."'")) == 0){
			echo '<div class="blue"><img src="images/icons/blue.png"> '.$_POST['new-starter'].' Heeft nog geen starter gekozen.</div>';
		}
			
		else{
			mysql_query("UPDATE gebruikers SET eigekregen = '0' WHERE username = '".$_POST['new-starter']."'");
			
			echo '<div class="green"><img src="images/icons/green.png">Je hebt succesvol een nieuwe starter gegeven.</div>';
		}
	}
	
	if(isset($_POST['take'])){
		
		mysql_query("UPDATE gebruikers SET eigekregen = '0' WHERE username = '".$_POST['who']."'");
		
		echo '<div class="green"><img src="images/icons/green.png"> Volgende speler kan terug een starter kiezen: '.$_POST['who'].'.</div>';
	}

?>
<form method="post">
<center>
	<table width="240" style="border: 1px solid #000;">
    	<tr>
        	<td colspan="2" height="40"><center><strong>Geef iemand een starter</strong></center></td>
        </tr>
        <tr>
        	<td width="80"><strong>Inlognaam:</strong></td>
            <td width="160"><input type="text" name="new-starter" class="text_long" value="<?php echo $_GET['player']; ?>" /></td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
            <td><input type="submit" name="make" class="button" value="ei kiezen"/></td>
        </tr>
    </table>
</center>
</form>
<div style="padding-top:30px;"></div>

<center>
	<table width="240" style="border: 1px solid #000;">
    	<tr>
        	<td colspan="2" height="40"><center><strong>Lijst van mensen zonder ei</strong></center></td>
        </tr>
        <?php
        $query = mysql_query("SELECT username FROM gebruikers WHERE eigekregen = '1'");
		  for($j=$page+1; $eigekregen = mysql_fetch_array($query); $j++)
		  { 
			  echo '<form method="post"><input type="hidden" name="who" value="'.$eigekregen['username'].'" />
			  		<tr>
						<td width="120"><div style="padding-left:20px;"><img src="images/icons/user_admin.png" width="16" height="16" /> '.$eigekregen['username'].'</div></td>
						<td width="120"><input type="submit" name="Give" value="Give" class="button_mini"></td>
					</tr></form>';
		  }
		?>
    </table>
</center>
</form>