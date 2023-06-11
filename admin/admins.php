<?php

	//Script laden zodat je nooit pagina buiten de index om kan laden
	include("includes/security.php");

	//Admin controle
	if($gebruiker['admin'] < 3) header('location: index.php?page=home');

	
	#################################################################
	
	if(isset($_POST['make'])){
		
		if(empty($_POST['make-admin'])) {
			echo '<div class="red"><img src="images/icons/red.png"> Geen inlognaam ingevuld.</div>';
		}
		elseif(mysql_num_rows(mysql_query("SELECT user_id FROM gebruikers WHERE username='".$_POST['make-admin']."'")) == 0){
			echo '<div class="red"><img src="images/icons/red.png"> '.$_POST['make-admin'].' bestaat niet.</div>';
		}
		elseif(mysql_fetch_array(mysql_query("SELECT admin FROM gebruikers WHERE username='".$_POST['make-admin']."'")) == 1){
			echo '<div class="blue"><img src="images/icons/blue.png"> '.$_POST['make-admin'].' is al een administrator.</div>';
		}
			
		else{
			mysql_query("UPDATE gebruikers SET admin = '1' WHERE username = '".$_POST['make-admin']."'");
			
			echo '<div class="green"><img src="images/icons/green.png"> '.$_POST['make-admin'].' succesvol administrator gemaakt.</div>';
		}
	}
	
	if(isset($_POST['take'])){
		
		mysql_query("UPDATE gebruikers SET admin = '0' WHERE username = '".$_POST['who']."'");
		
		echo '<div class="green"><img src="images/icons/green.png"> Succesvol adminpower ontnomen van '.$_POST['who'].'.</div>';
	}

?>
<form method="post">
<center>
	<table width="240" style="border: 1px solid #000;">
    	<tr>
        	<td colspan="2" height="40"><center><strong>Make someone an Administrator</strong></center></td>
        </tr>
        <tr>
        	<td width="80"><strong>Username:</strong></td>
            <td width="160"><input type="text" name="make-admin" class="text_long" value="<?php echo $_GET['player']; ?>" /></td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
            <td><input type="submit" name="make" class="button" value="Maak admin"/></td>
        </tr>
    </table>
</center>
</form>
<div style="padding-top:30px;"></div>

<center>
	<table width="240" style="border: 1px solid #000;">
    	<tr>
        	<td colspan="2" height="40"><center><strong>List administrators lvl 1</strong></center></td>
        </tr>
        <?php
        $query = mysql_query("SELECT username FROM gebruikers WHERE admin = '1'");
		  for($j=$page+1; $admin = mysql_fetch_array($query); $j++)
		  { 
			  echo '<form method="post"><input type="hidden" name="who" value="'.$admin['username'].'" />
			  		<tr>
						<td width="120"><div style="padding-left:20px;"><img src="images/icons/user_admin.png" width="16" height="16" /> '.$admin['username'].'</div></td>
						<td width="120"><input type="submit" name="take" value="Take" class="button_mini"></td>
					</tr></form>';
		  }
		?>
		<tr>
        	<td colspan="2" height="40"><center><strong>List administrators lvl 2</strong></center></td>
        </tr>
        <?php
        $query = mysql_query("SELECT username FROM gebruikers WHERE admin = '2'");
		  for($j=$page+1; $admin = mysql_fetch_array($query); $j++)
		  { 
			  echo '<form method="post"><input type="hidden" name="who" value="'.$admin['username'].'" />
			  		<tr>
						<td width="120"><div style="padding-left:20px;"><img src="images/icons/user_admin.png" width="16" height="16" /> '.$admin['username'].'</div></td>
						<td width="120"><input type="submit" name="take" value="Take" class="button_mini"></td>
					</tr></form>';
		  }
		?>
		<tr>
        	<td colspan="2" height="40"><center><strong>List administrators lvl 3</strong></center></td>
        </tr>
        <?php
        $query = mysql_query("SELECT username FROM gebruikers WHERE admin = '3'");
		  for($j=$page+1; $admin = mysql_fetch_array($query); $j++)
		  { 
			  echo '<form method="post"><input type="hidden" name="who" value="'.$admin['username'].'" />
			  		<tr>
						<td width="120"><div style="padding-left:20px;"><img src="images/icons/user_admin.png" width="16" height="16" /> '.$admin['username'].'</div></td>
						<td width="120"><input type="submit" name="take" value="Take" class="button_mini"></td>
					</tr></form>';
		  }
		?>
    </table>
</center>
</form>