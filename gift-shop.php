
<?php
exit;
$checkuser = mysql_query('SELECT user_id FROM `2014_gifts` WHERE user_id = "'.$_SESSION['id'].'"');
if (mysql_num_rows($checkuser) == 0) {
mysql_query('INSERT INTO `2014_gifts` (user_id) VALUES ("'.$_SESSION['id'].'")');
}

$newyear = mysql_fetch_assoc(mysql_query("SELECT gebruikers_item.*, gebruikers_tmhm.*
											   FROM gebruikers_item
											   INNER JOIN gebruikers_tmhm
											   ON gebruikers_item.user_id = gebruikers_tmhm.user_id
											   WHERE gebruikers_item.user_id = '".$_SESSION['id']."'"));

if(isset($_POST['give'])) {
$_POST['gift']=mysql_real_escape_string($_POST['gift']);
$_POST['player']=mysql_real_escape_string($_POST['player']);
$_POST['text']=mysql_real_escape_string($_POST['text']);
$_POST['gift']=htmlspecialchars($_POST['gift']);
$_POST['player']=htmlspecialchars($_POST['player']);
$_POST['text']=htmlspecialchars($_POST['text']);

if($_POST['gift']=="img/gift-box/arrow.png") { $gift="arrow"; }
if($_POST['gift']=="img/gift-box/candy.png") { $gift="candy"; $price="30000"; $type="silver"; $rating="200"; }
if($_POST['gift']=="img/gift-box/cake.png") { $gift="cake"; $price="80000"; $type="silver"; $rating="1000"; }
if($_POST['gift']=="img/gift-box/chocolates-cookies.png") { $gift="chocolates-cookies"; $price="45"; $type="gold"; $rating="2500"; }
if($_POST['gift']=="img/gift-box/cocktail.png") { $gift="cocktail"; $price="20"; $type="gold"; $rating="1300"; }
if($_POST['gift']=="img/gift-box/cocktail2.png") { $gift="cocktail2"; $price="30"; $type="gold"; $rating="1900"; }
if($_POST['gift']=="img/gift-box/gift-rose.png") { $gift="gift-rose"; $price="45000"; $type="silver"; $rating="1000"; }
if($_POST['gift']=="img/gift-box/heart-of-roses.png") { $gift="heart-of-roses"; $price="140000"; $type="silver"; $rating="3000"; }
if($_POST['gift']=="img/gift-box/ipod.png") { $gift="ipod"; $price="75"; $type="gold"; $rating="4000"; }
if($_POST['gift']=="img/gift-box/pizza.png") { $gift="pizza"; $price="25000"; $type="silver"; $rating="1100"; }
if($_POST['gift']=="img/gift-box/rose.png") { $gift="rose"; $price="15"; $type="gold"; $rating="500"; }
if($_POST['gift']=="img/gift-box/steak.png") { $gift="steak"; $price="65000"; $type="silver"; $rating="1800"; }
if($_POST['gift']=="img/gift-box/teddy.png") { $gift="teddy"; $price="100000"; $type="silver"; $rating="2000"; }
$checkreciever = mysql_num_rows(mysql_query("SELECT user_id FROM `gebruikers` WHERE `username`='".$_POST['player']."'"));

	if($checkreciever == 0) echo '<center><div class="red">Der angegebene Spieler existiert nicht</div></center>';
	elseif(empty($_POST['player'])) echo '<center><div class="red">Bitte gib einen Spielername ein</div></center>';
	elseif($_POST['player']==$gebruiker['username']) echo '<center><div class="red">Du kannst Geschenke nicht an Dich selbst schicken</div></center>';	
	elseif($gift=="arrow") echo '<center><div class="red">Du hast kein Geschenk ausgewählt</div></center>';
	elseif($_POST['gift']=="") echo '<center><div class="red">Du hast kein Geschenk ausgewählt</div></center>';
	elseif(strlen($_POST['text'])>50) echo '<center><div class="red">Die Nachricht darf Maximal 50 Zeichen enthalten</div></center>';
	elseif($gebruiker[$type]<$price) echo '<center><div class="red">Du hast nicht nicht genug Silber oder Gold, um ein Geschenk zu schicken</div></center>';
	elseif(($gebruiker['send_gift']==0) && ($gebruiker['premiumaccount']==0)) echo '<center><div class="red">Normale Spieler können maximal 1 Geschenk pro Tag verschicken</div></center>';
	elseif(($gebruiker['send_gift']==0) && ($gebruiker['premiumaccount']>0)) echo '<center><div class="red">Premium Mitglieder können maximal 2 Geschenke pro Tag verschicken</div></center>';
	else {
		echo '<center><div class="green">Das gekaufte Geschenk wurde erfolgreich an '.$_POST['player'].' versendet.</div></center>';
		$reciever = mysql_fetch_assoc(mysql_query("SELECT user_id FROM `gebruikers` WHERE `username`='".$_POST['player']."'"));
		mysql_query("INSERT INTO `gifts` (`id`, `gift`, `reciever`, `sender`, `text`) VALUES (NULL, '".$gift."', '".$reciever['user_id']."', '".$gebruiker['user_id']."', '".$_POST['text']."')");
		mysql_query("UPDATE `gebruikers` SET `rating`=`rating`+'".$rating."' WHERE `user_id`='".$reciever['user_id']."'");
		mysql_query("UPDATE `gebruikers` SET `send_gift`=`send_gift`-'1' WHERE `user_id`='".$gebruiker['user_id']."'");
		mysql_query("UPDATE `gebruikers` SET `".$type."`=`".$type."`-'".$price."' WHERE `user_id`='".$gebruiker['user_id']."'");
		$event = '<img src="img/gift-box/gift.png" class="imglower" width="32" /> Der Spieler <a href="?page=profile&player='.$gebruiker['username'].'" style="font-weight:bold">'.$gebruiker['username'].'</a> hat Dir <img src="images/icons/'.$type.'.png" /> '.$price.' geschickt.';
		mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen) VALUES (NULL, NOW(), '".$reciever['user_id']."', '".$event."', '0')");
			}
}
?>
<style>
#prices tr td { background:#eee;padding:5px;border-radius:5px; }
</style>
<div id="newyeartitle">
<img src="img/gift-box/teddy.png" class="img" width="24"> 
Geschenkboutique
<img src="img/gift-box/teddy.png" class="img" width="24"> 
</div>
<div id="newyear">
<img src="img/gift-box/gift.png" style="float:left;" width="200">

<b>Hallo <?php echo $gebruiker['username']; ?>!</b><br />
Und Willkommen im Geschenk-Shop!<br />
Hier kannst Du viele verschiedene Geschenke kaufen und an Deine Freunde schicken!<br />
Die Geschenke befinden sich anschließend in der Geschenk Box auf dessen Profil.
<br /><br /><b style="color:red">
Du kannst Dir nichts selbst schenken!</b><br>
<br />
<center><div class="sep" style="width:690px;"></center>
<br />
<h2>Geschenke</h2>
<center>
<table id="prices">
<tr>
	<td align="center"><img src="img/gift-box/teddy.png" width="48"><br /><img src="images/icons/silver.png" style="margin-bottom:-3px;" />100,000 Silber<br /><img src="images/icons/statistics.png" style="margin-bottom:-3px;" /><b> +2,000 Wertung</b>
</td>
	<td align="center"><img src="img/gift-box/rose.png" width="48"><br /><img src="images/icons/gold.png" style="margin-bottom:-3px;" />15 Gold<br /><img src="images/icons/statistics.png" style="margin-bottom:-3px;" /><b> +500 Wertung</b>
</td>
	<td align="center"><img src="img/gift-box/ipod.png" width="48"><br /><img src="images/icons/gold.png" style="margin-bottom:-3px;" />75 Gold<br /><img src="images/icons/statistics.png" style="margin-bottom:-3px;" /><b> +4,000 Wertung</b>
</td>
	<td align="center"><img src="img/gift-box/cake.png" width="48"><br /><img src="images/icons/silver.png" style="margin-bottom:-3px;" />80,000 Silber<br /><img src="images/icons/statistics.png" style="margin-bottom:-3px;" /><b> +1,000 Wertung</b>
</td>
	<td align="center"><img src="img/gift-box/candy.png" width="48"><br /><img src="images/icons/silver.png" style="margin-bottom:-3px;" />30,000 Silber<br /><img src="images/icons/statistics.png" style="margin-bottom:-3px;" /><b> +200 Wertung</b>
</td>
	<td align="center"><img src="img/gift-box/chocolates-cookies.png" width="48"><br /><img src="images/icons/gold.png" style="margin-bottom:-3px;" />45 Gold<br /><img src="images/icons/statistics.png" style="margin-bottom:-3px;" /><b> +2,500 Wertung</b>
</td>
</tr>
<tr>
	<td align="center"><img src="img/gift-box/cocktail.png" width="48"><br /><img src="images/icons/gold.png" style="margin-bottom:-3px;" />20 Gold<br /><img src="images/icons/statistics.png" style="margin-bottom:-3px;" /><b> +1,300 Wertung</b>
</td>
	<td align="center"><img src="img/gift-box/cocktail2.png" width="48"><br /><img src="images/icons/gold.png" style="margin-bottom:-3px;" />30 Gold<br /><img src="images/icons/statistics.png" style="margin-bottom:-3px;" /><b> +1,900 Wertung</b>
</td>
	<td align="center"><img src="img/gift-box/gift-rose.png" width="48"><br /><img src="images/icons/silver.png" style="margin-bottom:-3px;" />45,000 Silber<br /><img src="images/icons/statistics.png" style="margin-bottom:-3px;" /><b> +1,000 Wertung</b>
</td>
	<td align="center"><img src="img/gift-box/heart-of-roses.png" width="48"><br /><img src="images/icons/silver.png" style="margin-bottom:-3px;" />140,000 Silber<br /><img src="images/icons/statistics.png" style="margin-bottom:-3px;" /><b> +3,000 Wertung</b>
</td>
	<td align="center"><img src="img/gift-box/pizza.png" width="48"><br /><img src="images/icons/silver.png" style="margin-bottom:-3px;" />25,000 Silber<br /><img src="images/icons/statistics.png" style="margin-bottom:-3px;" /><b> +1,100 Wertung</b>
</td>
	<td align="center"><img src="img/gift-box/steak.png" width="48"><br /><img src="images/icons/silver.png" style="margin-bottom:-3px;" />65,000 Silber<br /><img src="images/icons/statistics.png" style="margin-bottom:-3px;" /><b> +1,800 Wertung</b>
</td>
</tr>
</table>
</center>

<center><div class="sep" style="width:690px;"></center>
<h2>Geschenk Senden</h2>
<center>

<form method="post">
<table id="prize" width="650" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="img" width="110"><img id="giftIMG" src="img/gift-box/arrow.png"><br />
		<select onchange="document.getElementById('giftIMG').src=this.options[this.selectedIndex].value;" name="gift">
<option value="img/gift-box/arrow.png">-- Bitte wählen ein Geschenk --</option>
<option value="img/gift-box/teddy.png">Teddybär</option>
<option value="img/gift-box/rose.png">Rose</option>
<option value="img/gift-box/ipod.png">iPod</option>
<option value="img/gift-box/cake.png">Kuchen</option>
<option value="img/gift-box/candy.png">Lolly</option>
<option value="img/gift-box/chocolates-cookies.png">Cookies</option>
<option value="img/gift-box/cocktail.png">Cocktail</option>
<option value="img/gift-box/cocktail2.png">Spezial Cocktail</option>
<option value="img/gift-box/gift-rose.png">Rosen Geschenk</option>
<option value="img/gift-box/heart-of-roses.png">Rosenherz</option>
<option value="img/gift-box/pizza.png">Pizza</option>
<option value="img/gift-box/steak.png">Steak</option>
</select>
		</td>
		<td class="row" width="430">
			<b style="text-decoration:underline;">Empfänger:</b> <input type="text"  class="text_long" style="width:200px;" name="player" id="player" placeholder="Spielername" /><br />
			<b style="text-decoration:underline;">Ein paar Worte oder Glückwünsche:</b><br />
			<textarea class="text_area" rows="12" name="text" style="width:380px;height:50px;line-height:25px;" placeholder="-- Maximal 50 Zeichen --"></textarea>
		</td>
		<td class="get" width="110"><input type="submit" name="give" value="Senden" class="button_prize"><input type="hidden" name="prizeid" value="2"></td>
	</tr>

</table>
</form>
<br />

</center>
</div>
<link rel="stylesheet" type="text/css" href="img/newyear/css.css" />
