<?
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

$page = 'pokedex';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

$have = explode(",", $gebruiker['pok_gezien']);
$array = count($have);

$zien = explode(",", $gebruiker['pok_bezit']);
$array2 = count($zien);

$totaal = mysql_num_rows(mysql_query("SELECT wild_id FROM pokemon_wild"));
if(isset($_GET['world'])) $world = $_GET['world'];
else $world = "Kanto";

?>
<script>
// When document is ready: this gets fired before body onload <img src='https://blogs.digitss.com/wp-includes/images/smilies/icon_smile.gif' alt=':)' class='wp-smiley' />
$(document).ready(function(){
	// Write on keyup event of keyword input element
	$("#kwd_search").keyup(function(){
		// When value of the input is not blank
		if( $(this).val() != "")
		{
			// Show only matching TR, hide rest of them
			$("#my-table tr").hide();
			$("#my-table td:contains-ci('" + $(this).val() + "')").parent("tr").show();
		}
		else
		{
			// When there is no input or clean again, show everything back
			$("#my-table tr").show();
		}
	});
});
// jQuery expression for case-insensitive filter
$.extend($.expr[":"], 
{
    "contains-ci": function(elem, i, match, array) 
	{
		return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
	}
});
</script>
<div >
  <h2>Pokedex</h2><hr>
  <tr>
      <td><div style="padding-bottom:20px;"><center><strong><a href="?page=pokedex&world=Kanto">Kanto</a></strong> - <strong><a href="?page=pokedex&world=Johto">Johto</a></strong> - <strong><a href="?page=pokedex&world=Hoenn">Hoenn</a></strong> - <strong><a href="?page=pokedex&world=Sinnoh">Sinnoh</a></strong> - <strong><a href="?page=pokedex&world=Unova">Unova</a></strong> - <strong><a href="?page=pokedex&world=Kalos">Kalos</a></strong></center></div></td>
    </tr>
  <div class="inhoud">

  <table style="padding: 5px;vertical-align: middle;border: 1px solid #000;">
  <tr>
	<td colspan="2">Totaal <b><?php echo $array; ?></b> Pokémon gezien van <b><?php echo $totaal; ?></b> en <b><?php echo $array2; ?></b> gevangen.</td>
  </tr>
  <tr>
  <td width="200px" style="padding: 5px;vertical-align: middle;border: 1px solid #000;">Pokémon</td>
  <td width="550px" style="padding: 5px;vertical-align: middle;border: 1px solid #000;">Informatie</td>
  </tr>
  
  <tr>
  <td>
  <div style="overflow:scroll; height: 640px;">
  <input type="text" id="kwd_search" value="" class="text_long"/>
	<table class="general" id="my-table">
  <?php
	$allpokemonsql = mysql_query("SELECT wild_id, naam FROM pokemon_wild WHERE wereld='".$world."' ORDER BY wild_id ASC");
							while($allpokemon = mysql_fetch_array($allpokemonsql)){
							$allpokemon['naam_goed'] = computer_naam($allpokemon['naam']);
							
	$bezit = explode(",", $gebruiker['pok_bezit']);
    if(in_array($allpokemon['wild_id'], $bezit)) {
		$naam = "<font color='green'>".$allpokemon['wild_id'].". ".$allpokemon['naam_goed']."</font>";
	} else {
		$naam = "".$allpokemon['wild_id'].". ".$allpokemon['naam_goed']."";
	}
	?>
	<tr>
	<td><a href="?page=pokedex&world=<? echo $world; ?>&pokemon=<?php echo $allpokemon['wild_id']; ?>"><?php echo $naam; ?></a></td>
	<td>
	<?php
		$have = explode(",", $gebruiker['pok_gezien']);
        if(in_array($allpokemon['wild_id'], $have)) {
        echo '<center><img src="./images/icons/pokeball.gif" width="14" height="14" alt="Gevangen" title="'.$txt['have_already'].' '.$allpokemon['naam_goed'].'" /></center>';
		} else {
		echo '<center><img src="./images/icons/pokeball_black.gif" width="14" height="14" alt="Niet gevangen" title="'.$txt['have_already'].' '.$allpokemon['naam_goed'].'" /></center>';
		}

		  ?>
	</td></tr>
	<?php
							}
  ?>
	</table>
  </div>
  </td>
  
  <td>
  <div style="overflow:scroll; height: 640px;">
  <?php
  if($_GET['pokemon'] == "") {
  $get = "460";
  } else {
  $get = $_GET['pokemon'];
  }
    $info = mysql_fetch_assoc(mysql_query("SELECT pokemon_wild.wild_id, naam, zeldzaamheid, type1, type2, gebied, wereld, COUNT(pokemon_speler.wild_id) AS hoeveelingame
										FROM pokemon_wild
										LEFT JOIN pokemon_speler
										ON pokemon_wild.wild_id = pokemon_speler.wild_id
										WHERE pokemon_wild.wild_id = '".$get."'
										GROUP BY pokemon_wild.wild_id"));
  $levelensql = mysql_query("SELECT * FROM levelen WHERE wild_id = '".$get."' ORDER BY level ASC");
  $aantallevelen = mysql_num_rows($levelensql);
  
	if($info['gebied'] == "Gras") $gebied2 = "Gras";
	elseif($info['gebied'] == "Lavagrot") $gebied2 = "lava";
	elseif($info['gebied'] == "Water") $gebied2 = "Water";
	elseif($info['gebied'] == "Grot") $gebied2 = "Grot";
	elseif($info['gebied'] == "Strand") $gebied2 = "Strand";
	elseif($info['gebied'] == "Vechtschool") $gebied2 = "Vechtschool";
	elseif($info['gebied'] == "Spookhuis") $gebied2 = "Spookhuis";

  if(empty($info['gebied'])) $gebied = "Er is geen favoriet";
  else $gebied = "".$gebied2." mijn favoriete plek";
  
  $info['type1'] = strtolower($info['type1']);
  $info['type2'] = strtolower($info['type2']);
  
  if($info['zeldzaamheid'] == 1) {
	$zeldzaam = "Gyakori Pokemon";
  } elseif($info['zeldzaamheid'] == 2) {
	$zeldzaam = "Kicsit Ritka Pokemon";
  } elseif($info['zeldzaamheid'] == 3) {
	$zeldzaam = "Nagyon ritka Pokemon";
  }
  if(empty($info['type2'])) $info['type'] = '<table><tr><td><div class="type '.$info['type1'].'">'.$info['type1'].'</div></td></tr></table>';
  else $info['type'] = '<table><tr><td><div class="type '.$info['type1'].'">'.$info['type1'].'</div></td><td> <div class="type '.$info['type2'].'">'.$info['type2'].'</div></td></tr></table>';

  echo'
      <table class="general">
        <tr>
		<td width="50%"><p><b>Normaal</b></p></td>
		<td width="50%"><p><b>Shiny</b></p></td>
		</tr>
		<tr>
          <td width="50%" align="center"><img src="images/pokemon/'.$info['wild_id'].'.gif" alt="normal '.$info['naam'].'" title="'.$info['naam'].'"></td>
		  <td width="50%" align="center"><img src="images/shiny/'.$info['wild_id'].'.gif" alt="Shiny '.$info['naam'].'" title="'.$info['naam'].'"></td>
        </tr>
      </table>';
	  
	  echo '
	  <table class="general">
		<tr>
			<td width="50%"><b>Naam</b></td>
			<td width="50%">'.$info['naam'].'</td>
		</tr>
		<tr>
			<td width="50%"><b>Zeldzaam</b></td>
			<td width="50%">'.$zeldzaam.'</td>
		</tr>
		<tr>
			<td width="50%"><b>Regio</b></td>
			<td width="50%">'.$info['wereld'].'</td>
		</tr>
		<tr>
			<td width="50%"><b>Type</b></td>
			<td width="50%">'.$info['type'].'</td>
		</tr>
		<tr>
			<td width="50%"><b>Gebied</b></td>
			<td width="50%">'.$gebied.'</td>
		</tr>
	  </table>
    <table class="general">';

	if($aantallevelen == 0){
		echo'
			<tr>
				<td colspan="2"><p><b>'.$txt['no_attack_or_evolve'].'</b></p></td>
		 	</tr>';
	}
	elseif($aantallevelen > 0){
		echo'
  		<tr>
  			<td width="100"><p><b>Level</b></p></td>
  			<td width="100"><p><b>Aanval</b></p></td>
  		</tr>';			

  	while($levelen = mysql_fetch_assoc($levelensql)){
    	if($levelen['wat'] == 'att'){
    		echo'
    			<tr>
    				<td><p>'.$levelen['level'].'</p></td>
    				<td><p>'.$levelen['aanval'].'</p></td>
    		 	</tr>';
    	}
      else{
        $evolutie = mysql_fetch_assoc(mysql_query("SELECT wild_id, naam FROM pokemon_wild WHERE wild_id = '".$levelen['nieuw_id']."'"));
      }
      
    	if($levelen['wat'] == 'evo' && $levelen['level'] < 100){
        echo'
          <tr>
          	<td><p>'.$levelen['level'].'</p></td>
          	<td><p><b>Evolutie:</b> <a href="?page=pokedex&pokemon='.$evolutie['wild_id'].'">'.$evolutie['naam'].'</a></p></td>
          </tr>
        ';
    	}
    	elseif($levelen['wat'] == 'evo' && $levelen['stone'] != ''){
    		echo'
    			<tr>
    				<td><p><img src="images/items/'.$levelen['stone'].'.png" alt="'.$levelen['stone'].'" title="'.$levelen['stone'].'"></p></td>
    				<td><p><b>Evolutie:</b> <a href="?page=pokedex&pokemon='.$evolutie['wild_id'].'">'.$evolutie['naam'].'</a></p></td>
    		 	</tr>';
    	}
    	elseif($levelen['wat'] == 'evo' && $levelen['trade'] == 1){
    		echo'
    			<tr>
    				<td><p><img src="images/icons/trade.png" alt="Trade" title="Trade"></p></td>
    				<td><p><b>Evolutie:</b> <a href="?page=pokedex&pokemon='.$evolutie['wild_id'].'">'.$evolutie['naam'].'</a></p></td>
    		 	</tr>';
    	}
  	}
	}
  echo '</table>';
  ?>
  </div>
  </td>
  </tr>
  </table>
  
  </div></div>