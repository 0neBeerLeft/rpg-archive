<?php
#Security laden
include('includes/security.php');

#Je moet rank 4 zijn om deze pagina te kunnen zien
if($gebruiker['rank'] < 3) header("Location: index.php?page=home");

$page = 'daycare';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');
	
$eicheck_sql = mysql_query("SELECT * FROM daycare WHERE user_id = '".$_SESSION['id']."' AND ei = '1'");
$eicheck = mysql_fetch_assoc($eicheck_sql);
#-----------------------EI

if(isset($_POST['accept'])){
	$hoeveelinhand = $gebruiker['in_hand'] + 1;
	$eiaantal = mysql_num_rows($eicheck_sql);
	
	if($eicheck['user_id'] != $_SESSION['id']) echo' <div class="red"><img src="images/icons/red.png"> '.$txt['alert_not_your_pokemon'].'</div>';
	elseif($hoeveelinhand == 7) echo'<div class="red"><img src="images/icons/red.png"> '.$txt['alert_hand_full'].'</div>';
	elseif($eiaantal == 0) echo'<div class="red"><img src="images/icons/red.png"> '.$txt['alert_no_eggs'].'</div>';
	else{
		#pokemon geven
    $query = mysql_fetch_assoc(mysql_query("SELECT `wild_id`, `naam`, `groei`, `attack_base`, `defence_base`, `speed_base`, `spc.attack_base`, `spc.defence_base`, `hp_base` FROM `pokemon_wild` WHERE `naam`='".$eicheck['naam']."' limit 1"));
    #De willekeurige pokemon in de pokemon_speler tabel zetten
    mysql_query("INSERT INTO `pokemon_speler` (`wild_id`, `aanval_1`, `aanval_2`, `aanval_3`, `aanval_4`) SELECT `wild_id`, `aanval_1`, `aanval_2`, `aanval_3`, `aanval_4` FROM `pokemon_wild` WHERE `wild_id`='".$query['wild_id']."'");
    #id opvragen van de insert hierboven
    $pokeid	= mysql_insert_id();
   	#Baby pokemon timer starten
    $tijd = date('Y-m-d H:i:s');
   
    #Karakter kiezen 
    $karakterr = mysql_fetch_assoc(mysql_query("SELECT * FROM `karakters` ORDER BY rand() limit 1"));
    $karakter = $karakterr['karakter_naam'];
    
    #Expnodig opzoeken en opslaan
    $levelpokemonnieuw = $query['level']+1;
    $groeipokemonnieuw = $query['groei'];
    $experience = mysql_fetch_assoc(mysql_query("SELECT `punten` FROM `experience` WHERE `soort`='".$groeipokemonnieuw."' AND `level`='".$levelpokemonnieuw."'"));
  
    #Pokemon IV maken en opslaan
    #Iv willekeurig getal tussen 1,31. Ik neem 2 omdat 1 te weinig is:P
    $attack_iv       = rand(2,31);
    $defence_iv      = rand(2,31);
    $speed_iv        = rand(2,31);
    $spcattack_iv    = rand(2,31);
    $spcdefence_iv   = rand(2,31);
    $hp_iv           = rand(2,31);
  
    #Stats berekenen
    $attackstat     = round(((($query['attack_base']*2+$attack_iv)*$query['level']/100)+5)*1);
    $defencestat    = round(((($query['defence_base']*2+$defence_iv)*$query['level']/100)+5)*1);
    $speedstat      = round(((($query['speed_base']*2+$speed_iv)*$query['level']/100)+5)*1);
    $spcattackstat  = round(((($query['spc.attack_base']*2+$spcattack_iv)*$query['level']/100)+5)*1);
    $spcdefencestat = round(((($query['spc.defence_base']*2+$spcdefence_iv)*$query['level']/100)+5)*1);
    #Hp bereken
    $hpstat         = round(((($query['hp_base']*2+$hp_iv)*$query['level']/100)+$query['level'])+10);
    
    #Heeft speler wel pokemon gekregen??
    if(is_numeric($pokeid)) mysql_query("UPDATE `gebruikers` SET `aantalpokemon`=`aantalpokemon`+'1' WHERE `user_id`='".$_SESSION['id']."'");
    
    #Alle gegevens van de pokemon opslaan
    mysql_query("UPDATE `pokemon_speler` SET `karakter`='".$karakter."', `expnodig`='".$experience['punten']."', `user_id`='".$_SESSION['id']."', `opzak`='ja', `opzak_nummer`='".$hoeveelinhand."', `shiny`='".$eicheck['levelup']."', `ei`='1', `ei_tijd`='".$tijd."', `attack_iv`='".$attack_iv."',`defence_iv`='".$defence_iv."', `speed_iv`='".$speed_iv."', `spc.attack_iv`='".$spcattack_iv."', `spc.defence_iv`='".$spcdefence_iv."', `hp_iv`='".$hp_iv."', `attack`='".$attackstat."', `defence`='".$defencestat."', `speed`='".$speedstat."', `spc.attack`='".$spcattackstat."', `spc.defence`='".$spcdefencestat."', `levenmax`='".$hpstat."', `leven`='".$hpstat."', `level`='5' WHERE `id`='".$pokeid."'");
    
    #Delete From Daycare
    mysql_query("DELETE FROM daycare WHERE user_id = '".$_SESSION['id']."' AND ei = '1'");
  
		echo '<div class="green">'.$txt['success_egg'].'</div>';
	}
}

