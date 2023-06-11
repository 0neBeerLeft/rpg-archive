<?php
echo "<center>tijdelijk uitgeschakeld</center>";
exit;
//Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");


if($gebruiker['rank'] <= 4) echo '<div class="red"><img src="images/icons/red.png">Hierfür ist dein Rang zu niedrig. Du benötigst mindestens <b>Rang 4</b>!</div>';

else { 

$page = 'traders';
//Goeie taal erbij laden voor de page
include_once('language/language-pages.php');


// arrays , refresh button.
$pokemonnaam = mysql_query("SELECT FROM pokemon_wild naam ORDER BY RAND() LIMIT 0,100");

$traders_sql = mysql_query("SELECT * FROM traders");
										 
if($_POST['submit']){
  $trader = mysql_fetch_assoc(mysql_query("SELECT * FROM `traders` WHERE `eigenaar`='".$_POST['check']."'"));
	
	//Kijken of user betreffende pokemon wel heeft
	if(mysql_num_rows(mysql_query("SELECT pokemon_speler.id FROM pokemon_speler INNER JOIN pokemon_wild ON pokemon_speler.wild_id = pokemon_wild.wild_id WHERE pokemon_wild.naam='".$trader['wil']."' AND pokemon_speler.user_id='".$_SESSION['id']."' AND pokemon_speler.opzak='ja'")) == 0)
    echo '<div class="blue">'.$trader['eigenaar'].': '.$txt['alert_dont_have_1'].' '.$trader['wil'].' '.$txt['alert_dont_have_2'].'</div>';
	//Kijken of pokemon nog niet is geruild
	elseif(empty($trader['naam']))
    echo '<div class="blue">'.$trader['eigenaar'].': '.$txt['alert_i_have_1'].' '.$trader['naam'].' '.$txt['alert_i_have_2'].'</div>';
  else{	
    if($trader['eigenaar'] == 'Wayne')
      mysql_query("UPDATE gebruikers SET silver = silver+'100' WHERE user_id = '".$_SESSION['id']."'");
	
	  echo '<div class="green">'.$trader['eigenaar'].': '.$txt['success_traders_change'].' '.$trader['naam'].'!</div>';
	
  	$delete_info = mysql_fetch_assoc(mysql_query("SELECT pokemon_speler.id, pokemon_speler.opzak_nummer, pokemon_speler.level, pokemon_speler.gevongenmet, pokemon_wild.naam 
												 FROM pokemon_speler
												 INNER JOIN pokemon_wild
												 ON pokemon_speler.wild_id = pokemon_wild.wild_id
												 WHERE pokemon_wild.naam = '".$trader['wil']."' 
												 AND pokemon_speler.user_id='".$_SESSION['id']."'
                         AND pokemon_speler.opzak='ja' 
												 ORDER BY pokemon_speler.opzak_nummer ASC LIMIT 1"));

  	//Pokemon die je geruild hebt deleten
  	mysql_query("DELETE FROM pokemon_speler WHERE id = '".$delete_info['id']."'");
   	
  	//Add Pokemon
  	//Load pokemon basis
    $add_sql = mysql_fetch_assoc(mysql_query("SELECT wild_id, naam, aanval_1, aanval_2, aanval_3, aanval_4 FROM pokemon_wild WHERE naam='".$trader['naam']."'"));
    
    $add_pokemon['id']             = $add_sql['wild_id'];
    $add_pokemon['pokemon']        = $add_sql['naam'];
    $add_pokemon['aanval1']        = $add_sql['aanval_1'];
    $add_pokemon['aanval2']        = $add_sql['aanval_2'];
    $add_pokemon['aanval3']        = $add_sql['aanval_3'];
    $add_pokemon['aanval4']        = $add_sql['aanval_4'];
    $klaar          = false;
    $loop           = 0;
    $lastid         = 0;
    //Loop beginnen
    do{ 
      $teller = 0;
      $loop++;
      //Levelen gegevens laden van de pokemon
      $levelenquery = mysql_query("SELECT * FROM `levelen` WHERE `wild_id`='".$add_pokemon['id']."' AND `level`<='".$delete_info['level']."' ORDER BY `id` ASC ");
      //Voor elke pokemon alle gegeven behandelen
      while($groei = mysql_fetch_assoc($levelenquery)){
        //Teller met 1 verhogen
        $teller++;
        //Is het nog binnen de level?
        if($delete_info['level'] >= $groei['level']){
          //Is het een aanval?
          if($groei['wat'] == 'att'){
            //Is er een plek vrij
            if(empty($add_pokemon['aanval1'])) $add_pokemon['aanval1'] = $groei['aanval'];
            elseif(empty($add_pokemon['aanval2'])) $add_pokemon['aanval2'] = $groei['aanval'];
            elseif(empty($add_pokemon['aanval3'])) $add_pokemon['aanval3'] = $groei['aanval'];
            elseif(empty($add_pokemon['aanval4'])) $add_pokemon['aanval4'] = $groei['aanval'];
            //Er is geen ruimte, dan willekeurig een aanval kiezen en plaatsen
            else{
              if(($add_pokemon['aanval1'] != $groei['aanval']) AND ($add_pokemon['aanval2'] != $groei['aanval']) AND ($add_pokemon['aanval3'] != $groei['aanval']) AND ($add_pokemon['aanval4'] != $groei['aanval'])){
                $nummer = rand(1,4);
                if($nummer == 1) $add_pokemon['aanval1'] = $groei['aanval'];
                elseif($nummer == 2) $add_pokemon['aanval2'] = $groei['aanval'];
                elseif($nummer == 3) $add_pokemon['aanval3'] = $groei['aanval'];
                elseif($nummer == 4) $add_pokemon['aanval4'] = $groei['aanval'];
              }
            }
          }
          //Evolueert de pokemon
          elseif($groei['wat'] == "evo"){
            $evo = mysql_fetch_assoc(mysql_query("SELECT * FROM `pokemon_wild` WHERE `wild_id`='".$groei['nieuw_id']."'"));
            $add_pokemon['id']             = $groei['nieuw_id'];
            $add_pokemon['pokemon']        = $groei['naam'];
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
    }while(!$klaar);
    
    $exp['level'] = $delete_info['level']+1;
    $info = mysql_fetch_assoc(mysql_query("SELECT experience.punten, pokemon_wild.* FROM experience INNER JOIN pokemon_wild WHERE experience.soort='".$pokemon['groei']."' AND experience.level='".$exp['level']."' AND pokemon_wild.wild_id='".$add_pokemon['id']."'"));
    
    //Karakter kiezen 
    $karakter  = mysql_fetch_assoc(mysql_query("SELECT * FROM `karakters` ORDER BY rand() limit 1"));
      
    //Pokemon IV maken en opslaan
    //Iv willekeurig getal tussen 1,31. Ik neem 2 omdat 1 te weinig is:P
    $attack_iv       = rand(2,31);
    $defence_iv      = rand(2,31);
    $speed_iv        = rand(2,31);
    $spcattack_iv    = rand(2,31);
    $spcdefence_iv   = rand(2,31);
    $hp_iv           = rand(2,31);
  
    //Stats berekenen
    $add_pokemon['attackstat']     = round((((($info['attack_base']*2+$attack_iv)*$delete_info['level']/100)+5)*1)*$karakter['attack_add']);
    $add_pokemon['defencestat']    = round((((($info['defence_base']*2+$defence_iv)*$delete_info['level']/100)+5)*1)*$karakter['defence_add']);
    $add_pokemon['speedstat']      = round((((($info['speed_base']*2+$speed_iv)*$delete_info['level']/100)+5)*1)*$karakter['speed_add']);
    $add_pokemon['spcattackstat']  = round((((($info['spc.attack_base']*2+$spcattack_iv)*$delete_info['level']/100)+5)*1)*$karakter['spc.attack_add']);
    $add_pokemon['spcdefencestat'] = round((((($info['spc.defence_base']*2+$spcdefence_iv)*$delete_info['level']/100)+5)*1)*$karakter['spc.defence_add']);
    $add_pokemon['hpstat']         = round(((($info['hp_base']*2+$hp_iv)*$delete_info['level']/100)+$delete_info['level'])+10);
    
    //Iv willekeurig getal tussen 2,15
    //Normaal tussen 1,31 maar wilde pokemon moet wat minder sterk zijn
    $attack_iv       = rand(2,15);
    $defence_iv      = rand(2,15);
    $speed_iv        = rand(2,15);
    $spcattack_iv    = rand(2,15);
    $spcdefence_iv   = rand(2,15);
    $hp_iv           = rand(2,15);
    
    
    mysql_query("INSERT INTO `pokemon_speler` (`wild_id`, `user_id`, `opzak`, `opzak_nummer`, `karakter`, `trade`, `level`, `levenmax`, `leven`, `expnodig`, `attack`, `defence`, `speed`, `spc.attack`, `spc.defence`, `attack_iv`, `defence_iv`, `speed_iv`, `spc.attack_iv`, `spc.defence_iv`, `hp_iv`, `aanval_1`, `aanval_2`, `aanval_3`, `aanval_4`, `gevongenmet`) 
      VALUES ('".$add_pokemon['id']."', '".$_SESSION['id']."', 'ja', '".$delete_info['opzak_nummer']."', '".$karakter['karakter_naam']."', '1.5', '".$delete_info['level']."', '".$add_pokemon['hpstat'] ."', '".$add_pokemon['hpstat']."', '".$info['punten']."', '".$add_pokemon['attackstat']."', '".$add_pokemon['defencestat']."', '".$add_pokemon['speedstat']."', '".$add_pokemon['spcattackstat']."', '".$add_pokemon['spcdefencestat']."', '".$attack_iv."', '".$defence_iv."', '".$speed_iv."', '".$spcattack_iv."', '".$spcdefence_iv."', '".$hp_iv."', '".$add_pokemon['aanval1']."', '".$add_pokemon['aanval2']."', '".$add_pokemon['aanval3']."', '".$add_pokemon['aanval4']."', '".$delete_info['gevongenmet']."')");
    
    //Remove pokemon from trader
    mysql_query("UPDATE `traders` SET `wil`='', `naam`=''  WHERE `eigenaar`='".$_POST['check']."'");
    
    update_pokedex($add_pokemon['id'],'','ei');
    
	}
}

#Admin functie trainer refresh:

if(isset($_POST['refresh'])){
	mysql_query("UPDATE `traders` SET `wil`='', `naam`='$pokemonnaam'");
	echo '<div class="green">'.$txt['success_traders_refresh'].'</div>';
}
?>

<center>
<?php echo $txt['title_text']; ?>

<?php
$traders_sql = mysql_query("SELECT * FROM traders");
while($traders = mysql_fetch_assoc($traders_sql)){
	echo '<div class="sep"></div>';
	if($traders['eigenaar'] == 'Kayl'){
		if(empty($traders['naam']))
			$text = $txt['kayl_no_pokemon'];
		else{
			$text = $txt['kayl_text_1'].$traders['wil'].$txt['kayl_text_2'].$traders['naam'].$txt['kayl_text_3'].'
					 <input type="hidden" name="check" value="'.$traders['eigenaar'].'">
					 <input type="submit" name="submit" style="margin-top:101px;" class="button" value="'.$txt['button_change'].' '.$traders['eigenaar'].'">';
		}
		
		echo '<form method="post">
            <table width="500">
    					<tr>
    						<td width="160"><center><img src="images/Kayl.png" width="97" height="200" alt="Italo" /></center></td>
    						<td width="340" valign="top">'.$text.'</td>
    					</tr>
    				</table>
          </form>';
	}
	
	if($traders['eigenaar'] == 'Wayne'){
		if(empty($traders['naam']))
			$text = $txt['wayne_no_pokemon'];
		else{
			$text = $txt['wayne_text_1'].$traders['wil'].$txt['wayne_text_2'].$traders['naam'].$txt['wayne_text_3'].'
					 <input type="hidden" name="check" value="'.$traders['eigenaar'].'">
					 <input type="submit" name="submit" style="margin-top:81px;" class="button" value="'.$txt['button_change'].' '.$traders['eigenaar'].'">';
		}
		
		echo '<form method="post">
            <table width="500">
    					<tr>
    						<td width="160"><center><img src="images/Wayne.png" width="81" height="200" alt="Wayne" /></center></td>
    						<td width="340" valign="top">'.$text.'</td>
    					</tr>
    				</table>
          </form>';
	}
	
	if($traders['eigenaar'] == 'Remy'){
		if(empty($traders['naam']))
			$text = $txt['remy_no_pokemon'];
		else{
			$text = $txt['remy_text_1'].$traders['wil'].$txt['remy_text_2'].$traders['naam'].$txt['remy_text_3'].'
					 <input type="hidden" name="check" value="'.$traders['eigenaar'].'">
					 <input type="submit" name="submit" style="margin-top:115px;" class="button" value="'.$txt['button_change'].' '.$traders['eigenaar'].'">';
		}
		
		echo '<form method="post">
            <table width="500">
      				<tr>
      					<td width="160"><center><img src="images/Remy.png" width="88" height="200" alt="Remy" /></center></td>
      					<td width="340" valign="top">'.$text.'</td>
      				</tr>
      			</table>
          </form>';
	}  
}
?>

</center>
<?php } ?>