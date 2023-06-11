<?php
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");
if ($gebruiker['rank'] < 6) {echo'<div class="red">Du benötigst mindestens Rang 6!</div>'; } else {
?>
<center>
</center>
<style>
.tabler {
background-color:#eee;padding:5px;width:100px;height:100px;
margin-right:0px;
margin-top:25px;
}
.tder {
text-align:center;
}
</style>
<center><h2><div>Pokéball Fabrik</div></h2></br>

<?php

$getfruits = mysql_query('SELECT black,blue,green,pink,red,white,yellow FROM gebruikers_item WHERE user_id = "'.$_SESSION['id'].'"');
$fruit = mysql_fetch_assoc($getfruits);
$black = $fruit['black']; $blue = $fruit['blue']; $green = $fruit['green'];
$pink = $fruit['pink']; $red = $fruit['red']; $white = $fruit['white'];
$yellow = $fruit['yellow'];

echo "<table width='600' border='0' cellspacing='0' cellpadding='0'>
            <tr>
              <td class='top_td'><center><img src='images/items/Apricorn-box.png'> Meine Aprikokos</center></td>
            </tr>
</table>";
if ($getfruits == 0) {
echo "<table width='600' border='0' cellspacing='0' cellpadding='0'>";
echo "<td align='center' style='width: 600px; height: 50px; border: solid 1px;'><center><strong>Du besitzt keine Aprikokos</strong></center></td>";
echo "</table><hr>";
} else {
echo "<table width='600' border='0' cellspacing='0' cellpadding='0'>";
echo "<td align='center' style='width: 70px; height: 50px; border: solid 1px;'><div align='center'><img src='images/craft/Red-Apricorn.png' title='Rote Aprikoko'></br>X".$red."</div></td></td>
<td align='center' style='width: 70px; height: 50px; border: solid 1px;'><div align='center'><img src='images/craft/Yellow-Apricorn.png' title='Gelbe Aprikoko'></br>X".$yellow."</div></td>
<td align='center' style='width: 70px; height: 50px; border: solid 1px;'><div align='center'><img src='images/craft/Blue-Apricorn.png' title='Blaue Aprikoko'></br>X".$blue."</div></td>
<td align='center' style='width: 70px; height: 50px; border: solid 1px;'><div align='center'><img src='images/craft/White-Apricorn.png' title='Weiße Aprikoko'></br>X".$white."</div></td>
<td align='center' style='width: 70px; height: 50px; border: solid 1px;'><div align='center'><img src='images/craft/Black-Apricorn.png' title='Schwarze Aprikoko'></br>X".$black."</div></td>
<td align='center' style='width: 70px; height: 50px; border: solid 1px;'><div align='center'><img src='images/craft/Pink-Apricorn.png' title='Pinke Aprikoko'></br>X".$pink."</div></td>
<td align='center' style='width: 70px; height: 50px; border: solid 1px;'><div align='center'><img src='images/craft/Green-Apricorn.png' title='Grüne Aprikoko'></br>X".$green."</div></td>";
echo "</center>";
echo "</table><hr>";
}
?>

<center>
<h2><div>Herstellung</div></h2></br>
<?php
//Craft this shit!!
if ($_POST['craft']) {
$itemid = mysql_real_escape_string($_POST['craft_id']);
$info = mysql_fetch_assoc(mysql_query('SELECT * FROM craft_items WHERE id = "'.$itemid.'"'));
$blackneed = $info['black']; $blueneed = $info['blue']; $greenneed = $info['green'];
$pinkneed = $info['pink']; $redneed = $info['red']; $whiteneed = $info['white'];
$yellowneed = $info['yellow'];
if ($itemid == 1) $namen = "Levelball";
if ($itemid == 2) $namen = "Turboball";
if ($itemid == 3) $namen = "Freundesball";
if ($itemid == 4) $namen = "Schwerball";
//Get gebruiker item
$myitems = mysql_fetch_assoc(mysql_query('SELECT black,blue,green,pink,red,white,yellow FROM gebruikers_item WHERE user_id = "'.$_SESSION['id'].'"'));
if ($blackneed > $myitems['black'] || $blueeed > $myitems['blue'] || $greenneed > $myitems['green'] || 
$pinkeed > $myitems['pink'] ||  $redneed > $myitems['red'] || 
$whiteneed > $myitems['white'] ||  $yellowneed > $myitems['yellow']) { echo '<div class="red">Du hast nicht genug Aprikokos gesammelt.</div>'; } else {
$updateitem = mysql_query('UPDATE gebruikers_item SET black = black - "'.$blackneed.'", blue = blue - "'.$blueneed.'",
green = green - "'.$greenneed.'", red = red - "'.$redneed.'", pink = pink - "'.$pinkneed.'",
 white = white - "'.$whiteneed.'",  yellow = yellow - "'.$yellowneed.'" WHERE user_id = "'.$_SESSION['id'].'"');
$giveitem = mysql_query('UPDATE gebruikers_item SET `'.$namen.'` = `'.$namen.'` + 1 WHERE user_id = "'.$_SESSION['id'].'"');
if ($giveitem && $updateitem) {echo'<div class="green">Du hast folgenden Ball hergestellt: '.$info['itemname'].'!</div>';}
}
}

mysql_query("set character_set_client='utf8'");
mysql_query("set character_set_results='utf8'");
mysql_query("set collation_connection='utf8'");

//Get the items 
$getitems = mysql_query('SELECT * FROM craft_items ORDER BY id');
while ($item = mysql_fetch_assoc($getitems)) {
$black = $item['black']; $blue = $item['blue']; $green = $item['green'];
$pink = $item['pink']; $red = $item['red']; $white = $item['white'];
$yellow = $item['yellow'];
?>
<table cellspacing="0" cellpadding="0" class="craft">
<tr>
<td rowspan="3" valign="top"></td>
<td valign="top" height="30" style="text-align:center;font-weight:bold;font-size:16px;text-shadow:0 0 5px #fff;border-bottom:1px solid #fff;"><img src="images/craft/items/<?=$item['image']?>"> <?=$item['itemname']?> <img src="images/craft/items/<?=$item['image']?>"></td>
</tr>
<tr><td valign="top" height="50" style="padding:5px;"><?=$item['descr']?></td></tr>
<tr><td valign="top"><b>Benötigte Aprikoko's:<br />
<?php
if ($black) echo ''.$black.' <img src="images/craft/Black-Apricorn.png" style="vertical-align:middle;margin-bottom:3px"/>, ';
if ($blue) echo ''.$blue.' <img src="images/craft/Blue-Apricorn.png" style="vertical-align:middle;margin-bottom:3px"/>, ';
if ($green) echo ''.$green.' <img src="images/craft/Green-Apricorn.png" style="vertical-align:middle;margin-bottom:3px"/>, ';
if ($pink) echo ''.$pink.' <img src="images/craft/Pink-Apricorn.png" style="vertical-align:middle;margin-bottom:3px"/>, ';
if ($red) echo ''.$red.' <img src="images/craft/Red-Apricorn.png" style="vertical-align:middle;margin-bottom:3px"/>, ';
if ($white) echo ''.$white.' <img src="images/craft/White-Apricorn.png" style="vertical-align:middle;margin-bottom:3px"/>, ';
if ($yellow) echo ''.$yellow.' <img src="images/craft/Yellow-Apricorn.png" style="vertical-align:middle;margin-bottom:3px"/> , ';
?>
</b></td></tr>
<tr><td colspan="2"><hr/></td></tr>
<form method="post">
<tr><td colspan="2" style="text-align:center">
<input type="hidden" name="craft_id" value="<?=$item['id']?>">
<input type="submit" class="myButton" value="Los geht's" name="craft" style="padding:3px">
</td></tr>
</form>
</table>
<?php } } ?>
</center>

<style>
.craft {
width:600px;padding:0px 0 0 0;margin:5px;height:100px;border:1px solid #000; color:#fff;padding:5px;
background: #6d0019;
background: -moz-linear-gradient(top,  #6d0019 0%, #8f0222 56%, #a90329 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#6d0019), color-stop(56%,#8f0222), color-stop(100%,#a90329));
background: -webkit-linear-gradient(top,  #6d0019 0%,#8f0222 56%,#a90329 100%);
background: -o-linear-gradient(top,  #6d0019 0%,#8f0222 56%,#a90329 100%);
background: -ms-linear-gradient(top,  #6d0019 0%,#8f0222 56%,#a90329 100%);
background: linear-gradient(to bottom,  #6d0019 0%,#8f0222 56%,#a90329 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#6d0019', endColorstr='#a90329',GradientType=0 );
}
</style>
