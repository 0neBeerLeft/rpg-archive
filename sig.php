<?php
exit;
use PHPImageWorkshop\ImageWorkshop as ImageWorkshop;
require_once('GD_Includes/PHPImageWorkshop/Exception/ImageWorkshopBaseException.php');
require_once('GD_Includes/PHPImageWorkshop/Exception/ImageWorkshopException.php');
require_once('GD_Includes/PHPImageWorkshop/Core/Exception/ImageWorkshopLayerException.php');
require_once('GD_Includes/PHPImageWorkshop/Core/ImageWorkshopLib.php');
require_once('GD_Includes/PHPImageWorkshop/Core/ImageWorkshopLayer.php');
require_once('GD_Includes/PHPImageWorkshop/ImageWorkshop.php');
include("includes/config.php");

$User = mysql_real_escape_string($_GET["user"]);
$query = mysql_query("SELECT gebruikers.user_id,gebruikers.username,gebruikers.rank,gebruikers.character,rank.naam FROM `gebruikers` 
					  JOIN rank ON gebruikers.rank=rank.id 
					  WHERE gebruikers.username='$User'") or die(mysql_error());
					  
if(mysql_num_rows($query) >=1) {
$row = mysql_fetch_array($query);
   //$Wins = mysql_num_rows(mysql_query('SELECT win FROM duel_logs WHERE win = "'.$row['user_id'].'"'));
   //$Lost= mysql_num_rows(mysql_query('SELECT lose FROM duel_logs WHERE lose = "'.$row['user_id'].'"'));
$gebruiker = mysql_fetch_assoc(mysql_query("SELECT g.*, gi.*, SUM(`Pokeball` + `Superball` + `Hyperball` + `Premierball` + `Netzball` + `Tauchball` + `Nestball` + `Wiederball` + `Timerball` + `Meisterball` + `Levelball` + `Freundesball` + `Turboball` + `Schwerball` + `Trank` + `Supertrank` + `Hypertrank` + `Hyperheiler` + `Beleber` + `Top Beleber` + `Pokedex` + `Pokedex chip` + `Angel` + `Forschersack` + `Fahrrad` + `Protein` + `Eisen` + `Carbon` + `Kalzium` + `KP Plus` + `Sonderbonbon` + `Finsterstein` + `Feuerstein` + `Blattstein` + `Mondstein` + `Ovaler Stein` + `Leuchtstein` + `Sonnenstein` + `Donnerstein` + `Wasserstein` + `Funkelstein` + `TM01` + `TM02` + `TM03` + `TM04` + `TM05` + `TM06` + `TM07` + `TM08` + `TM09` + `TM10` + `TM11` + `TM12` + `TM13` + `TM14` + `TM15` + `TM16` + `TM17` + `TM18` + `TM19` + `TM20` + `TM21` + `TM22` + `TM23` + `TM24` + `TM25` + `TM26` + `TM27` + `TM28` + `TM29` + `TM30` + `TM31` + `TM32` + `TM33` + `TM34` + `TM35` + `TM36` + `TM37` + `TM38` + `TM39` + `TM40` + `TM41` + `TM42` + `TM43` + `TM44` + `TM45` + `TM46` + `TM47` + `TM48` + `TM49` + `TM50` + `TM51` + `TM52` + `TM53` + `TM54` + `TM55` + `TM56` + `TM57` + `TM58` + `TM59` + `TM60` + `TM61` + `TM62` + `TM63` + `TM64` + `TM65` + `TM66` + `TM67` + `TM68` + `TM69` + `TM70` + `TM71` + `TM72` + `TM73` + `TM74` + `TM75` + `TM76` + `TM77` + `TM78` + `TM79` + `TM80` + `TM81` + `TM82` + `TM83` + `TM84` + `TM85` + `TM86` + `TM87` + `TM88` + `TM89` + `TM90` + `TM91` + `TM92` + `HM01` + `HM02` + `HM03` + `HM04` + `HM05` + `HM06` + `HM07` + `HM08`) AS items				  FROM gebruikers AS g INNER JOIN gebruikers_item AS gi 
																  ON g.user_id = gi.user_id 
																  INNER JOIN gebruikers_tmhm AS gtmhm
																  ON g.user_id = gtmhm.user_id
																   WHERE g.user_id = '".$row['user_id']."'
																  GROUP BY g.user_id"));

   $rating = number_format($gebruiker['rating']);
   $Wins = number_format($gebruiker['gewonnen']);
   $Lost = number_format($gebruiker['verloren']);
	
   
   function utf8_strrev($str){
   if (!preg_match('/^[\w\d\s.,-]*$/', $str)) {
   preg_match_all('/./us', $str, $ar);
   return join('',array_reverse($ar[0]));
   }
   else {
	return $str;
   }
} 
# User Details
mysql_query("set character_set_client='utf8'");
mysql_query("set character_set_results='utf8'");
mysql_query("set collation_connection='utf8'");

###################
$Username = $row["username"];
$level = $row["rank"];
$ranksql = mysql_fetch_assoc(mysql_query("SELECT * FROM `rank` WHERE `ranknummer`='{$level}'"));
$rankname = ' ('.$level.') '.utf8_strrev($ranksql['hebrew']);

$Base_Pic = "GD_Includes/Card.png";
$BaseLayer = ImageWorkshop::initFromPath($Base_Pic);

$Char = ImageWorkshop::initFromPath('images/you/'.$row["character"].'.png');
$Char->resizeInPixel(null, 95, true);

$text_username = utf8_strrev($Username);
$fontPath = "GD_Includes/arial.ttf";
$fontPath2 = "GD_Includes/arial.ttf";
$fontPath3 = "GD_Includes/impact.ttf";
$fontSize = 13;
$fontSize2 = 16; 
$fontSize3 = 18; 
$fontColor = "FFFFFF";
$textRotation = 0;
if($gebruiker['clan']=='') $Clan = 'Kein Clan'; else $Clan = $gebruiker['clan']; 
$tijd = time();
    	if(($gebruiker['online']+6000) > $tijd) $Status = 'Online'; else $Status = 'Offline';
		$Status = utf8_strrev($Status);

$name = ImageWorkshop::initTextLayer($text_username, $fontPath3, $fontSize3, $fontColor, $textRotation, $backgroundColor);
$text = ImageWorkshop::initTextLayer($rankname, $fontPath, $fontSize2, $fontColor, $textRotation, $backgroundColor);
$win = ImageWorkshop::initTextLayer($Wins, $fontPath2, $fontSize, $fontColor, $textRotation, $backgroundColor);
$lost = ImageWorkshop::initTextLayer($Lost, $fontPath2, $fontSize, $fontColor, $textRotation, $backgroundColor);
$clan = ImageWorkshop::initTextLayer($Clan, $fontPath2, $fontSize, $fontColor, $textRotation, $backgroundColor);
$rate = ImageWorkshop::initTextLayer($rating, $fontPath2, $fontSize, $fontColor, $textRotation, $backgroundColor);
$status = ImageWorkshop::initTextLayer($Status, $fontPath2, $fontSize, $fontColor, $textRotation, $backgroundColor);
 
$BaseLayer->addLayerOnTop($name, 50, 13, "LT");
$BaseLayer->addLayerOnTop($text, 10, 13, "RT");
$BaseLayer->addLayerOnTop($Char, 0, 0, "LM");
$BaseLayer->addLayerOnTop($win, 40, 37, "RT");
$BaseLayer->addLayerOnTop($lost, 40, 57, "RT");
$BaseLayer->addLayerOnTop($clan, 30, 75, "RT");
$BaseLayer->addLayerOnTop($rate, 220, 75, "RT");
$BaseLayer->addLayerOnTop($status, 220, 57, "RT");

//Show Result
$image = $BaseLayer->getResult();
header('Content-type: image/png');
header('Content-Disposition: filename="Card.png"');
imagepng($image, null, 8); // We choose to show a PNG (quality of 8 on a scale of 0 to 9)
exit;
}
else {
echo "Wrong Player";
}

?>
