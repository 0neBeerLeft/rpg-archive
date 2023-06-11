<?		
//Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

//Admin controle
if($gebruiker['admin'] < 2) header('location: index.php?page=home');

#####################################################################


if(isset($_POST['submit'])) {
  //Makkelijke naam toewijzen
  $user_id = $_POST['user_id'];
  $wereld = $_POST['wereld'];
  $ei     = $_POST['ei'];

  //Gegevens laden van speler
  $aantal = mysql_num_rows(mysql_query("SELECT `user_id` FROM `pokemon_speler` WHERE `user_id`='".$user_id."' AND `opzak`='ja'"));

  if($user_id == '')
  	echo '<div class="red"><img src="images/icons/red.png"> No player selected.</div>';
  elseif($wereld == '')
  	echo '<div class="red"><img src="images/icons/red.png"> No world selected.</div>';
  elseif($ei == '')
  	echo '<div class="red"><img src="images/icons/red.png"> No egg chosen.</div>';
  elseif($aantal == 6)
    echo '<div class="red"><img src="images/icons/red.png"> The Trainer already has 6 pokemon.</div>';
  else{
    //1 bij aantal op doen, omdat je anders 2x opzak nummer 1 hebt bijv.
    $opzak_nummer = $aantal+1;
    //Baby pokemon timer starten
    $tijd = date('Y-m-d H:i:s');
    //Pokemon opslaan
    //Als het ei een starter ei is
	  if($ei == 1){ 
      //Willekeurige pokemon laden, en daarvan de gegevens
      $query = mysql_fetch_array(mysql_query("SELECT pw.wild_id, pw.naam, pw.groei, pw.attack_base, pw.defence_base, pw.speed_base, `pw`.`spc.attack_base`, `pw`.`spc.defence_base`, pw.hp_base, pw.aanval_1, pw.aanval_2, pw.aanval_3, pw.aanval_4 FROM pokemon_wild AS pw INNER JOIN pokemon_nieuw_starter AS pnb ON pw.wild_id = pnb.wild_id ORDER BY rand() LIMIT 1"));
	  
      //De willekeurige pokemon in de pokemon_speler tabel zetten
      mysql_query("INSERT INTO `pokemon_speler` (`wild_id`, `aanval_1`, `aanval_2`, `aanval_3`, `aanval_4`) SELECT `wild_id`, `aanval_1`, `aanval_2`, `aanval_3`, `aanval_4` FROM `pokemon_wild` WHERE `wild_id`='".$query['wild_id']."'");
      //id opvragen van de insert hierboven
      $pokeid	= mysql_insert_id();
      
      //Heeft speler wel pokemon gekregen??
      if(is_numeric($pokeid)){
        mysql_query("UPDATE `gebruikers` SET `aantalpokemon`=`aantalpokemon`+'1' WHERE `user_id`='".$user_id."'");
      }
      
      //Karakter kiezen 
      $karakter  = mysql_fetch_array(mysql_query("SELECT * FROM `karakters` ORDER BY rand() limit 1"));
      
      //Expnodig opzoeken en opslaan
      $experience = mysql_fetch_array(mysql_query("SELECT `punten` FROM `experience` WHERE `soort`='".$query['groei']."' AND `level`='6'"));
    
      //Pokemon IV maken en opslaan
      //Iv willekeurig getal tussen 1,31. Ik neem 2 omdat 1 te weinig is:P
      $attack_iv       = rand(2,31);
      $defence_iv      = rand(2,31);
      $speed_iv        = rand(2,31);
      $spcattack_iv    = rand(2,31);
      $spcdefence_iv   = rand(2,31);
      $hp_iv           = rand(2,31);
    
      //Stats berekenen
      $attackstat     = round((((($query['attack_base']*2+$attack_iv)*5/100)+5)*1)*$karakter['attack_add']);
      $defencestat    = round((((($query['defence_base']*2+$defence_iv)*5/100)+5)*1)*$karakter['defence_add']);
      $speedstat      = round((((($query['speed_base']*2+$speed_iv)*5/100)+5)*1)*$karakter['speed_add']);
      $spcattackstat  = round((((($query['spc.attack_base']*2+$spcattack_iv)*5/100)+5)*1)*$karakter['spc.attack_add']);
      $spcdefencestat = round((((($query['spc.defence_base']*2+$spcdefence_iv)*5/100)+5)*1)*$karakter['spc.defence_add']);
      $hpstat         = round(((($query['hp_base']*2+$hp_iv)*5/100)+5)+10);
      
      //Alle gegevens van de pokemon opslaan
      mysql_query("UPDATE `pokemon_speler` SET `level`='5', `karakter`='".$karakter['karakter_naam']."', `expnodig`='".$experience['punten']."', `user_id`='".$user_id."', `opzak`='ja', `opzak_nummer`='".$opzak_nummer."', `ei`='1', `ei_tijd`='".$tijd."', `attack_iv`='".$attack_iv."', `defence_iv`='".$defence_iv."', `speed_iv`='".$speed_iv."', `spc.attack_iv`='".$spcattack_iv."', `spc.defence_iv`='".$spcdefence_iv."', `hp_iv`='".$hp_iv."', `attack`='".$attackstat."', `defence`='".$defencestat."', `speed`='".$speedstat."', `spc.attack`='".$spcattackstat."', `spc.defence`='".$spcdefencestat."', `levenmax`='".$hpstat."', `leven`='".$hpstat."' WHERE `id`='".$pokeid."'");
    }
    //Als het ei een gewoon ei is.
    elseif($ei == 2){
      //Willekeurige pokemon laden, en daarvan de gegevens
      $query = mysql_fetch_array(mysql_query("SELECT pw.wild_id, pw.naam, pw.groei, pw.attack_base, pw.defence_base, pw.speed_base, `pw`.`spc.attack_base`, `pw`.`spc.defence_base`, pw.hp_base, pw.aanval_1, pw.aanval_2, pw.aanval_3, pw.aanval_4 FROM pokemon_wild AS pw INNER JOIN pokemon_nieuw_normaal AS pnb ON pw.wild_id = pnb.wild_id ORDER BY rand() LIMIT 1"));
      //De willekeurige pokemon in de pokemon_speler tabel zetten
      mysql_query("INSERT INTO `pokemon_speler` (`wild_id`, `aanval_1`, `aanval_2`, `aanval_3`, `aanval_4`) SELECT `wild_id`, `aanval_1`, `aanval_2`, `aanval_3`, `aanval_4` FROM `pokemon_wild` WHERE `wild_id`='".$query['wild_id']."'");
      //id opvragen van de insert hierboven
      $pokeid	= mysql_insert_id();
      
      //Heeft speler wel pokemon gekregen??
      if(is_numeric($pokeid)){
        mysql_query("UPDATE `gebruikers` SET `aantalpokemon`=`aantalpokemon`+'1' WHERE `user_id`='".$user_id."'");
      }
      
      //Karakter kiezen 
      $karakter  = mysql_fetch_array(mysql_query("SELECT * FROM `karakters` ORDER BY rand() limit 1"));
      
      //Expnodig opzoeken en opslaan
      $experience = mysql_fetch_array(mysql_query("SELECT `punten` FROM `experience` WHERE `soort`='".$query['groei']."' AND `level`='6'"));
    
      //Pokemon IV maken en opslaan
      //Iv willekeurig getal tussen 1,31. Ik neem 2 omdat 1 te weinig is:P
      $attack_iv       = rand(2,31);
      $defence_iv      = rand(2,31);
      $speed_iv        = rand(2,31);
      $spcattack_iv    = rand(2,31);
      $spcdefence_iv   = rand(2,31);
      $hp_iv           = rand(2,31);
    
      //Stats berekenen
      $attackstat     = round((((($query['attack_base']*2+$attack_iv)*5/100)+5)*1)*$karakter['attack_add']);
      $defencestat    = round((((($query['defence_base']*2+$defence_iv)*5/100)+5)*1)*$karakter['defence_add']);
      $speedstat      = round((((($query['speed_base']*2+$speed_iv)*5/100)+5)*1)*$karakter['speed_add']);
      $spcattackstat  = round((((($query['spc.attack_base']*2+$spcattack_iv)*5/100)+5)*1)*$karakter['spc.attack_add']);
      $spcdefencestat = round((((($query['spc.defence_base']*2+$spcdefence_iv)*5/100)+5)*1)*$karakter['spc.defence_add']);
      $hpstat         = round(((($query['hp_base']*2+$hp_iv)*5/100)+5)+10);
      
      //Alle gegevens van de pokemon opslaan
      mysql_query("UPDATE `pokemon_speler` SET `level`='5', `karakter`='".$karakter['karakter_naam']."', `expnodig`='".$experience['punten']."', ``user_id`='".$user_id."', `opzak`='ja', `opzak_nummer`='".$opzak_nummer."', `ei`='1', `ei_tijd`='".$tijd."', `attack_iv`='".$attack_iv."',`defence_iv`='".$defence_iv."', `speed_iv`='".$speed_iv."', `spc.attack_iv`='".$spcattack_iv."', `spc.defence_iv`='".$spcdefence_iv."', `hp_iv`='".$hp_iv."', `attack`='".$attackstat."', `defence`='".$defencestat."', `speed`='".$speedstat."', `spc.attack`='".$spcattackstat."', `spc.defence`='".$spcdefencestat."', `levenmax`='".$hpstat."', `leven`='".$hpstat."' WHERE `id`='".$pokeid."'");
    }
    //Als het ei een baby ei is
    elseif($ei == 3){
      //Willekeurige pokemon laden, en daarvan de gegevens
      $query = mysql_fetch_array(mysql_query("SELECT pw.wild_id, pw.naam, pw.groei, pw.attack_base, pw.defence_base, pw.speed_base, `pw`.`spc.attack_base`, `pw`.`spc.defence_base`, pw.hp_base, pw.aanval_1, pw.aanval_2, pw.aanval_3, pw.aanval_4 FROM pokemon_wild AS pw INNER JOIN pokemon_nieuw_baby AS pnb ON pw.wild_id = pnb.wild_id ORDER BY rand() LIMIT 1"));
      //De willekeurige pokemon in de pokemon_speler tabel zetten
      mysql_query("INSERT INTO `pokemon_speler` (`wild_id`, `aanval_1`, `aanval_2`, `aanval_3`, `aanval_4`) SELECT `wild_id`, `aanval_1`, `aanval_2`, `aanval_3`, `aanval_4` FROM `pokemon_wild` WHERE `wild_id`='".$query['wild_id']."'");
      //id opvragen van de insert hierboven
      $pokeid	= mysql_insert_id();
      
      //Heeft speler wel pokemon gekregen??
      if(is_numeric($pokeid)){
        mysql_query("UPDATE `gebruikers` SET `aantalpokemon`=`aantalpokemon`+'1' WHERE `user_id`='".$user_id."'");
      }
      
      //Karakter kiezen 
      $karakter  = mysql_fetch_array(mysql_query("SELECT * FROM `karakters` ORDER BY rand() limit 1"));
      
      //Expnodig opzoeken en opslaan
      $experience = mysql_fetch_array(mysql_query("SELECT `punten` FROM `experience` WHERE `soort`='".$query['groei']."' AND `level`='6'"));
    
      //Pokemon IV maken en opslaan
      //Iv willekeurig getal tussen 1,31. Ik neem 2 omdat 1 te weinig is:P
      $attack_iv       = rand(2,31);
      $defence_iv      = rand(2,31);
      $speed_iv        = rand(2,31);
      $spcattack_iv    = rand(2,31);
      $spcdefence_iv   = rand(2,31);
      $hp_iv           = rand(2,31);
    
      //Stats berekenen
      $attackstat     = round((((($query['attack_base']*2+$attack_iv)*5/100)+5)*1)*$karakter['attack_add']);
      $defencestat    = round((((($query['defence_base']*2+$defence_iv)*5/100)+5)*1)*$karakter['defence_add']);
      $speedstat      = round((((($query['speed_base']*2+$speed_iv)*5/100)+5)*1)*$karakter['speed_add']);
      $spcattackstat  = round((((($query['spc.attack_base']*2+$spcattack_iv)*5/100)+5)*1)*$karakter['spc.attack_add']);
      $spcdefencestat = round((((($query['spc.defence_base']*2+$spcdefence_iv)*5/100)+5)*1)*$karakter['spc.defence_add']);
      $hpstat         = round(((($query['hp_base']*2+$hp_iv)*5/100)+5)+10);
      
      //Alle gegevens van de pokemon opslaan
      mysql_query("UPDATE `pokemon_speler` SET `level`='5', `karakter`='".$karakter['karakter_naam']."', `expnodig`='".$experience['punten']."', `user_id`='".$user_id."', `opzak`='ja', `opzak_nummer`='".$opzak_nummer."', `ei`='1', `baby_tijd`='".$tijd."', `attack_iv`='".$attack_iv."',`defence_iv`='".$defence_iv."', `speed_iv`='".$speed_iv."', `spc.attack_iv`='".$spcattack_iv."', `spc.defence_iv`='".$spcdefence_iv."', `hp_iv`='".$hp_iv."', `attack`='".$attackstat."', `defence`='".$defencestat."', `speed`='".$speedstat."', `spc.attack`='".$spcattackstat."', `spc.defence`='".$spcdefencestat."', `levenmax`='".$hpstat."', `leven`='".$hpstat."' WHERE `id`='".$pokeid."'");
    }
   echo '<div class="green"><img src="images/icons/green.png"> You have '.$_POST['speler'].' successfully gave a pokemon egg.</div>';      
  }
}
?>



