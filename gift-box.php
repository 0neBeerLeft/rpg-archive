<?php
exit;
  session_start();
    include_once('includes/config.php');
	include_once('includes/ingame.inc.php');
  $user = mysql_fetch_assoc(mysql_query("SELECT g.user_id, g.username, g.datum, g.email, g.ip_aangemeld, g.gender, g.ip_ingelogd, g.silver, g.gold, g.bank, g.premiumaccount, g.admin, g.respect, g.wereld, g.online, CONCAT(g.voornaam,' ',g.achternaam) AS combiname, g.land, g.`character`, g.profiel, g.teamzien, g.badgeszien, g.rank, g.wereld, g.clan, g.aantalpokemon, g.badges, g.gewonnen, g.rating, g.achievs, g.mishpat, g.verloren, COUNT(DISTINCT g.user_id) AS 'check', gi.`Badge case`																																																						 											FROM gebruikers AS g 
											INNER JOIN gebruikers_item AS gi 
											ON g.user_id = gi.user_id
											WHERE username='" .$_GET['player']."'
											AND account_code != '0'
											GROUP BY `user_id`"));
											
// DEFAULT
$gift_counter = 0;
// Counters SQL
$event2014_counter = mysql_fetch_assoc(mysql_query("SELECT * FROM `2014_gifts` WHERE `user_id`='".$user['user_id']."'"));
$gifts_counter = mysql_num_rows(mysql_query("SELECT * FROM `gifts` WHERE `reciever`='".$user['user_id']."'"));

// Counting
$gift_counter = $gift_counter+($event2014_counter['gift1']+$event2014_counter['gift2']+$event2014_counter['gift3']+$event2014_counter['gift4']+$event2014_counter['gift5']); // 2014 event
$gift_counter = $gift_counter+$gifts_counter;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-style-type" content="text/css" />
    <title>Pokemon-Sky</title>
	<link rel="stylesheet" type="text/css" href="img/gift-box/style.css" />
	<link rel="shortcut icon" href="https://www.pokemon-battle.net/img/icon.png" type="image/x-icon" />
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <script type="text/javascript" src="javascripts/tooltip.js"></script>
    <script type="text/javascript" src="javascripts/new_tooltip.js"></script>
</head>
 <body>
 
	<header><img src="https://icons.iconarchive.com/icons/svengraph/i-love/128/Box-icon.png" height="50" style="vertical-align:middle;" /> Geschenke von <?php echo $user['username']; ?> <span><?php echo $gift_counter; ?> Anzahl</span></header>
<div id="prizes">
<div id="event2014">
<?php 

$event2014sql = mysql_query("SELECT * FROM `2014_gifts` WHERE `user_id`='".$user['user_id']."'");
while(($event2014 = mysql_fetch_assoc($event2014sql))){
	if($event2014['gift1']>0){ for ($x=1; $x<=$event2014['gift1']; $x++) {
	echo '<a class="tooltip" title=\'<b><span class="gift1">מתנה רגילה</span> התקבל על ידי:</b> איוונט מיוחד לשנת 2014!\'><img src="img/newyear/normal.png"></a>';
	} }
	if($event2014['gift2']>0){ for ($x=1; $x<=$event2014['gift2']; $x++) {
	echo '<a class="tooltip" title=\'<b><span class="gift2">מתנה בינונית</span> התקבל על ידי:</b> איוונט מיוחד לשנת 2014!\'><img src="img/newyear/medium.png"></a>';
	} }
	if($event2014['gift3']>0){ for ($x=1; $x<=$event2014['gift3']; $x++) {
	echo '<a class="tooltip" title=\'<b><span class="gift3">מתנה מוזהבת</span> התקבל על ידי:</b> איוונט מיוחד לשנת 2014!\'><img src="img/newyear/gold.png"></a>';
	} }
	if($event2014['gift4']>0){ for ($x=1; $x<=$event2014['gift4']; $x++) {
	echo '<a class="tooltip" title=\'<b><span class="gift4">מתנה שחורה</span> התקבל על ידי:</b> איוונט מיוחד לשנת 2014!\'><img src="img/newyear/black2.png"></a>';
	} }
	if($event2014['gift5']>0){ for ($x=1; $x<=$event2014['gift5']; $x++) {
	echo '<a class="tooltip" title=\'<b><span class="gift5 glow">מתנה מסתורית</span> התקבל על ידי:</b> איוונט מיוחד לשנת 2014!\'><img src="img/newyear/black.png"></a>';
	} }
}

$giftsql = mysql_query("SELECT * FROM `gifts` WHERE `reciever`='".$user['user_id']."'");
while(($gift = mysql_fetch_assoc($giftsql))){
$sender = mysql_fetch_assoc(mysql_query("SELECT username FROM `gebruikers` WHERE `user_id`='".$gift['sender']."'"));
	if($gift['text']=="") {
	echo '<a class="tooltip" title=\'<b>Absender: </b>'.$sender['username'].'\'><img src="img/gift-box/'.$gift['gift'].'.png"></a>';
	} else {
	echo '<a class="tooltip" title=\'<b>Absender: </b>'.$sender['username'].'<br /><b>Text: </b>'.$gift['text'].'\'><img src="img/gift-box/'.$gift['gift'].'.png"></a>';
	}
}
 ?>
 </div>
 </div>
</body>
</html>
