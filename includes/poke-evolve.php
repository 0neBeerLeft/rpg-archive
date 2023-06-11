<?
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

#Gegevens laden van de des betreffende pokemon
$pokemon = mysql_fetch_assoc(mysql_query("SELECT pokemon_wild.wild_id, pokemon_wild.naam, pokemon_wild.groei, pokemon_speler.* FROM pokemon_wild INNER JOIN pokemon_speler ON pokemon_wild.wild_id = pokemon_speler.wild_id WHERE pokemon_speler.id='".$evolueren['pokemonid']."'"));

$update = mysql_fetch_assoc(mysql_query("SELECT * FROM `pokemon_wild` WHERE `wild_id`='".$evolueren['nieuw_id']."'"));

#als er op de doorgaan knop gedrukt word
if(isset($_POST['acceptevolutie'])){
  $tekst = "<div class='blue'>".$pokemon['naam']." has evolved into ".$update['naam']."</div>";
  $button = False;
  #Pokemon opslaan als in bezit
  update_pokedex($update['wild_id'],$pokemon['wild_id'],'evo');

  #Nieuwe stats opslaan
  #Nieuwe level word
  $levelnieuw = $pokemon['level']+1;
  if($levelnieuw > 100) $levelnieuw = 100;
  $info = mysql_fetch_assoc(mysql_query("SELECT experience.punten, karakters.* FROM experience INNER JOIN karakters WHERE experience.soort='".$pokemon['groei']."' AND experience.level='".$levelnieuw."' AND karakters.karakter_naam='".$pokemon['karakter']."'"));

  $attackstat     = round(((((($update['attack_base']*2+$pokemon['attack_iv']+floor($pokemon['attack_ev']/4))*$pokemon['level']/100)+5)*1)+$pokemon['attack_up'])*$info['attack_add']);
  $defencestat    = round(((((($update['defence_base']*2+$pokemon['defence_iv']+floor($pokemon['defence_ev']/4))*$pokemon['level']/100)+5)*1)+$pokemon['defence_up'])*$info['defence_add']);
  $speedstat      = round(((((($update['speed_base']*2+$pokemon['speed_iv']+floor($pokemon['speed_ev']/4))*$pokemon['level']/100)+5)*1)+$pokemon['speed_up'])*$info['speed_add']);
  $spcattackstat  = round(((((($update['spc.attack_base']*2+$pokemon['spc.attack_iv']+floor($pokemon['spc.attack_ev']/4))*$pokemon['level']/100)+5)*1)+$pokemon['spc_up'])*$info['spc.attack_add']);
  $spcdefencestat = round(((((($update['spc.defence_base']*2+$pokemon['spc.defence_iv']+floor($pokemon['spc.defence_ev']/4))*$pokemon['level']/100)+5)*1)+$pokemon['spc_up'])*$info['spc.defence_add']);
  $hpstat         = round((((($update['hp_base']*2+$pokemon['hp_iv']+floor($pokemon['hp_ev']/4))*$pokemon['level']/100)+$pokemon['level'])+10)+$pokemon['hp_up']);

  #Pokemon gegevens en Stats opslaan
  mysql_query("UPDATE `pokemon_speler` SET `wild_id`='".$update['wild_id']."', `attack`='".$attackstat."', `defence`='".$defencestat."', `speed`='".$speedstat."', `spc.attack`='".$spcattackstat."', `spc.defence`='".$spcdefencestat."', `levenmax`='".$hpstat."', `leven`='".$hpstat."' WHERE `id`='".$pokemon['id']."'");
 
  #Check if more pokemon should evolve
  $current = array_pop($_SESSION['used']);      
  
  $count = 0;
  $sql = mysql_query("SELECT pokemon_wild.naam, pokemon_speler.id, pokemon_speler.wild_id, pokemon_speler.roepnaam, pokemon_speler.level, pokemon_speler.expnodig, pokemon_speler.exp FROM pokemon_wild INNER JOIN pokemon_speler ON pokemon_wild.wild_id = pokemon_speler.wild_id WHERE pokemon_speler.id='".$current."'");
  while($select = mysql_fetch_assoc($sql)){
    #Change name for male and female
    $select['naam_goed'] = pokemon_naam($select['naam'],$select['roepnaam']);
    if($select['level'] < 100){
      #Load data from pokemon living grows Leveling table
      $levelensql = mysql_query("SELECT `id`, `level`, `trade`, `wild_id`, `wat`, `nieuw_id`, `aanval` FROM `levelen` WHERE `wild_id`='".$select['wild_id']."' AND `level`>'".$_SESSION['lvl_old']."' AND `level`<='".$select['level']."' ORDER BY id ASC");
      #Voor elke actie kijken als het klopt.
      while($levelen = mysql_fetch_assoc($levelensql)){
        #als de actie een aanval leren is
        if($levelen['wat'] == "att"){
          #Kent de pokemon deze aanval al
          if(($select['aanval_1'] != $levelen['aanval']) AND ($select['aanval_2'] != $levelen['aanval']) AND ($select['aanval_3'] != $levelen['aanval']) AND ($select['aanval_4'] != $levelen['aanval'])){
            unset($_SESSION['evolueren']);
            if($levelen['level'] > $select['level']) break;
            $_SESSION['aanvalnieuw'] = base64_encode($select['id']."/".$levelen['aanval']);
            $count++;
            $_SESSION['lvl_old'] = $levelen['level'];
            array_push($_SESSION['used'], $select['id']);
            break;
          }
        }
        #Gaat de pokemon evolueren
        elseif($levelen['wat'] == "evo"){
          #The level is greater than or equal to the level that is required? To another page
          if(($levelen['level'] <= $select['level']) OR (($levelen['trade'] == 1) AND ($select['trade'] == "1.5"))){
            unset($_SESSION['aanvalnieuw']);
            if($levelen['level'] > $select['level']) break;
            $_SESSION['evolueren'] = base64_encode($select['id']."/".$levelen['nieuw_id']);
            $count++;
            $_SESSION['lvl_old'] = $levelen['level'];
            array_push($_SESSION['used'], $select['id']);
            break;
          }    
        }
      }
      if($count != 0) break;
    }
  }
  if($count == 0) unset($_SESSION['evolueren']);  
  
	#Event taal pack includen
	$eventlanguage = GetEventLanguage($gebruiker['land']);
	include('language/events/language-events-'.$eventlanguage.'.php');

	$event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower" /> '.$pokemon['naam'].' '.$txt['event_is_evolved_in'].' '.$update['naam'].'.';
	
	#Melding geven aan de uitdager
	mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
	VALUES (NULL, '".$date."', '".$_SESSION['id']."', '".$event."', '0')");
} 

