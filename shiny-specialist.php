<?
#include dit script als je de pagina alleen kunt zien als je ingelogd bent.
include('includes/security.php');

#Als je geen pokemon bij je hebt, terug naar index.
if($gebruiker['in_hand'] == 0) header('Location: index.php');

$page = 'shiny-specialist';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

if(isset($_POST['shinyspecialist'])){
	$pokemoninfo = mysql_fetch_assoc(mysql_query("SELECT pokemon_speler.user_id, pokemon_speler.opzak, pokemon_speler.shiny, pokemon_speler.ei, pokemon_wild.zeldzaamheid
												  FROM pokemon_speler
												  INNER JOIN pokemon_wild
												  ON pokemon_speler.wild_id = pokemon_wild.wild_id
												  WHERE pokemon_speler.id = '".$_POST['pokemonid']."'"));
	
	#Hoeveel gold nodig?
	if(($pokemoninfo['ei'] == 1) OR ($pokemoninfo['shiny'] == 1)) $goldneed = '--';
	elseif($pokemoninfo['zeldzaamheid'] == 1) $goldneed = 25;
	elseif($pokemoninfo['zeldzaamheid'] == 2) $goldneed = 40;
	else $goldneed = 125;
	
	#Is er geen pokemon gekozen?
	if(empty($_POST['pokemonid'])) echo '<div class="red">'.$txt['alert_no_pokemon_selected'].'</div>';
	#Is de pokemon wel uit het ei?
	elseif($pokemoninfo['ei'] == 1) echo '<div class="red">'.$txt['alert_pokemon_is_egg'].'</div>';
	#Is de pokemon wel van jou?
	elseif($pokemoninfo['user_id'] != $_SESSION['id']) echo '<div class="red">'.$txt['alert_not_your_pokemon'].'</div>';
	#Is de pokemon al shiny?
	elseif($pokemoninfo['shiny'] == 1) echo '<div class="red">'.$txt['alert_already_shiny'].'</div>';
	#Heb je de pokemon wel bij je?
	elseif($pokemoninfo['opzak'] != 'ja') echo '<div class="red">'.$txt['alert_pokemon_not_in_hand'].'</div>';
	#Niet genoeg gold?
	elseif($gebruiker['gold'] < $goldneed) echo '<div class="red">'.$txt['alert_not_enough_gold'].'</div>';
	#Alles correct?
	else{
		mysql_query("UPDATE pokemon_speler SET shiny='1' WHERE id = '".$_POST['pokemonid']."' AND user_id = '".$_SESSION['id']."'");
		mysql_query("UPDATE gebruikers SET gold = gold-'".$goldneed."' WHERE user_id = '".$_SESSION['id']."'");
		echo '<div class="green">'.$txt['success'].'</div>';
	}
}
?>
<center><p><?php echo $txt['title_text']; ?></p></center>
  <center>
   <form method="post">
   <table width="200" cellpadding="0" cellspacing="0">
      <tr>
        <td width="50" class="top_first_td"><?php echo $txt['#']; ?></td>
        <td width="100" class="top_td">&nbsp;</td>
        <td width="50" class="top_td"><?php echo $txt['gold_need']; ?></td>
      </tr>
      <?php
      while($pokemon = mysql_fetch_assoc($pokemon_sql)){
        $pokemon = pokemonei($pokemon);
        $pokemon['naam'] = pokemon_naam($pokemon['naam'],$pokemon['roepnaam']);
		$popup = pokemon_popup($pokemon, $txt);
		
		#Hoeveel gold nodig?
		if(($pokemon['ei'] == 1) OR ($pokemon['shiny'] == 1)) $goldoutput = '--';
		elseif($pokemon['zeldzaamheid'] == 1) $goldoutput = 25;
		elseif($pokemon['zeldzaamheid'] == 2) $goldoutput = 40;
		else $goldoutput = 125;
		
        echo '<tr>';
          #Als pokemon een eitje is of al shiny:
          if(($pokemon['ei'] == 1) OR ($pokemon['shiny'] == 1)) echo '<td class="normal_first_td"><center><input type="radio" name="pokemonid" disabled /></center></td>';
		  #Anders:
          else echo '<td class="normal_first_td"><center><input type="radio" name="pokemonid" value="'.$pokemon['id'].'"/></center></td>';

        echo '<td class="normal_td"><center><a href="#" class="tooltip" onMouseover="showhint(\''.$popup.'\', this)"><img src="'.$pokemon['animatie'].'"></a></center></td>
          	  <td class="normal_td"><img src="images/icons/gold.png" title="Gold" style="margin-bottom:-3px;"> '.$goldoutput.'</td>
        	</tr>';
      }
	  mysql_data_seek($pokemon_sql, 0);
      ?>
      <tr>
        <td colspan="3"><button type="submit" name="shinyspecialist" class="button"  style="min-width: 200px;"><?php echo $txt['button']; ?></button></td>
      </tr>
</table>
</form>
</center>