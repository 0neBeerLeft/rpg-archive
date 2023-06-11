<?php
include("includes/security.php");
$page = 'clan-winkel';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

if(isset($_POST['winkel']))
?>
</table>
<br />
<center><h3>Clan Shops</h3></center>
<br /><br />
<form method="post">
<table class="lijst" width="100%">
<tr><td width="110">Price:</td><td width="16"><img src="images/icons/silver.png" /></td><td align="left">5000 silver</td></tr>
<tr><td>Shop name:</td><td width="16"><img src="images/icons/basket.png" /></td><td align="left"><input type="text" name="naam" maxlength="29"/>&nbsp;&nbsp;<input type="submit" name="submitvoorshopaanmaak" value="Create!" /></td></tr>
</table>
</form>
<?PHP
if($data->clanowner == $username)
if(isset($_POST['make'])){
	
	#clan_id aanmaken
	$query ="SELECT max(clan_id) FROM clans";
	$result = mysql_query($query) or die ("Error in query: $query. " .mysql_error());
	$row = mysql_fetch_row($result);
	$row2 = $row[0];
	$row3 = 1 + $row2;
	
	$clan_id				= $row3;
	$clan_naam				= $_POST['clan_naam'];
	$clan_owner				= $_SESSION['naam'];
	$clan_ownerid			= $_SESSION['id'];
	$clan_spelersaantal		= 1;
	$clan_members			= (string)$_SESSION['id'];
	$clan_beschrijving		= $_POST['clan_beschrijving'];
	$clan_type				= $_POST['clan_type'];
	$clan_level				= 1;
	$captcha				= $_POST['captcha'];
	$goldneed				= 100;
	$clan_gold				= 0;
	$clan_silver			= 0;
}
?>