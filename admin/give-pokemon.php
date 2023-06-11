<?
//Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

//Admin controle
if($gebruiker['admin'] < 2) header('location: index.php?page=home');

if(isset($_POST['geef'])){  

	//Gegevens laden van speler
	$aantal = mysql_num_rows(mysql_query("SELECT `user_id` FROM `pokemon_speler` WHERE `user_id`='".$_POST['user_id']."' AND `opzak`='ja'"));

	if($_POST['user_id'] == '') echo '<div class="red"><img src="images/icons/red.png"> No player selected.</div>';
	elseif($_POST['wild_id'] == 'none') echo '<div class="red"><img src="images/icons/red.png"> No pokemon selected.</div>';
	elseif($aantal == 6) echo '<div class="red"><img src="images/icons/red.png"> The Trainer already has 6 pokemon.</div>';
	elseif(mysql_num_rows(mysql_query("SELECT user_id FROM gebruikers WHERE user_id='".$_POST['user_id']."'")) == 0) echo '<div class="red"><img src="images/icons/red.png"> User unknown.</div>';
	elseif(($_POST['level'] > 100) OR ($_POST['level'] < 5)) echo '<div class="red"><img src="images/icons/red.png"> Level is too high or too low.</div>';
	else{
    //Load pokemon basis
    $new_computer_sql = mysql_fetch_array(mysql_query("SELECT * FROM `pokemon_wild` WHERE `wild_id`='".$_POST['wild_id']."'"));

    //Alle gegevens vast stellen voordat alles begint.
    $new_computer['id']             = $new_computer_sql['wild_id'];
    $new_computer['pokemon']        = $new_computer_sql['naam'];
    $new_computer['aanval1']        = $new_computer_sql['aanval_1'];
    $new_computer['aanval2']        = $new_computer_sql['aanval_2'];
    $new_computer['aanval3']        = $new_computer_sql['aanval_3'];
    $new_computer['aanval4']        = $new_computer_sql['aanval_4'];
    $klaar          = false;
    $loop           = 0;
    $lastid         = 0;

    //Loop beginnen
    do{ 
      $teller = 0;
      $loop++;
      //Levelen gegevens laden van de pokemon
      $levelenquery = mysql_query("SELECT * FROM `levelen` WHERE `wild_id`='".$new_computer['id']."' AND `level`<='".$_POST['level']."' AND `stone`='' ORDER BY `id` ASC ");

      //Voor elke pokemon alle gegeven behandelen
      while($groei = mysql_fetch_array($levelenquery)){

        //Teller met 1 verhogen
        $teller++;
        //Is het nog binnen de level?
        if($_POST['level'] >= $groei['level']){
          //Is het een aanval?
          if($groei['wat'] == 'att'){
            //Is er een plek vrij
            if(empty($new_computer['aanval1'])) $new_computer['aanval1'] = $groei['aanval'];
            elseif(empty($new_computer['aanval2'])) $new_computer['aanval2'] = $groei['aanval'];
            elseif(empty($new_computer['aanval3'])) $new_computer['aanval3'] = $groei['aanval'];
            elseif(empty($new_computer['aanval4'])) $new_computer['aanval4'] = $groei['aanval'];
            //Er is geen ruimte, dan willekeurig een aanval kiezen en plaatsen
            else{
              if(($new_computer['aanval1'] != $groei['aanval']) AND ($new_computer['aanval2'] != $groei['aanval']) AND ($new_computer['aanval3'] != $groei['aanval']) AND ($new_computer['aanval4'] != $groei['aanval'])){
                $nummer = rand(1,4);
                if($nummer == 1) $new_computer['aanval1'] = $groei['aanval'];
                elseif($nummer == 2) $new_computer['aanval2'] = $groei['aanval'];
                elseif($nummer == 3) $new_computer['aanval3'] = $groei['aanval'];
                elseif($nummer == 4) $new_computer['aanval4'] = $groei['aanval'];
              }
            }
          }

          //Evolueert de pokemon
          elseif($groei['wat'] == "evo"){
            $evo = mysql_fetch_array(mysql_query("SELECT * FROM `pokemon_wild` WHERE `wild_id`='".$groei['nieuw_id']."'"));
            $new_computer['id']             = $groei['nieuw_id'];
            $new_computer['pokemon']        = $groei['naam'];
            $loop = 0;
            break;
          }
        }

        //Er gebeurd niks dan stoppen
        else{
          $klaar = true;
          break;
        }
      }
      if($teller == 0){
        break;
        $klaar = true;
      }
      if($loop == 2){
        break;
        $klaar = true;
      }
    }
	while(!$klaar);

    //Karakter kiezen 
    $karakter  = mysql_fetch_array(mysql_query("SELECT * FROM `karakters` ORDER BY rand() limit 1"));

    //Expnodig opzoeken en opslaan
    $level = $_POST['level']+1;
    $experience = mysql_fetch_array(mysql_query("SELECT `punten` FROM `experience` WHERE `soort`='".$new_computer_sql['groei']."' AND `level`='".$level."'"));

    //Iv willekeurig getal tussen 2,31
    $attack_iv       = rand(2,31);
    $defence_iv      = rand(2,31);
    $speed_iv        = rand(2,31);
    $spcattack_iv    = rand(2,31);
    $spcdefence_iv   = rand(2,31);
    $hp_iv           = rand(2,31);

    //Stats berekenen
    $new_computer['attackstat']     = round(((($new_computer_sql['attack_base']*2+$attack_iv)*$_POST['level']/100)+5)*1);
    $new_computer['defencestat']    = round(((($new_computer_sql['defence_base']*2+$defence_iv)*$_POST['level']/100)+5)*1);
    $new_computer['speedstat']      = round(((($new_computer_sql['speed_base']*2+$speed_iv)*$_POST['level']/100)+5)*1);
    $new_computer['spcattackstat']  = round(((($new_computer_sql['spc.attack_base']*2+$spcattack_iv)*$_POST['level']/100)+5)*1);
    $new_computer['spcdefencestat'] = round(((($new_computer_sql['spc.defence_base']*2+$spcdefence_iv)*$_POST['level']/100)+5)*1);
    $new_computer['hpstat']         = round(((($new_computer_sql['hp_base']*2+$hp_iv)*$_POST['level']/100)+$_POST['level'])+10);

    //Baby pokemon timer starten
    $tijd = date('Y-m-d H:i:s');
    $opzak = $aantal+1;

    //Save Computer
    mysql_query("INSERT INTO `pokemon_speler` (`wild_id`, `user_id`, `opzak`, `opzak_nummer`, `karakter`, `level`, `levenmax`, `leven`, `totalexp`, `expnodig`, `attack`, `defence`, `speed`, `spc.attack`, `spc.defence`, `attack_iv`, `defence_iv`, `speed_iv`, `spc.attack_iv`, `spc.defence_iv`, `hp_iv`, `attack_ev`, `defence_ev`, `speed_ev`, `spc.attack_ev`, `spc.defence_ev`, `hp_ev`, `aanval_1`, `aanval_2`, `aanval_3`, `aanval_4`, `effect`, `ei`, `ei_tijd`) 

      VALUES ('".$new_computer['id']."', '".$_POST['user_id']."', 'ja', '".$opzak."', '".$karakter['karakter_naam']."', '".$_POST['level']."', '".$new_computer['hpstat'] ."', '".$new_computer['hpstat'] ."', '".$experience['punten']."', '".$experience['punten']."', '".$new_computer['attackstat']."', '".$new_computer['defencestat']."', '".$new_computer['speedstat']."', '".$new_computer['spcattackstat']."', '".$new_computer['spcdefencestat']."', '".$attack_iv."', '".$defence_iv."', '".$speed_iv."', '".$spcattack_iv."', '".$spcdefence_iv."', '".$hp_iv."', '".$new_computer_sql['effort_attack']."', '".$new_computer_sql['effort_defence']."', '".$new_computer_sql['effort_spc.attack']."', '".$new_computer_sql['effort_spc.defence']."', '".$new_computer_sql['effort_speed']."', '".$new_computer_sql['effort_hp']."', '".$new_computer['aanval1']."', '".$new_computer['aanval2']."', '".$new_computer['aanval3']."', '".$new_computer['aanval4']."', '".$new_computer_sql['effect']."', '1', '".$tijd."')");
	
	echo '<div class="green"><img src="images/icons/green.png"> successfully gave a pokemon.</div>';

  }
}

  $info = mysql_fetch_assoc(mysql_query("SELECT g.user_id, g.username, g.datum, g.email, g.ip_aangemeld, g.ip_ingelogd, g.silver, g.gold, g.bank, g.premiumaccount, g.admin, g.wereld, g.online, CONCAT(g.voornaam,' ',g.achternaam) AS combiname, g.land, g.`character`, g.profiel, g.teamzien, g.badgeszien, g.rank, g.wereld, g.aantalpokemon, g.badges, g.gewonnen, g.verloren, COUNT(DISTINCT g.user_id) AS 'check', gi.`Badge case`																																																						 											FROM gebruikers AS g 
											INNER JOIN gebruikers_item AS gi 
											ON g.user_id = gi.user_id
											WHERE username='" .$_GET['player']."'
											AND account_code != '0'
											GROUP BY `user_id`"));
?>

<form method="post">
<center>
<p>Give someone a pokemon, the pokemon first show in an egg, even though the pokemon is above level 5.</p>
<table width="350">
	<tr>
    	<td width="150">User ID:</td>
        <td width="200"><input type="text" name="user_id" class="text_long" value="<?php if($_GET['player'] != '') echo $_GET['player']; ?>"></td>
    </tr>
    <tr>
    	<td>Pokemon:</td>
        <td><select name="wild_id" class="text_select">
				<option value="none">Choose someone</option>
				<?php 
                  $allpokemonsql = mysql_query("SELECT wild_id, naam FROM pokemon_wild ORDER BY naam ASC");
                  while($allpokemon = mysql_fetch_array($allpokemonsql)){
                    $allpokemon['naam_goed'] = computer_naam($allpokemon['naam']);
                      echo '<option value="'.$allpokemon['wild_id'].'">'.$allpokemon['naam_goed'].'</option>';
                  }
                ?>
			</select></td>
    </tr>
    <tr>
    	<td>Level:</td>
        <td><input type="text" name="level" class="text_long" /><input type="hidden" name="ID" value="<?PHP echo $info['user_id']; ?>" /></td>
    </tr>
    <tr>
    	<td>&nbsp;</td>
        <td><input type="submit" name="geef" value="Give!" class="button" /></td>
    </tr>
</table>
</center>
</form>