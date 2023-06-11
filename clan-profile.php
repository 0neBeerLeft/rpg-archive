<?php
//Security laden
include('includes/security.php');

if(isset($_GET['clan']))
{
$page = 'clan-profile';
//Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

//clan leader
$clanquery = mysql_query("SELECT * FROM clans WHERE clan_naam='".$_GET['clan']."'");
$profiel = mysql_fetch_array($clanquery);
$gebruikerquery = mysql_query("SELECT clan FROM gebruikers WHERE username='".$_SESSION['naam']."'");
$gebruiker2 = mysql_fetch_array($gebruikerquery);



//there is no player completed then echo
  if(mysql_num_rows($clanquery) == 0) header('Location: index.php');
  //Anders de pagina
  else
  { 
    $plaatssql = mysql_query("SELECT `clan_naam` FROM `clans` ORDER BY `clan_level` DESC, `clan_spelersaantal` DESC, `clan_naam` ASC");
     
//Default Values
    $medaille = "";
    $star = '';

//All clans show
    //Is the clan that comes the same as the clan which looked am
    for($j=1; $plaats = mysql_fetch_assoc($plaatssql); $j++)
      if($profiel['clan_naam'] == $plaats['clan_naam']) $voortgang = $j;
    
    $voortgangplaats = $voortgang."<sup>e</sup>";

//medals etc...	
if($voortgang == '1'){
	    $medaille = "<img src='images/icons/plaatsnummereen.png'>";
	    $voortgangplaats = $voortgang."<sup>ste</sup>";
	  }
	  elseif($voortgang == '2')
	    $medaille = "<img src='images/icons/plaatsnummertwee.png'>";
	  elseif($voortgang == '3')
	    $medaille = "<img src='images/icons/plaatsnummerdrie.png'>";
	  elseif($voortgang > '3' && $voortgang <= '10')
	    $medaille = "<img src='images/icons/gold_medaille.png'>";
	  elseif($voortgang > '10' && $voortgang <= '30')
	    $medaille = "<img src='images/icons/silver_medaille.png'>";
	  elseif($voortgang > '30' && $voortgang <= '50')
	    $medaille = "<img src='images/icons/bronze_medaille.png'>";
	  elseif($voortgang =='')
		$voortgangplaats = "<b>Admin.</b>";
}
#donate gold
if(isset($_POST['donate'])){

		$clan_donate			= $_POST['clan_donate'];
		#clan user load
		$clanquery2 = mysql_query("SELECT clan FROM gebruikers WHERE username='".$_SESSION['naam']."'");
		$clan2 = mysql_fetch_array($clanquery2);
		
		#no gold
		if(empty($clan_donate)){
		$foutje1		= '<span class="error_red">*</span>';
		$alert  		= '<div class="red">Please enter a value to fill.</div>';
  	}
		#less than 0
		elseif($clan_donate < 0){
		$foutje1		= '<span class="error_red">*</span>';
		$alert 			= '<div class="red">Please fill in a value greater than 0.</div>';
	}
		#Heeft de speler wel zo veel gold contant
		elseif($gebruiker['gold'] < $clan_donate){
		$alert 			= '<div class="red">You dont have enough gold.</div>';
	}
		#Is gebruiker wel lid van clan?
		elseif($clan2['clan'] != $_GET['clan']){
		$foutje1		= '<span class="error_red">*</span>';
		$alert			= '<div class="red">You are not a member of this clan.</div>';
	}
		
		else{
		#Geldafnemen
		mysql_query("UPDATE gebruikers SET gold = gold-'".$clan_donate."' WHERE user_id = '".$_SESSION['id']."'");
		mysql_query("UPDATE clans SET clan_gold = clan_gold+'".$clan_donate."' WHERE clan_naam = '".$_GET['clan']."'");
		
		echo '<div class="green"><img src="images/icons/green.png"> Succesvol '.$_POST['clan_donate'].' gold gestord.</div>';
	}
}
#donate silver
if(isset($_POST['donatesilver'])){

		$clan_donatesilver			= $_POST['clan_donatesilver'];
		#clan gebruiker laden
		$clanquery3 = mysql_query("SELECT clan FROM gebruikers WHERE username='".$_SESSION['naam']."'");
		$clan3 = mysql_fetch_array($clanquery3);
		
		#geen gold
		if(empty($clan_donatesilver)){
		$foutje1		= '<span class="error_red">*</span>';
		$alert  		= '<div class="red">Please enter a value to fill.</div>';
  	}
		#kleiner dan 0
		elseif($clan_donatesilver < 0){
		$foutje1		= '<span class="error_red">*</span>';
		$alert 			= '<div class="red">Please fill in a value greater than 0.</div>';
	}
		#Heeft de speler wel zo veel gold contant
		elseif($gebruiker['silver'] < $clan_donatesilver){
		$alert 			= '<div class="red">You dont have enough gold.</div>';
	}
		#Is gebruiker wel lid van clan?
		elseif($clan3['clan'] != $_GET['clan']){
		$foutje1		= '<span class="error_red">*</span>';
		$alert			= '<div class="red">You are not a member of this clan.</div>';
	}
		
		else{
		#Geldafnemen
		mysql_query("UPDATE gebruikers SET silver = silver-'".$clan_donatesilver."' WHERE user_id = '".$_SESSION['id']."'");
		mysql_query("UPDATE clans SET clan_silver = clan_silver+'".$clan_donatesilver."' WHERE clan_naam = '".$_GET['clan']."'");
		
		echo '<div class="green"><img src="images/icons/green.png"> successful '.$_POST['clan_donatesilver'].' silver gestord.</div>';
	}
}
		#kick player
		if(isset($_POST['kick_member'])){
		
		#geen leader kicken
		if($profiel['clan_owner'] == $_POST['who']){
		$alert			= '<div class="red">You can not kick yourself.</div>';
		}
		else{
		mysql_query("UPDATE gebruikers SET clan = '' WHERE username = '".$_POST['who']."'");
		mysql_query("UPDATE clans SET clan_spelersaantal = clan_spelersaantal-1 WHERE clan_naam = '".$profiel['clan_naam']."'");
		
		echo '<div class="green"><img src="images/icons/green.png"> You have successfully removed: '.$_POST['who'].' from the clan.</div>';
			#bericht sturen naar speler
			$event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower"> You are kicked out of the clan '.$profiel['clan_naam'].'';
			
			#Melding geven aan de uitdager
			$kickquery = mysql_fetch_assoc(mysql_query ("SELECT user_id FROM gebruikers WHERE username='".$_POST['who']."'"));
			mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
			VALUES (NULL, NOW(), '".$kickquery['user_id']."', '".$event."', '0')");
	}
  }
		#leave
		if(isset($_POST['clan_leave'])){
		
		#geen leader
		if($profiel['clan_owner'] == $_POST['who']){
		$alert			= '<div class="red">You can not kick yourself.</div>';
		}
		else{
		mysql_query("UPDATE gebruikers SET clan = '' WHERE username = '".$_POST['who']."'");
		mysql_query("UPDATE clans SET clan_spelersaantal = clan_spelersaantal-1 WHERE clan_naam = '".$profiel['clan_naam']."'");
		echo '<div class="green"><img src="images/icons/green.png"> Successfully removed from the clan.</div>';
		
		#bericht sturen naar speler
			$event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower">'.$_SESSION['naam'].' has left the clan.';
			
			#Melding geven aan de uitdager
			$kickquery2 = mysql_fetch_assoc(mysql_query ("SELECT clan_ownerid FROM clans WHERE clan_naam='".$profiel['clan_naam']."'"));
			mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
			VALUES (NULL, NOW(), '".$kickquery2['clan_ownerid']."', '".$event."', '0')");
		
		}
  }
		
		#level up
		if(isset($_POST['level_up'])){
		#kijken welk level clan nu is.
		$clan_levelupgold = $profiel['clan_level']*100;
			#Heeft de clan wel zo veel gold contant
			if($profiel['clan_gold'] < $clan_levelupgold){
			$alert 			= '<div class="red">Your clan does not have enough gold.</div>';
			}
			else{
			#Geldafnemen
			mysql_query("UPDATE clans SET clan_gold = clan_gold-'".$clan_levelupgold."' WHERE clan_naam = '".$_GET['clan']."'");
			mysql_query("UPDATE clans SET clan_level = clan_level+1 WHERE clan_naam = '".$_GET['clan']."'");
			
			$levelnieuw = $profiel['clan_level']+1;
			echo '<div class="green"><img src="images/icons/green.png"> Congratulations your clan is now level '.$levelnieuw.'.</div>';
			}
	}

  
}
?>
<form method="post">
<center>
<?php if($alert != '') echo $alert; ?>
    <table width="600" border="0" cellspacing="0" cellpadding="0">
      <tr>
		<td width="180" rowspan="100" valign="top"><img src="images/type/<?php echo $profiel['clan_type']; ?>.png" width="150" height="150" title="<?php echo $profiel['clan_type']; ?>"/></td>
        <td width="100"><strong>Clan name:</strong></td>
        <td width="260"><? echo $profiel['clan_naam']; ?></td>
      </tr>
	  <tr>
		<td width="100"><strong>Clan owner:</strong></td>
        <td width="260"><? echo $profiel['clan_owner']; ?></a></td>
	  </tr>
	  <tr>
		<td width="100"><strong>Clan description:</strong></td>
        <td width="260"><? echo $profiel['clan_beschrijving']; ?></td>
	  </tr>
	  <tr>
	  <td width="100"><strong>&nbsp;</strong></td>
      <td width="260">&nbsp;</td>
	  </tr>
	  <tr>
        <td width="100"><strong>Position:</strong></td>
        <td width="260"><? echo $medaille; ?> <? echo $voortgangplaats; ?></td>
      </tr>
	  <tr>
		<td width="100"><strong>Clan level:</strong></td>
        <td width="260"><? echo $profiel['clan_level']; ?></td>
	  </tr>
	  </tr>
	  <?php
	  if($profiel['clan_owner'] == $_SESSION['naam']){
	  $clan_levelup ="<button type='submit' name='level_up' class='button'>Increase Clans Level</button>";
	  $clan_levelup3= $profiel['clan_level']*100;
	  $clan_levelupgold2 = "<strong>upgrade costs '".$clan_levelup3."' gold</strong>";
	  }
	  else{
	  $clan_levelup="<strong>&nbsp;</strong>";
	  $clan_levelupgold2="<strong>&nbsp;</strong>";
	  }
	  echo'
	  <td width="100">'.$clan_levelup.'</td>
      <td width="260"><sup>'.$clan_levelupgold2.'</sup></td>
	  '
	  ?>
	  </tr>
	  <tr>
        <td width="100"><strong><img src="images/icons/gold.png" title="Gold" style="margin-bottom:-3px;"> Gold:</strong></td>
        <td width="260"><strong><? echo highamount($profiel['clan_gold']); ?></strong></td>
      </tr>
	  <tr>
        <td width="100"><strong><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;"> Silver:</strong></td>
        <td width="260"><strong><? echo highamount($profiel['clan_silver']); ?></strong></td>
      </tr>
	  <td width="100"><strong>&nbsp;</strong></td>
      <td width="260">&nbsp;</td>
	  </tr>
	  <tr>
		<td width="100"><strong>Number of Members:</strong></td>
        <td width="260"><? echo $profiel['clan_spelersaantal']; ?></td>
	  </tr>
	  <tr>
        <?php
        $query = mysql_query("SELECT username FROM gebruikers WHERE clan ='".$_GET['clan']."'");
		  for($j=$page+1; $user = mysql_fetch_array($query); $j++)
		  { 
		  #clan owner in het vet
			if($profiel['clan_owner'] == $user['username']){
			$strong_l = "<strong>";
			$strong_r = "</strong>";
			}
			else{
			$strong_l = "";
			$strong_r = "";
			}
		  #clan owner kan kicken/leader maken
			if($profiel['clan_owner'] == $_SESSION['naam']){
			$clan_kick = "<button type='submit' name='kick_member' class='button_mini'>Kick</button>";
			$clan_makeleader ="<button type='submit' name='make_leader' class='button_mini'>Leader</button>";
			}
			else{
			$clan_kick = "";
			$clan_makeleader ="";
			}
		  #jezelf laten leaven
			if($user['username'] == $_SESSION['naam']){
			$clan_leave ="<button type='submit' name='clan_leave' class='button_mini'>Leave</button>";
			}
			else{
			$clan_leave ="";
			}
		  
			  echo '<form method="post"><input type="hidden" name="who" value="'.$user['username'].'" />
			  		<tr>
						<td width="120"><a href="?page=profile&player='.$user['username'].'">'.$strong_l.'<div style="padding-left:20px;"><img src="images/icons/user.png" width="16" height="16" /> '.$user['username'].'</a></div>'.$strong_r.'</td>
					    <td width="120">'.$clan_kick.''.$clan_makeleader.''.$clan_leave.'</td>
					</tr></form>';
		  }
		?>
      </tr>
</table>
</center>
</form>