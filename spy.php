<?php
	//Script laden zodat je nooit pagina buiten de index om kan laden
	include("includes/security.php");
	
	//Je moet rank 3 zijn om deze pagina te kunnen zien
	if($gebruiker['rank'] <= 2) header("Location: index.php?page=home");
	
	$page = 'spy';
	//Goeie taal erbij laden voor de page
	include_once('language/language-pages.php');
	
	if(isset($_POST['spy'])) $player = $_POST['player'];
	else $player = $_GET['player'];
	
$geheimegegevens = '<tr>
						<td width="150"><strong>'.$txt['username'].'</strong></td>
						<td width="150"><input type="text" name="player" class="text_long" value="'.$player.'" /></td>
					   </tr>
					   <tr>
						<td>&nbsp;</td>
						<td style="padding-top:2px;"><input type="submit" name="spy" value="'.$txt['button'].'" class="button" /></td>
					  </tr>
					</table>';
			
if(isset($_POST['spy'])){
	$speler = mysql_fetch_assoc(mysql_query("SELECT user_id, username, admin, wereld, silver FROM gebruikers WHERE `username`='".$_POST['player']."'"));
	
	if(empty($_POST['player']))
		$error = '<div class="red">'.$txt['alert_no_username'].'</div>';
	elseif($gebruiker['naam'] == $_POST['player'])
		$error = '<div class="red">'.$txt['alert_spy_yourself'].'</div>';
	elseif($speler['username'] == '')
    	$error = '<div class="red">'.$txt['alert_username_dont_exist'].'</div>';
	elseif($gebruiker['silver'] < 100)
		$error = '<div class="red">'.$txt['alert_not_enough_silver'].'</div>';
	elseif($speler['admin'] == 1)
    $error = '<div class="red">'.$txt['alert_admin_spy'].'</div>';
	else{
    //kans berekenen
    $kans = rand(1,4);
    //20 silver van gebruiker halen
    mysql_query("UPDATE `gebruikers` SET `silver`=`silver`-'100' WHERE `user_id` = '".$_SESSION['id']."'");
		 
		//mislukt
		if($kans == 1)
		  $error = '<div class="red">'.$txt['alert_spy_failed'].'</div>';
		//mislukt incl jail
		elseif($kans == 3){
			$tijd = rand(120,480);
			$time = date("i:s", $tijd);
			$time = explode(":", $time);
			$tijdnu = date('Y-m-d H:i:s');
			mysql_query("UPDATE `gebruikers` SET `gevangenistijd`='".$tijd."', `gevangenistijdbegin`='".$tijdnu."' WHERE `user_id`='".$_SESSION['id']."'");
  
			$error = '<div class="red">'.$txt['alert_spy_failed_jail_1'].' '.$time[0].' '.$txt['alert_spy_failed_jail_2'].' '.$time[1].' '.$txt['alert_spy_failed_jail_3'].'</div>';
		}
		//gelukt
		else{						
			$error = '<div class="green">'.$txt['success_spy'].'</div>';
													  
			$sqlpokemon	=	mysql_query("SELECT pokemon_speler.*, pokemon_wild.wild_id, pokemon_wild.naam, pokemon_wild.type1, pokemon_wild.type2
							   FROM pokemon_speler
							   INNER JOIN pokemon_wild
							   ON pokemon_speler.wild_id = pokemon_wild.wild_id
							   WHERE `user_id`='".$speler['user_id']."' AND `opzak`='ja' ORDER BY `opzak_nummer` ASC");
			
	    #Pokemons opzak weergeven op het scherm
        while($pokemonspy = mysql_fetch_assoc($sqlpokemon)){
          
          $pokemonspy = pokemonei($pokemonspy);
          $pokemonspy['naam'] = pokemon_naam($pokemonspy['naam'],$pokemonspy['roepnaam']);
          $popup = pokemon_popup($pokemonspy, $txt);
            
          $balls = $balls.'<img onMouseover="showhint(\''.$popup.'\', this)" src="'.$pokemonspy['animatie'].'" alt="'.$pokemonspy['naam'].'" width="32" height="32" style="padding: 0px 8px 5px 0px;" />';
		}
		
		$geheimegegevens = ' 	
        <tr>
          <td width="150"><strong>'.$txt['username'].'</strong></td>
          <td width="150">'.$speler['username'].'</td>
        </tr>
        <tr>
          <td><strong>'.$txt['world'].'</strong></td>
          <td>'.$speler['wereld'].'</td>
        </tr>
        <tr>
          <td><strong>'.$txt['silver_in_hand'].'</strong></td>
          <td><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> '.highamount($speler['silver']).'</td>
        </tr>
        <tr>
          <td><strong>'.$txt['team'].'</strong></td>
          <td>'.$balls.'</td>
        </tr>
      </table>';
		}		
	}
}
if(isset($_POST['spy'])) echo $error;
?>
<center>
  <p><?php echo $txt['title_text']; ?></p>
</center>
<form method="post">
  <center>
    <table width="520" cellpadding="0" cellspacing="0" border="0">
    	<tr>
      	<td width="214" height="20"></td>
      	<td width="300" colspan="2" rowspan="2" valign="top">
          <table width="300" cellpadding="0" cellspacing="0">
            <?php echo $geheimegegevens; ?>
          	<tr>
          	  <td><img src="images/teamrocket.png" /></td>
        	  </tr>
       	 	</table>
          </td>
        </tr>
    </table>
  </center>
</form>