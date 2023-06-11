<?
#Sessies aan zetten
session_start(); 

$page = 'sell-box';
#Goeie taal erbij laden voor de box
include('language/language-box.php');

#config laden
include_once('includes/config.php');
include_once('includes/ingame.inc.php');
include_once('includes/globaldefs.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns=https:://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=GLOBALDEF_SITETITLE?></title>
<link rel="stylesheet" type="text/css" href="stylesheets/box.css" />
</head>
<body>
<?
$select = mysql_fetch_assoc(mysql_query("SELECT pokemon_speler.id, pokemon_speler.user_id, pokemon_speler.gehecht, pokemon_speler.opzak, pokemon_speler.shiny, pokemon_speler.level, pokemon_wild.zeldzaamheid, pokemon_wild.naam, gebruikers.silver, gebruikers.premiumaccount, gebruikers.rank
										FROM pokemon_speler
										INNER JOIN pokemon_wild
										ON pokemon_speler.wild_id = pokemon_wild.wild_id
										INNER JOIN gebruikers ON
										pokemon_speler.user_id = gebruikers.user_id
										WHERE pokemon_speler.id='".$_GET['id']."'"));
$tempname = $_GET['name'];
$tempname2 = substr($tempname, 0, -2);
$iteminfo = mysql_fetch_assoc(mysql_query("SELECT naam, soort, silver, gold FROM markt WHERE naam='".$_GET['name']."' "));
$userinfo = mysql_fetch_assoc(mysql_query("SELECT username, user_id, rank, premiumaccount FROM gebruikers WHERE user_id='".$_SESSION['id']."' "));
if ($tempname2 == "TM")
	$checkitem = mysql_fetch_assoc(mysql_query("SELECT `".$tempname."` FROM gebruikers_tmhm WHERE user_id='".$_SESSION['id']."' "));
else
	$checkitem = mysql_fetch_assoc(mysql_query("SELECT `".$tempname."` FROM gebruikers_item WHERE user_id='".$_SESSION['id']."' "));

/*$silver = floor($_POST['silver']);
$gold = floor($_POST['gold']);
$quantity = floor($_POST['quantity']);*/

$min_silver = 0;
$max_silver = 500000;
$min_gold = 0;
$max_gold = 250;

$max_quantity = 10;

#Kijken hoeveel pokemon de speler op de transferlijst mag hebben
$allowed = 1;
if($userinfo['premiumaccount'] >= 1) $allowed = 3;

#zeldzaamheid opvragen
//$max_min = max_min_price($select);
$error = False;

/*if($select['user_id'] != $_SESSION['id']){
  #Pokemon is niet van jou,, ga naar home
  echo '<div class="red">'.$txt['alert_not_your_pokemon'].'</div>';
}
elseif($select['gehecht'] == 1){
	echo '<div class="red">'.$txt['alert_beginpokemon'].'</div>';
}*/


/*if($quantity>$checkitem['$tempname']) {
	echo '<div class="red">אין לך מספיק מהחפץ הזה.</div>';
}*/
if(empty($_SESSION['id'])) {
	echo '<div class="red">Je bent niet ingelogd.</div>';
}
else{
 $count = mysql_num_rows(mysql_query("SELECT `seller` FROM `item_market` WHERE `seller`='".$userinfo['username']."'"));
#Als speler al meerdere pokemon op de markplaats heeft
if($count < $allowed){
  #Naam veranderen als het male of female is.
 /* $pokemonnaam = pokemon_naam($select['naam'],$select['roepnaam']);
  if($select['shiny'] == 1) $shiny = '<img src="images/icons/lidbetaald.png" alt="Shiny" title="Shiny">'; 
  else $shiny = '';*/
  
  if(isset($_POST['sell'])){
  
  $silver = floor($_POST['silver']);
  $gold = floor($_POST['gold']);
  $quantity = floor($_POST['quantity']);
  
    $buyer_sql = mysql_query("SELECT user_id FROM gebruikers WHERE username='".$_POST['to_user']."'");
    $error = True;
  	if($userinfo['rank'] <= 3)
  		echo '<div class="red">'.$txt['alert_too_low_rank'].'</div>';
    /*elseif($select['user_id'] != $_SESSION['id'])
      echo '<div class="red">'.$txt['alert_not_your_pokemon'].'</div>';*/
    /*elseif(empty($_POST['silver']))
      echo '<div class="red">'.$txt['alert_no_amount'].'</div>';*/
    /*elseif($_POST['silver'] < 1 && $_POST['gold'] < 1)
      echo '<div class="red">'.$txt['alert_no_amount'].'</div>';*/
	 elseif ($quantity < 1)
	  echo '<div class="red">Je kan niet 0 items verkopen.</div>';
	 elseif ($quantity > $checkitem[$tempname])
	  echo '<div class="red">Zoveel items bezit je niet.</div>';
	 elseif($quantity > $max_quantity)
      echo '<div class="red">Het maximaal wat je kan verkopen is '.$max_quantity.' items.</div>';
     elseif($silver < $min_silver)
      echo '<div class="red">Het minimum is '.$min_silver.' Silver.</div>';
	 elseif($gold < $min_gold)
      echo '<div class="red">Het minimum is '.$min_gold.' Gold.</div>';
    elseif($silver > $max_silver)
      echo '<div class="red">Het maximum is '.$max_silver.' Silver.</div>';
	 elseif($gold > $max_gold)
      echo '<div class="red">Het maximum is '.$max_gold.' Gold.</div>';
	 elseif($tempname == "Alte Krone") 
	 echo '<div class="red">Dit item kan niet worden verkocht.</div>';
    elseif((!empty($_POST['to_user'])) && (mysql_num_rows($buyer_sql) == 0))
      echo '<div class="red">'.$txt['alert_user_dont_exist'].'</div>';
    /*elseif($select['opzak'] == 'tra')
      echo '<div class="red">'.$txt['alert_pokemon_already_for_sale'].'</div>';*/
    else{
//      $buyer = mysql_fetch_assoc($buyer_sql);
		if ($tempname2 == "TM")
			mysql_query("UPDATE `gebruikers_tmhm` SET `".$tempname."` = `".$tempname."` - '".$quantity."' WHERE `user_id` = '".$_SESSION['id']."'") or die(mysql_error());
		else
			mysql_query("UPDATE `gebruikers_item` SET `".$tempname."` = `".$tempname."` - '".$quantity."' WHERE `user_id` = '".$_SESSION['id']."'") or die(mysql_error());
		mysql_query("INSERT INTO `item_market` (`seller`, `item`, `quantity`, `silver`, `to_user`, `gold`) 
        VALUES ('".$userinfo['username']."', '".$_GET['name']."', '".$quantity."', '".$_POST['silver']."', '".$_POST['to_user']."', '".$_POST['gold']."')");
/*if ($count==0)
      mysql_query("UPDATE `gerbruikers` SET '".$userinfo['sell_item_1']."' = '".$_GET['name']."' WHERE `user_id`='".$_SESSION['id']."'");
elseif ($count==1)
      mysql_query("UPDATE `gerbruikers` SET '".$userinfo['sell_item_2']."' = '".$_GET['name']."' WHERE `user_id`='".$_SESSION['id']."'");
else
      mysql_query("UPDATE `gerbruikers` SET '".$userinfo['sell_item_3']."' = '".$_GET['name']."' WHERE `user_id`='".$_SESSION['id']."'");*/

		  echo '<div class="green">Wurde erfolgreich zum Verkauf eingestellt.</div>';
		}
  }
?>
<form method="post" name="form">
<?php
echo 'Weet je zeker dat je een '.' <strong> '.$tempname.' </strong><img src="images/items/'.$tempname.'.png" title="'.$tempname.'" /> wil verkopen?<br /><br />
<strong>'.$tempname.'</strong> <img src="images/items/'.$tempname.'.png" title="'.$tempname.'" /> voor <img src="images/icons/silver.png" title="Silver" /> '.$iteminfo['silver'].' Silver en <img src="images/icons/gold.png" title="Gold" /> '.$iteminfo['gold'].' Gold.';

?>
<br /><br />
	Aantal : <input type="text" name="quantity" class="text_long" maxlength="10"> <br />
<img src="images/icons/man.png" title="Spielername" style="margin: 0px 0px -3px 5px;" /> <input type="text" name="to_user" class="text_long" maxlength="10"> (Dit veld kan leeg gelaten worden)<br />
<img src="images/icons/silver.png" title="Silber" style="margin: 0px 0px -3px 5px;" /> <input type="text" name="silver" maxlength="6" onkeypress="onlyNumeric(arguments[0])" class="text_long" value="<?php echo $iteminfo['silver']; ?>"/>
<img src="images/icons/gold.png" title="Gold" style="margin: 0px 0px -3px 5px;" /> <input type="text" name="gold" maxlength="3" onkeypress="onlyNumeric(arguments[0])" class="text_long" value="<?php echo $iteminfo['gold']; ?>"/> (Prijs per stuk) <br /><br />
<input type="submit" value="Verkopen" name="sell" class="button" style="margin-left:8px;"/>
<br /><br />
<?php //echo $txt['sell_rules']; ?>
</form>
<?
#De if afsluiten
}
else{
  echo '<div class="blue">Du kannst keine weiteren Items verkaufen.</div>';
}
}
?>
</body>
</html>