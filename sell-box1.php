<?php 
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
<?php 
$select = mysql_fetch_assoc(mysql_query("SELECT pokemon_speler.id, pokemon_speler.user_id, pokemon_speler.gehecht, pokemon_speler.opzak, pokemon_speler.shiny, pokemon_speler.level, pokemon_wild.zeldzaamheid, pokemon_wild.naam, gebruikers.silver, gebruikers.premiumaccount, gebruikers.rank 
                                        FROM pokemon_speler 
                                        INNER JOIN pokemon_wild 
                                        ON pokemon_speler.wild_id = pokemon_wild.wild_id 
                                        INNER JOIN gebruikers ON 
                                        pokemon_speler.user_id = gebruikers.user_id 
                                        WHERE pokemon_speler.id='".$_GET['id']."'")); 

#Kijken hoeveel pokemon de speler op de transferlijst mag hebben 
$allowed = 1; 
if($select['premiumaccount'] >= 1) $allowed = 3;  

#zeldzaamheid opvragen 
$max_min = max_min_price($select); 
$error = False; 

if($select['user_id'] != $_SESSION['id']){ 
  #Pokemon is niet van jou,, ga naar home 
  echo '<div class="red">'.$txt['alert_not_your_pokemon'].'</div>'; 
} 
elseif($select['gehecht'] == 1){ 
    echo '<div class="red">'.$txt['alert_beginpokemon'].'</div>'; 
} 
else{ 
$count = mysql_num_rows(mysql_query("SELECT `id` FROM `transferlijst` WHERE `user_id`='".$_SESSION['id']."'")); 
#Als speler al meerdere pokemon op de markplaats heeft 
if($count < $allowed){  
  #Naam veranderen als het male of female is. 
  $pokemonnaam = pokemon_naam($select['naam'],$select['roepnaam']); 
  if($select['shiny'] == 1) $shiny = '<img src="images/icons/lidbetaald.png" alt="Shiny" title="Shiny">';  
  else $shiny = ''; 
   
  if(isset($_POST['sell'])){ 
    $buyer_sql = mysql_query("SELECT user_id FROM gebruikers WHERE username='".$_POST['buyer']."'"); 
    $error = True; 
      if($select['rank'] <= 3) 
          echo '<div class="red">'.$txt['alert_too_low_rank'].'</div>'; 
    elseif($select['user_id'] != $_SESSION['id']) 
      echo '<div class="red">'.$txt['alert_not_your_pokemon'].'</div>'; 
    elseif(empty($_POST['bedrag'])) 
      echo '<div class="red">'.$txt['alert_no_amount'].'</div>'; 
    elseif($_POST['bedrag'] < 1) 
      echo '<div class="red">'.$txt['alert_no_amount'].'</div>'; 
      elseif($_POST['bedrag'] < $max_min['minimum']) 
      echo '<div class="red">'.$txt['alert_price_too_less'].' '.$max_min['minimum_mooi'].'.</div>'; 
    elseif($_POST['bedrag'] > $max_min['maxprice']) 
      echo '<div class="red">'.$txt['alert_price_too_much'].' '.$max_min['maxprice_mooi'].'.</div>'; 
    elseif((!empty($_POST['buyer'])) && (mysql_num_rows($buyer_sql) == 0)) 
      echo '<div class="red">'.$txt['alert_user_dont_exist'].'</div>'; 
    elseif($select['opzak'] == 'tra') 
      echo '<div class="red">'.$txt['alert_pokemon_already_for_sale'].'</div>'; 
    else{ 
      $buyer = mysql_fetch_assoc($buyer_sql); 
      mysql_query("INSERT INTO `transferlijst` (`datum`, `user_id`, `to_user`, `pokemon_id`, `silver`, 'gold')  
        VALUES ('".date("Y-m-d")."', '".$_SESSION['id']."', '".$buyer['user_id']."', '".$select['id']."', '".$_POST['bedrag']."')"); 
      mysql_query("UPDATE `pokemon_speler` SET `opzak`='tra' WHERE `id`='".$select['id']."'"); 
          echo '<div class="green">'.$txt['alert_success_sell'].'</div>'; 
        } 
  } 
?> 
<form method="post" name="form"> 
<?php echo $txt['sell_box_title_text_1'].' <strong> '.$pokemonnaam.$shiny.' </strong> level <strong> '.$select['level'].'</strong> '.$txt['sell_box_title_text_2'].' 
'.$pokemonnaam.' '.$txt['sell_box_title_text_3'].' <img src="images/icons/silver.png" title="Silver" /> '.$max_min['waard_mooi'].' '.$txt['sell_box_title_text_4'].' '.$pokemonnaam.' '.$txt['sell_box_title_text_5']; ?> 

<img src="images/icons/man.png" title="Player" style="margin: 0px 0px -3px 5px;" /> <input type="text" name="buyer" class="text_long" maxlength="10"> <?php echo $txt['keep_empty']; ?>  
<img src="images/icons/silver.png" title="Silver" style="margin: 0px 0px -3px 5px;" /> <input type="text" name="bedrag" maxlength="6" onKeyPress="submitMe()" class="text_long" value="<?php if(!$error) echo $max_min['waard']; else echo $_POST['bedrag']; ?>"/><input type="submit" value="Verkoop" name="sell" class="button" style="margin-left:8px;"/> 
<img src="images/icons/gold.png" title="gold" style="margin: 0px 0px -3px 5px;" /> <input type="text" name="bedrag" maxlength="6" onKeyPress="submitMe()" class="text_long" value="gold"/><input type="submit" value="Verkoop" name="sell" class="button" style="margin-left:8px;"/> 

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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"https:p://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlnhttps:tp://www.w3.org/1999/xhtml">
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

#Kijken hoeveel pokemon de speler op de transferlijst mag hebben 
$allowed = 1; 
if($select['premiumaccount'] >= 1) $allowed = 3;  

#zeldzaamheid opvragen 
$max_min = max_min_price($select); 
$error = False; 

if($select['user_id'] != $_SESSION['id']){ 
  #Pokemon is niet van jou,, ga naar home 
  echo '<div class="red">'.$txt['alert_not_your_pokemon'].'</div>'; 
} 
elseif($select['gehecht'] == 1){ 
    echo '<div class="red">'.$txt['alert_beginpokemon'].'</div>'; 
} 
else{ 
$count = mysql_num_rows(mysql_query("SELECT `id` FROM `transferlijst` WHERE `user_id`='".$_SESSION['id']."'")); 
#Als speler al meerdere pokemon op de markplaats heeft 
if($count < $allowed){  
  #Naam veranderen als het male of female is. 
  $pokemonnaam = pokemon_naam($select['naam'],$select['roepnaam']); 
  if($select['shiny'] == 1) $shiny = '<img src="images/icons/lidbetaald.png" alt="Shiny" title="Shiny">';  
  else $shiny = ''; 
   
  if(isset($_POST['sell'])){ 
    $buyer_sql = mysql_query("SELECT user_id FROM gebruikers WHERE username='".$_POST['buyer']."'"); 
    $error = True; 
      if($select['rank'] <= 3) 
          echo '<div class="red">'.$txt['alert_too_low_rank'].'</div>'; 
    elseif($select['user_id'] != $_SESSION['id']) 
      echo '<div class="red">'.$txt['alert_not_your_pokemon'].'</div>'; 
    elseif(empty($_POST['bedrag'])) 
      echo '<div class="red">'.$txt['alert_no_amount'].'</div>'; 
    elseif($_POST['bedrag'] < 1) 
      echo '<div class="red">'.$txt['alert_no_amount'].'</div>'; 
      elseif($_POST['bedrag'] < $max_min['minimum']) 
      echo '<div class="red">'.$txt['alert_price_too_less'].' '.$max_min['minimum_mooi'].'.</div>'; 
    elseif($_POST['bedrag'] > $max_min['maxprice']) 
      echo '<div class="red">'.$txt['alert_price_too_much'].' '.$max_min['maxprice_mooi'].'.</div>'; 
    elseif((!empty($_POST['buyer'])) && (mysql_num_rows($buyer_sql) == 0)) 
      echo '<div class="red">'.$txt['alert_user_dont_exist'].'</div>'; 
    elseif($select['opzak'] == 'tra') 
      echo '<div class="red">'.$txt['alert_pokemon_already_for_sale'].'</div>'; 
    else{ 
      $buyer = mysql_fetch_assoc($buyer_sql); 
      mysql_query("INSERT INTO `transferlijst` (`datum`, `user_id`, `to_user`, `pokemon_id`, `silver`, 'gold')  
        VALUES ('".date("Y-m-d")."', '".$_SESSION['id']."', '".$buyer['user_id']."', '".$select['id']."', '".$_POST['bedrag']."')"); 
      mysql_query("UPDATE `pokemon_speler` SET `opzak`='tra' WHERE `id`='".$select['id']."'"); 
          echo '<div class="green">'.$txt['alert_success_sell'].'</div>'; 
        } 
  } 
?> 
<form method="post" name="form"> 
<?php echo $txt['sell_box_title_text_1'].' <strong> '.$pokemonnaam.$shiny.' </strong> level <strong> '.$select['level'].'</strong> '.$txt['sell_box_title_text_2'].' 
'.$pokemonnaam.' '.$txt['sell_box_title_text_3'].' <img src="images/icons/silver.png" title="Silver" /> '.$max_min['waard_mooi'].' '.$txt['sell_box_title_text_4'].' '.$pokemonnaam.' '.$txt['sell_box_title_text_5']; ?> 

<img src="images/icons/man.png" title="Player" style="margin: 0px 0px -3px 5px;" /> <input type="text" name="buyer" class="text_long" maxlength="10"> <?php echo $txt['keep_empty']; ?>  
<img src="images/icons/silver.png" title="Silver" style="margin: 0px 0px -3px 5px;" /> <input type="text" name="bedrag" maxlength="6" onKeyPress="submitMe()" class="text_long" value="<?php if(!$error) echo $max_min['waard']; else echo $_POST['bedrag']; ?>"/><input type="submit" value="Verkoop" name="sell" class="button" style="margin-left:8px;"/> 
<img src="images/icons/gold.png" title="gold" style="margin: 0px 0px -3px 5px;" /> <input type="text" name="bedrag" maxlength="6" onKeyPress="submitMe()" class="text_long" value="gold"/><input type="submit" value="Verkoop" name="sell" class="button" style="margin-left:8px;"/> 

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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//Ehttps:ttp://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmhttps:http://www.w3.org/1999/xhtml">
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

#Kijken hoeveel pokemon de speler op de transferlijst mag hebben 
$allowed = 1; 
if($select['premiumaccount'] >= 1) $allowed = 3;  

#zeldzaamheid opvragen 
$max_min = max_min_price($select); 
$error = False; 

if($select['user_id'] != $_SESSION['id']){ 
  #Pokemon is niet van jou,, ga naar home 
  echo '<div class="red">'.$txt['alert_not_your_pokemon'].'</div>'; 
} 
elseif($select['gehecht'] == 1){ 
    echo '<div class="red">'.$txt['alert_beginpokemon'].'</div>'; 
} 
else{ 
$count = mysql_num_rows(mysql_query("SELECT `id` FROM `transferlijst` WHERE `user_id`='".$_SESSION['id']."'")); 
#Als speler al meerdere pokemon op de markplaats heeft 
if($count < $allowed){  
  #Naam veranderen als het male of female is. 
  $pokemonnaam = pokemon_naam($select['naam'],$select['roepnaam']); 
  if($select['shiny'] == 1) $shiny = '<img src="images/icons/lidbetaald.png" alt="Shiny" title="Shiny">';  
  else $shiny = ''; 
   
  if(isset($_POST['sell'])){ 
    $buyer_sql = mysql_query("SELECT user_id FROM gebruikers WHERE username='".$_POST['buyer']."'"); 
    $error = True; 
      if($select['rank'] <= 3) 
          echo '<div class="red">'.$txt['alert_too_low_rank'].'</div>'; 
    elseif($select['user_id'] != $_SESSION['id']) 
      echo '<div class="red">'.$txt['alert_not_your_pokemon'].'</div>'; 
    elseif(empty($_POST['bedrag'])) 
      echo '<div class="red">'.$txt['alert_no_amount'].'</div>'; 
    elseif($_POST['bedrag'] < 1) 
      echo '<div class="red">'.$txt['alert_no_amount'].'</div>'; 
      elseif($_POST['bedrag'] < $max_min['minimum']) 
      echo '<div class="red">'.$txt['alert_price_too_less'].' '.$max_min['minimum_mooi'].'.</div>'; 
    elseif($_POST['bedrag'] > $max_min['maxprice']) 
      echo '<div class="red">'.$txt['alert_price_too_much'].' '.$max_min['maxprice_mooi'].'.</div>'; 
    elseif((!empty($_POST['buyer'])) && (mysql_num_rows($buyer_sql) == 0)) 
      echo '<div class="red">'.$txt['alert_user_dont_exist'].'</div>'; 
    elseif($select['opzak'] == 'tra') 
      echo '<div class="red">'.$txt['alert_pokemon_already_for_sale'].'</div>'; 
    else{ 
      $buyer = mysql_fetch_assoc($buyer_sql); 
      mysql_query("INSERT INTO `transferlijst` (`datum`, `user_id`, `to_user`, `pokemon_id`, `silver`, 'gold')  
        VALUES ('".date("Y-m-d")."', '".$_SESSION['id']."', '".$buyer['user_id']."', '".$select['id']."', '".$_POST['bedrag']."')"); 
      mysql_query("UPDATE `pokemon_speler` SET `opzak`='tra' WHERE `id`='".$select['id']."'"); 
          echo '<div class="green">'.$txt['alert_success_sell'].'</div>'; 
        } 
  } 
?> 
<form method="post" name="form"> 
<?php echo $txt['sell_box_title_text_1'].' <strong> '.$pokemonnaam.$shiny.' </strong> level <strong> '.$select['level'].'</strong> '.$txt['sell_box_title_text_2'].' 
'.$pokemonnaam.' '.$txt['sell_box_title_text_3'].' <img src="images/icons/silver.png" title="Silver" /> '.$max_min['waard_mooi'].' '.$txt['sell_box_title_text_4'].' '.$pokemonnaam.' '.$txt['sell_box_title_text_5']; ?> 

<img src="images/icons/man.png" title="Player" style="margin: 0px 0px -3px 5px;" /> <input type="text" name="buyer" class="text_long" maxlength="10"> <?php echo $txt['keep_empty']; ?>  
<img src="images/icons/silver.png" title="Silver" style="margin: 0px 0px -3px 5px;" /> <input type="text" name="bedrag" maxlength="6" onKeyPress="submitMe()" class="text_long" value="<?php if(!$error) echo $max_min['waard']; else echo $_POST['bedrag']; ?>"/><input type="submit" value="Verkoop" name="sell" class="button" style="margin-left:8px;"/> 
<img src="images/icons/gold.png" title="gold" style="margin: 0px 0px -3px 5px;" /> <input type="text" name="bedrag" maxlength="6" onKeyPress="submitMe()" class="text_long" value="gold"/><input type="submit" value="Verkoop" name="sell" class="button" style="margin-left:8px;"/> 

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
<html xmlns="https://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<head>
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

#Kijken hoeveel pokemon de speler op de transferlijst mag hebben 
$allowed = 1; 
if($select['premiumaccount'] >= 1) $allowed = 3;  

#zeldzaamheid opvragen 
$max_min = max_min_price($select); 
$error = False; 

if($select['user_id'] != $_SESSION['id']){ 
  #Pokemon is niet van jou,, ga naar home 
  echo '<div class="red">'.$txt['alert_not_your_pokemon'].'</div>'; 
} 
elseif($select['gehecht'] == 1){ 
    echo '<div class="red">'.$txt['alert_beginpokemon'].'</div>'; 
} 
else{ 
$count = mysql_num_rows(mysql_query("SELECT `id` FROM `transferlijst` WHERE `user_id`='".$_SESSION['id']."'")); 
#Als speler al meerdere pokemon op de markplaats heeft 
if($count < $allowed){  
  #Naam veranderen als het male of female is. 
  $pokemonnaam = pokemon_naam($select['naam'],$select['roepnaam']); 
  if($select['shiny'] == 1) $shiny = '<img src="images/icons/lidbetaald.png" alt="Shiny" title="Shiny">';  
  else $shiny = ''; 
   
  if(isset($_POST['sell'])){ 
    $buyer_sql = mysql_query("SELECT user_id FROM gebruikers WHERE username='".$_POST['buyer']."'"); 
    $error = True; 
      if($select['rank'] <= 3) 
          echo '<div class="red">'.$txt['alert_too_low_rank'].'</div>'; 
    elseif($select['user_id'] != $_SESSION['id']) 
      echo '<div class="red">'.$txt['alert_not_your_pokemon'].'</div>'; 
    elseif(empty($_POST['bedrag'])) 
      echo '<div class="red">'.$txt['alert_no_amount'].'</div>'; 
    elseif($_POST['bedrag'] < 1) 
      echo '<div class="red">'.$txt['alert_no_amount'].'</div>'; 
      elseif($_POST['bedrag'] < $max_min['minimum']) 
      echo '<div class="red">'.$txt['alert_price_too_less'].' '.$max_min['minimum_mooi'].'.</div>'; 
    elseif($_POST['bedrag'] > $max_min['maxprice']) 
      echo '<div class="red">'.$txt['alert_price_too_much'].' '.$max_min['maxprice_mooi'].'.</div>'; 
    elseif((!empty($_POST['buyer'])) && (mysql_num_rows($buyer_sql) == 0)) 
      echo '<div class="red">'.$txt['alert_user_dont_exist'].'</div>'; 
    elseif($select['opzak'] == 'tra') 
      echo '<div class="red">'.$txt['alert_pokemon_already_for_sale'].'</div>'; 
    else{ 
      $buyer = mysql_fetch_assoc($buyer_sql); 
      mysql_query("INSERT INTO `transferlijst` (`datum`, `user_id`, `to_user`, `pokemon_id`, `silver`, 'gold')  
        VALUES ('".date("Y-m-d")."', '".$_SESSION['id']."', '".$buyer['user_id']."', '".$select['id']."', '".$_POST['bedrag']."')"); 
      mysql_query("UPDATE `pokemon_speler` SET `opzak`='tra' WHERE `id`='".$select['id']."'"); 
          echo '<div class="green">'.$txt['alert_success_sell'].'</div>'; 
        } 
  } 
?> 
<form method="post" name="form"> 
<?php echo $txt['sell_box_title_text_1'].' <strong> '.$pokemonnaam.$shiny.' </strong> level <strong> '.$select['level'].'</strong> '.$txt['sell_box_title_text_2'].' 
'.$pokemonnaam.' '.$txt['sell_box_title_text_3'].' <img src="images/icons/silver.png" title="Silver" /> '.$max_min['waard_mooi'].' '.$txt['sell_box_title_text_4'].' '.$pokemonnaam.' '.$txt['sell_box_title_text_5']; ?> 

<img src="images/icons/man.png" title="Player" style="margin: 0px 0px -3px 5px;" /> <input type="text" name="buyer" class="text_long" maxlength="10"> <?php echo $txt['keep_empty']; ?>  
<img src="images/icons/silver.png" title="Silver" style="margin: 0px 0px -3px 5px;" /> <input type="text" name="bedrag" maxlength="6" onKeyPress="submitMe()" class="text_long" value="<?php if(!$error) echo $max_min['waard']; else echo $_POST['bedrag']; ?>"/><input type="submit" value="Verkoop" name="sell" class="button" style="margin-left:8px;"/> 
<img src="images/icons/gold.png" title="gold" style="margin: 0px 0px -3px 5px;" /> <input type="text" name="bedrag" maxlength="6" onKeyPress="submitMe()" class="text_long" value="gold"/><input type="submit" value="Verkoop" name="sell" class="button" style="margin-left:8px;"/> 

<?php echo $txt['sell_rules']; ?> 
</form> 
<? 
#De if afsluiten 
} 
else{ 
  echo '<div class="blue">'.$txt['alert_too_much_on_transfer_1'].' '.$allowed.' '.$txt['alert_too_much_on_transfer_2'].'</div>'; 
} 
} 
?> 
</body> 
</html> 

<?php echo $txt['sell_rules']; ?> 
</form> 
<? 
#De if afsluiten 
} 
else{ 
  echo '<div class="blue">'.$txt['alert_too_much_on_transfer_1'].' '.$allowed.' '.$txt['alert_too_much_on_transfer_2'].'</div>'; 
} 
} 
?> 
</body> 
</html> 

<?php echo $txt['sell_rules']; ?> 
</form> 
<?php 
#De if afsluiten 
} 
else{ 
  echo '<div class="blue">'.$txt['alert_too_much_on_transfer_1'].' '.$allowed.' '.$txt['alert_too_much_on_transfer_2'].'</div>'; 
} 
} 
?> 
</body> 
</html> 

<?php echo $txt['sell_rules']; ?> 
</form> 
<?php 
#De if afsluiten 
} 
else{ 
  echo '<div class="blue">'.$txt['alert_too_much_on_transfer_1'].' '.$allowed.' '.$txt['alert_too_much_on_transfer_2'].'</div>'; 
} 
} 
?> 
</body> 
</html>