elseif(isset($_POST['dontaccept'])){
	$eiaantal = mysql_num_rows($eicheck_sql);
	if($eicheck['user_id'] != $_SESSION['id']) echo' <div class="red"><img src="images/icons/red.png"> '.$txt['alert_not_your_pokemon'].'</div>';
	elseif($eiaantal == 0) echo'<div class="red"><img src="images/icons/red.png"> '.$txt['alert_no_eggs'].'</div>';
	else mysql_query("DELETE FROM daycare WHERE user_id = '".$_SESSION['id']."' AND ei = '1'");
}

elseif(mysql_num_rows($eicheck_sql) == 1)
	echo '<form method="post"><div class="blue"><img src="images/icons/blue.png"> '.$txt['egg_text'].'<br /><br /></div></form>';


#-----------------------EINDE EI

$daycaresql = mysql_query("SELECT daycare.*, pokemon_speler.wild_id, pokemon_speler.shiny FROM daycare INNER JOIN pokemon_speler ON daycare.pokemonid = pokemon_speler.id WHERE daycare.user_id = '".$_SESSION['id']."' AND daycare.ei = '0'");
$aantal = mysql_num_rows($daycaresql);

#Default
$kostenbegin = 250;

if($gebruiker['premiumaccount'] == 0) {
	$hoeveel = $txt['normal_user'];
	$toegestaan = 1;
}
else{
	$hoeveel = $txt['premium_user'];
	$toegestaan = 2;
}