<center>
<form method="post">

<p>You can give someone a Pokemon egg.<br />
*Please note, if someone already has 6 Pokemon with them do not use this!</p>
<table width="250">
  <tr>
    	<td width="100"><strong>User ID:</strong></td>
    <td width="150"><input name="user_id" class="text_long" type="text" value="<?php if($_GET['player'] == '') echo $_POST['user_id']; else echo $_GET['player']; ?>"></td>
    </tr>
  <tr>
    <td rowspan="10">&nbsp;</td>
    <td height="40"><strong>Egg choice:</strong></td>
    </tr>
  <tr>
    <td><input type="radio" name="ei" value="1" id="starter" /> <label for="starter">Starter Egg</label></td>
    </tr>
  <tr>
    <td><input type="radio" name="ei" value="2" id="gewoon" /> <label for="gewoon">Simpe Egg</label></td>
    </tr>
  <tr>
    <td><input type="radio" name="ei" value="3" id="baby" /> <label for="baby">Baby Egg</label></td>
    </tr>
  <tr>
    <td height="40"><strong>World:</strong></td>
    </tr>
  <tr>
    <td><input type="radio" name="wereld" value="Kanto" id="kanto" /> <label for="kanto">Kanto</label></td>
    </tr>
  <tr>
    <td><input type="radio" name="wereld" value="Johto" id="johto" /> <label for="johto">Johto</label></td>
    </tr>
  <tr>
    <td><input type="radio" name="wereld" value="Hoenn" id="hoenn" /> <label for="hoenn">Hoenn</label></td>
    </tr>
  <tr>
    <td><input type="radio" name="wereld" value="Sinnoh" id="sinnoh" /> <label for="sinnoh">Sinnoh</label></td>
    </tr>
  <tr>
    <td><input name="submit" type="submit" value="Give Egg" class="button"></td>
    </tr>
</table>
</form>
</center>