#Als er op de stop knop gedrukt word
elseif(isset($_POST['stopevolutie'])){
  #stop trade evo
  mysql_query("UPDATE pokemon_speler SET trade=1 WHERE id = '".$pokemon['id']."'");
  
  $tekst = "<div class='blue'>".$pokemon['naam']." has stopped the evolution.</div>";
  $button = False;
  #Checken als meer pokemon moet evolueren
  $current = array_pop($_SESSION['used']);      
  
  $count = 0;
  $sql = mysql_query("SELECT pokemon_wild.naam, pokemon_speler.id, pokemon_speler.wild_id, pokemon_speler.roepnaam, pokemon_speler.level, pokemon_speler.trade, pokemon_speler.expnodig, pokemon_speler.exp FROM pokemon_wild INNER JOIN pokemon_speler ON pokemon_wild.wild_id = pokemon_speler.wild_id WHERE pokemon_speler.id='".$current."'");
  while($select = mysql_fetch_assoc($sql)){
    #Change name for male and female
    $select['naam_goed'] = pokemon_naam($select['naam'],$select['roepnaam']);
    if($select['level'] < 101){
      #Gegevens laden van pokemon die leven groeit uit levelen tabel
      $levelensql = mysql_query("SELECT `id`, `level`, `trade`, `wild_id`, `wat`, `nieuw_id`, `aanval` FROM `levelen` WHERE `wild_id`='".$select['wild_id']."' AND `level`>'".$_SESSION['lvl_old']."' ORDER BY id ASC");
      #Voor elke actie kijken als het klopt.
      while($levelen = mysql_fetch_assoc($levelensql)){
        #als de actie een aanval leren is
        if($levelen['wat'] == "att"){
          #Kent de pokemon deze aanval al
          if(($select['aanval_1'] != $levelen['aanval']) AND ($select['aanval_2'] != $levelen['aanval']) AND ($select['aanval_3'] != $levelen['aanval']) AND ($select['aanval_4'] != $levelen['aanval'])){
            unset($_SESSION['evolueren']);
            $_SESSION['aanvalnieuw'] = base64_encode($select['id']."/".$levelen['aanval']);
            $count++;
            $_SESSION['lvl_old'] = $levelen['level'];
            array_push($_SESSION['used'], $select['id']);
            break;
          }
        }
        #Does the pokemon evolve
        elseif($levelen['wat'] == "evo"){
          #The level is greater than or equal to the level that is required? To another page
          if(($levelen['level'] <= $select['level']) OR (($levelen['trade'] == 1) AND ($select['trade'] == "1.5"))){
            $_SESSION['evolueren'] = base64_encode($select['id']."/".$levelen['nieuw_id']);
            $count++;
            $_SESSION['lvl_old'] = $levelen['level'];
            array_push($_SESSION['used'], $select['id']);
            break;
          }    
        }
      }
      if($count != 0) break;
    }
  }
  if($count == 0) unset($_SESSION['evolueren']);  
}

#als er nergens opgedrukt is
else{
  $tekst = "<div class='blue'>".$pokemon['naam']." will evolve into ".$update['naam']."</div>";
  $button = True;
}
?>

<center>
    <table width="500" border="0">
      <tr>
        <td colspan="3"><center><? echo $tekst; ?></center></td>
      </tr>
      <tr>
        <td width="200" valign="top"><center><img src="images/<?php if($pokemon['shiny'] == 0) echo 'pokemon'; else echo 'shiny'; ?>/<? echo $pokemon['wild_id']; ?>.gif" /></center></td>
        <td width="86" valign="middle"><center><img src="images/icons/pijl_rechts.png" width="16" height="16" /></center></td>
        <td width="200" valign="top"><center><img src="images/<?php if($pokemon['shiny'] == 0) echo 'pokemon'; else echo 'shiny'; ?>/<? echo $update['wild_id']; ?>.gif" /></center></td>
      </tr>
      <tr>
        <td colspan="3">
        <center>
        <?
        if($button){
          ?>
          <form method="post">
            <input type="submit" name="acceptevolutie" value="Accept Evolution" class="button">
              - 
            <input type="submit" name="stopevolutie" value="Stop Evolution" class="button">
          </form>
          <?
        }
        ?>
        </center></td>
      </tr>
    </table>
  </center>
