<?
#Load language
$page = 'race';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

#Checks
if(($_GET['id'] == '') || ($_GET['code'] == '') || ($_GET['accept'] == '')) echo '<div class="red"><img src="images/icons/red.png"> Ongeldige link</div>';
elseif((!is_numeric($_GET['id'])) || (!is_numeric($_GET['code'])) || (!is_numeric($_GET['accept']))) echo '<div class="red"><img src="images/icons/red.png"> Ongeldige link</div>';
elseif(($_GET['accept'] != 0) && ($_GET['accept'] != 1)) echo '<div class="red"><img src="images/icons/red.png"> Ongeldige link</div>';
else{
	$sql = mysql_query("SELECT * From clan_invites WHERE invite_id = '".$_GET['id']."' AND code = '".$_GET['code']."'");
	$select = mysql_fetch_assoc($sql);
	$clanquery = mysql_query("SELECT * FROM clans WHERE clan_naam='".$select['invite_clannaam']."'");
	$clan = mysql_fetch_assoc($clanquery);
	$maxspelers = 10*$clan['clan_level'];
	
	
	if(mysql_num_rows($sql) == 0) echo '<div class="blue"><img src="images/icons/blue.png"> Invalide invite.</div>';
	#kijken of clan niet vol is.
	elseif($clan['clan_spelersaantal'] == $maxspelers){
		echo '<div class="red">De clan is vol</div>';
	}
	#kijken of users rank wel hoog genoeg is.
	elseif($gebruiker['rank'] < 5 and $_GET['accept'] != 0){
		echo '<div class="red">Je moet minimaal rank 5 zijn om een clan te joinen.</div>';
	}
	else{
		#Als accept 0 is
		if($_GET['accept'] == 0){
			#invite verwijderen
			mysql_query("DELETE FROM clan_invites WHERE invite_id = '".$_GET['id']."' AND code = '".$_GET['code']."'");
			
			#Bericht opstellen na wat de language van de user is
			$event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower"> Clan invite is afgewezen door '.$select['invite_usernaam'].'';
			
			#Melding geven aan de uitdager
			mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
			VALUES (NULL, NOW(), '".$clan['clan_ownerid']."', '".$event."', '0')");
			
			#Melding hier ff weergeven
			echo '<div class="green"><img src="images/icons/green.png"> Invite afgewezen</div>';
		}
		#Als geaccepteerd
		else{
			mysql_query("UPDATE gebruikers SET clan = '".$select['invite_clannaam']."' WHERE user_id = '".$_SESSION['id']."'");
			mysql_query("UPDATE clans SET clan_spelersaantal = clan_spelersaantal+1  WHERE clan_naam = '".$select['invite_clannaam']."'");
			mysql_query("UPDATE clans SET clan_members = clan_members + '".$_SESSION['id']."'  WHERE clan_naam = '".$select['invite_clannaam']."'");
			mysql_query("DELETE FROM clan_invites WHERE invite_id = '".$_GET['id']."' AND code = '".$_GET['code']."'");
			
			echo '<div class="green"><img src="images/icons/green.png"> Welkom bij '.$select['invite_clannaam'].'</div>';
			
			}
			
		}
	}

?>