#Things van pokemon wegbrengen:
if(isset($_POST['brengweg'])){
  $update = mysql_fetch_assoc(mysql_query("SELECT pokemon_wild.naam, pokemon_speler.id,pokemon_speler.user_id, pokemon_speler.opzak, pokemon_speler.level FROM pokemon_wild INNER JOIN pokemon_speler ON pokemon_wild.wild_id = pokemon_speler.wild_id WHERE id = '".$_POST['pokemonid']."'"));
	if($update['user_id'] != $_SESSION['id'])
    echo' <div class="red">'.$txt['alert_not_your_pokemon'].'</div>';
  elseif($update['opzak'] == 'day')
		echo' <div class="red">'.$txt['alert_already_in_daycare'].'</div>';
	elseif($update['level'] == 100)
		echo' <div class="red">'.$txt['alert_already_lvl_100'].'</div>';
	elseif($aantal >= $toegestaan)
		echo' <div class="red">'.$txt['alert_daycare_full'].'</div>';
	else{
    mysql_data_seek($pokemon_sql, 0);
    $i = 0;
    while($pokemon = mysql_fetch_assoc($pokemon_sql)){
      if($pokemon['id'] == $_POST['pokemonid']){
    		mysql_query("UPDATE pokemon_speler SET `opzak`='day', `opzak_nummer`='' WHERE id = '".$_POST['pokemonid']."'");
    		mysql_query("INSERT INTO daycare (pokemonid, user_id, naam, level,vanaf_datum)
    		  VALUES ('".$update['id']."', '".$_SESSION['id']."', '".$update['naam']."', '".$update['level']."',NOW())");
    	}
    	else{
        $i++;
        mysql_query("UPDATE `pokemon_speler` SET `opzak_nummer`='".$i."' WHERE `id`='".$pokemon['id']."'");
      }
		}
		echo' <div class="green">'.$txt['success_bring'].'</div>';
	}
}

#Things van pokemon ophalen:
if(isset($_POST['haalop'])){
  $select = mysql_fetch_assoc(mysql_query("SELECT * FROM `daycare` WHERE `pokemonid`='".$_POST['pokemonid']."'"));
  $level = $select['level'] + $select['levelup'];
  $kostenlevelup = $select['levelup'] * 500;
  $kosten = $kostenbegin + $kostenlevelup;
	$hoeveelinhand = $gebruiker['in_hand'] + 1;
	if($_SESSION['id'] != $select['user_id'])
    echo'<div class="red">'.$txt['alert_not_your_pokemon'].'</div>';
	elseif($hoeveelinhand == 7)
		echo'<div class="red">'.$txt['alert_hand_full'].'</div>';
	elseif($kosten > $gebruiker['silver'])
		echo'<div class="red">'.$txt['alert_not_enough_silver'].'</div>';
	else{
  	mysql_query("UPDATE pokemon_speler SET `opzak`='ja', `opzak_nummer`='".$hoeveelinhand."' WHERE id = '".$_POST['pokemonid']."'");
  	mysql_query("DELETE FROM daycare WHERE pokemonid = '".$_POST['pokemonid']."'");
  	mysql_query("UPDATE `gebruikers` SET `silver`=`silver`-'".$kosten."' WHERE `user_id`='".$_SESSION['id']."'");
  	
    $_SESSION['used'] = Array();    
    $count = 0;
  	
    for($i=1; $i<=$select['levelup']; $i++){
      if($count == 0) $_SESSION['lvl_old'] = $select['level'];
      array_push($_SESSION['used'], $_POST['pokemonid']);
      $count++;
      $update = mysql_fetch_assoc(mysql_query("SELECT pw.*, ps.* FROM pokemon_wild AS pw INNER JOIN pokemon_speler ps ON pw.wild_id = ps.wild_id WHERE id = '".$_POST['pokemonid']."'"));
      if($update['level'] <= 100){
        #informatie van level
        $nieuwelevel = $update['level']+1; # Dit was 2
        $levelnieuw = $update['level']+1;
        
        #Script aanroepen dat nieuwe stats berekent
        nieuwestats($update,$nieuwelevel,$update['expnodig']);
        
        #Script aanroepen dat berekent als pokemon evolueert of een aanval leert
        if((!$_SESSION['aanvalnieuw']) AND (!$_SESSION['evolueren']))
          $toestemming = levelgroei($levelnieuw,$update);
        
        #Gebeurtenis maken.
        $pokemonnaam = htmlspecialchars($update['naam'], ENT_QUOTES);

        mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
	        VALUES (NULL, NOW(), '".$_SESSION['id']."', '".$pokemonnaam." is een level gegroeid.', '0')");
      } 
    }
	  echo' <div class="green">'.$txt['success_take'].'</div>';
	}
}
	
?>

<table width="660">
	<tr>
    	<td width="100" valign="top"><img src="images/daycare.gif" width="80" height="72" /></td>
        <td width="560" valign="top"><?php echo $hoeveel.'<br />'.$txt['title_text']; ?>
		
		<?php if($aantal < $toegestaan){ ?>
        
        <HR />
        
        <?php if($gebruiker['in_hand'] == 0){
			echo '<center>'.$txt['alert_no_pokemon'].'</center>';
		}
		else{?>
        
            <form method="post">
            <center>
            <div style="padding-bottom:10px;"><?php echo $txt['give_pokemon_text']; ?></div>
            <select name="pokemonid" class="text_select" style="float:none; margin-right:2px;">
            <?php
                #Pokemons opzak weergeven op het scherm
                if($gebruiker['in_hand'] > 0) mysql_data_seek($pokemon_sql, 0);
                mysql_data_seek($pokemon_sql, 0);
                while($pokemon = mysql_fetch_assoc($pokemon_sql)){
                    if($pokemon['ei'] == 0) {
                        echo '<option value="' . $pokemon['id'] . '">' . $pokemon['naam'] . '</option>';
                    }
                }
				mysql_data_seek($pokemon_sql, 0);
            ?></select>
            <button type="submit" name="brengweg" class="button"><?php echo $txt['button_bring']; ?></button>
            </center>
            </form>

        <?php }} if($aantal > 0){ ?>

        <HR />
        	
            <center>
            <div style="padding-bottom:10px;"><?php echo $txt['take_pokemon_text']; ?></div>
            <table width="560" cellpadding="0" cellspacing="0">
            	<tr>
                	<td width="50" class="top_first_td"><?php echo $txt['#']; ?></td>
                    <td width="110" class="top_td"><?php echo $txt['name']; ?></td>
                    <td width="100" class="top_td"><?php echo $txt['level']; ?></td>
                    <td width="100" class="top_td"><?php echo $txt['levelup']; ?></td>
                    <td width="120" class="top_td"><?php echo $txt['cost']; ?></td>
                    <td width="80" class="top_td"><?php echo $txt['buy']; ?></td>
               </tr>
               <?php while($daycare = mysql_fetch_assoc($daycaresql)){
      				   $level = $daycare['level'] + $daycare['levelup'];
      				   $kostenlevelup = $daycare['levelup'] * 500;
      				   $kosten = $kostenbegin + $kostenlevelup;
      				   $map = 'pokemon';
      				   if($daycare['shiny'] == '1') $map = 'shiny';
      						echo'
      							<tr>
      								<td class="normal_first_td"><img src="images/'.$map.'/icon/'.$daycare['wild_id'].'.gif"></td>
      								<td class="normal_td">'.$daycare['naam'].'</td>
      								<td class="normal_td">'.$level.'</td>
      								<td class="normal_td">'.$daycare['levelup'].'</td>
      								<td class="normal_td"><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;"> '.highamount($kosten).'</td>
      								<td class="normal_td"><form method="post"><input type="hidden" name="pokemonid" value="'.$daycare['pokemonid'].'">
      									<button type="submit" name="haalop" class="button">'.$txt['button_take'].'</button></form></td>
      						   </tr>';
      					   }
      			   ?>
            </table>
            </center>
            <?php } ?>
        </td>
    </tr>
</table>