<?php
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");
?>
	  <center>
      Als je Pokémon een bepaald level bereikt kan hij nieuwe aanvallen leren.<br />
      Klik op een van zijn oude aanvallen om deze te vervangen.<br /><br />
      Als je de aanval liever niet leert klik dan op de knop [Annuleren].<br /><br />
      <small>Als je minder dan 4 aanvallen hebt leert je Pokemon automatisch de nieuwe aanval.</small><br />
      <br />

        <?
          #Gegevens laden van de des betreffende pokemon
          $pokemoninfo  = mysql_fetch_assoc(mysql_query("SELECT pokemon_wild.wild_id, pokemon_wild.naam, pokemon_speler.id, pokemon_speler.aanval_1, pokemon_speler.aanval_2, pokemon_speler.aanval_3, pokemon_speler.aanval_4 FROM pokemon_wild INNER JOIN pokemon_speler ON pokemon_wild.wild_id = pokemon_speler.wild_id WHERE `id`='".$nieuweaanval['pokemonid']."'"));
          $finish = False;

          if(isset($_POST['annuleer'])){
            echo "<div class='blue'><img src='images/icons/blue.png'>Je Pokémon heeft ".$nieuweaanval['aanvalnaam']." niet geleerd.</div>";
            $finish = true;
          }
          if(isset($_POST['attack'])){
            echo "<div class='green'><img src='images/icons/green.png'>Je Pokémon heeft ".$nieuweaanval['aanvalnaam']." geleerd en is ".$_POST['attack']." vergeten.</div>";
            
            #Nieuwe aanval opslaan
            mysql_query("UPDATE `pokemon_speler` SET `".$_POST['welke']."`='".$nieuweaanval['aanvalnaam']."' WHERE `id`='".$nieuweaanval['pokemonid']."'");
            $pokemoninfo[$_POST['welke']] = $nieuweaanval['aanvalnaam'];
            $finish = true;
          }

          if($finish){
            $current = array_pop($_SESSION['used']);      

            $count = 0;
            $sql = mysql_query("SELECT pokemon_wild.naam, pokemon_speler.id, pokemon_speler.wild_id, pokemon_speler.roepnaam, pokemon_speler.level, pokemon_speler.trade, pokemon_speler.expnodig, pokemon_speler.exp FROM pokemon_wild INNER JOIN pokemon_speler ON pokemon_wild.wild_id = pokemon_speler.wild_id WHERE pokemon_speler.id='".$current."'");
            while($select = mysql_fetch_assoc($sql)){
              #Change name for male and female
              $select['naam_goed'] = pokemon_naam($select['naam'],$select['roepnaam']);
              if($select['level'] < 100){
                #Gegevens laden van pokemon die leven groeit uit levelen tabel
                $levelensql = mysql_query("SELECT `id`, `level`, `trade`, `wild_id`, `wat`, `nieuw_id`, `aanval` FROM `levelen` WHERE `wild_id`='".$select['wild_id']."' AND `level`>'".$_SESSION['lvl_old']."' AND `level`<='".$select['level']."' AND aanval!='".$nieuweaanval['aanvalnaam']."' ORDER BY id ASC");
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
                    #Is het level groter of gelijk aan de level die benodigd is? Naar andere pagina gaan
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
            if($count == 0) unset($_SESSION['aanvalnieuw']);  
          }
        ?>
        <table width="500" border="0">
        <tr>
          <td width="130" height="120" rowspan="4"><img src="images/pokemon/<? echo $pokemoninfo['wild_id']; ?>.gif" /></td>
          <td colspan="2">Je <? echo $pokemoninfo['naam']; ?> probeert <strong><? echo $nieuweaanval['aanvalnaam']; ?> <br /></strong> te leren.
          Wil je <strong><? echo $nieuweaanval['aanvalnaam']; ?> leren?<br /><br /></td>
        </tr>
        <?
        echo '<tr>
          	  	<form method="post">
					<td width="178"><input type="submit" name="attack" value="'.$pokemoninfo['aanval_1'].'" class="button"></td>
					<input type="hidden" name="welke" value="aanval_1">
          		</form>
          		<form method="post">
            		<td width="178"><input type="submit" name="attack" value="'.$pokemoninfo['aanval_2'].'" class="button"></td>
            		<input type="hidden" name="welke" value="aanval_2">
          		</form>
        	</tr>
        	<tr>
          		<form method="post">
            		<td><input type="submit" name="attack" value="'.$pokemoninfo['aanval_3'].'" class="button"></td>
            		<input type="hidden" name="welke" value="aanval_3">
         		</form>
          		<form method="post">
            		<td><input type="submit" name="attack" value="'.$pokemoninfo['aanval_4'].'" class="button"></td>
            		<input type="hidden" name="welke" value="aanval_4">
         		</form>
       	 	</tr>';
        ?> 
        <tr>
            <td colspan="2"><form method="post"><input type="submit" name="annuleer" value="Annuleren" class="button"></form></td>
        </tr>
      </table>
    </center>       