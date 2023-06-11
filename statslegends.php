<?php
$page = 'statistics';
//Goeie taal erbij laden voor de page
include_once('language/language-pages.php');
?>
<center>

<a href="=?page=statslegends"><img src="img/ui-christmas/statistics2.png" /></a><a href="?page=statsnonlegends"><img src="img/ui-christmas/statistics3.png" /></a><a href="?page=statistics"><img src="img/ui-christmas/statistics1.png" /></a>

<tr><td>&nbsp;</td></tr>
		<tr>
        	<td style="text-align: center; vertical-align: center;"><h3>Top team <?=GLOBALDEF_SITENAME?><br /><span class="smalltext">Gebasseerd op alle stats.</span></h3></td>
        </tr>

<? # Top 6 sterkste pokemon # ?>
<span id="statics">
	<table width="660"  border="0">
        <tr>
        	<td style="padding-left:8px;"><center>
<?php
$top5pokemonsql = mysql_query("SELECT pokemon_speler.*, pokemon_wild.wild_id, pokemon_wild.naam, pokemon_wild.type1, pokemon_wild.type2, gebruikers.username,
							  SUM(`attack` + `defence` + `speed` + `spc.attack` + `spc.defence`) AS strongestpokemon 
							  FROM pokemon_speler
							  INNER JOIN pokemon_wild
							  ON pokemon_speler.wild_id = pokemon_wild.wild_id
							  INNER JOIN gebruikers
							  ON pokemon_speler.user_id = gebruikers.user_id
							  WHERE gebruikers.account_code = '1'AND admin = '0' AND (pokemon_wild.wild_id='653' OR pokemon_wild.wild_id='656' OR pokemon_wild.wild_id='639' OR pokemon_wild.wild_id='571' OR pokemon_wild.wild_id='570' OR pokemon_wild.wild_id='151' OR pokemon_wild.wild_id='636' OR pokemon_wild.wild_id='637' OR pokemon_wild.wild_id='638' OR pokemon_wild.wild_id='654' OR pokemon_wild.wild_id='652' OR pokemon_wild.wild_id='651' OR pokemon_wild.wild_id='650' OR pokemon_wild.wild_id='649' OR pokemon_wild.wild_id='648' OR pokemon_wild.wild_id='647' OR pokemon_wild.wild_id='646' OR pokemon_wild.wild_id='645' OR pokemon_wild.wild_id='644' OR pokemon_wild.wild_id='643' OR pokemon_wild.wild_id='642' OR pokemon_wild.wild_id='641' OR pokemon_wild.wild_id='640' OR pokemon_wild.wild_id='150' OR pokemon_wild.wild_id='146' OR pokemon_wild.wild_id='730' OR pokemon_wild.wild_id='145' OR pokemon_wild.wild_id='144' OR pokemon_wild.wild_id='377' OR pokemon_wild.wild_id='378' OR pokemon_wild.wild_id='386' OR pokemon_wild.wild_id='385' OR pokemon_wild.wild_id='384' OR pokemon_wild.wild_id='383' OR pokemon_wild.wild_id='382' OR pokemon_wild.wild_id='381' OR pokemon_wild.wild_id='380' OR pokemon_wild.wild_id='379' OR pokemon_wild.wild_id='655' OR pokemon_wild.wild_id='244' OR pokemon_wild.wild_id='493' OR pokemon_wild.wild_id='494' OR pokemon_wild.wild_id='492' OR pokemon_wild.wild_id='491' OR pokemon_wild.wild_id='482' OR pokemon_wild.wild_id='483' OR pokemon_wild.wild_id='484' OR pokemon_wild.wild_id='485' OR pokemon_wild.wild_id='486' OR pokemon_wild.wild_id='487' OR pokemon_wild.wild_id='488' OR pokemon_wild.wild_id='489' OR pokemon_wild.wild_id='490' OR pokemon_wild.wild_id='251' OR pokemon_wild.wild_id='250' OR pokemon_wild.wild_id='249' OR pokemon_wild.wild_id='725' OR pokemon_wild.wild_id='726' OR pokemon_wild.wild_id='727' OR pokemon_wild.wild_id='728' OR pokemon_wild.wild_id='481' OR pokemon_wild.wild_id='243' OR pokemon_wild.wild_id='245' OR pokemon_wild.wild_id='729' OR pokemon_wild.wild_id='658' OR pokemon_wild.wild_id='657' OR pokemon_wild.wild_id='479' OR pokemon_wild.wild_id='480')
							  GROUP BY pokemon_speler.id ORDER BY strongestpokemon DESC LIMIT 24");

while($pokemon = mysql_fetch_array($top5pokemonsql)){
	
	if($pokemon['shiny'] == 1) $type= 'shiny';
	else $type = 'pokemon';
	$wildid = $pokemon['wild_id'];
	$strongestpokemonnumber ++;
	$lowname = strtolower($pokemon['naam']);
	$eigenaar = $pokemon['username'];
	$statistics = 1;
    $pokemon = pokemonei($pokemon);
    $pokemon['naam'] = pokemon_naam($pokemon['naam'],$pokemon['roepnaam']);
    $popup = pokemon_popup($pokemon, $txt);
  echo '<div id="topteam"><a href="#" class="tooltip" onMouseover="showhint(\''.$popup.'\', this)"><img src="images/'.$type.'/'.$wildid.'.gif" /></a><a href="index.php?page=profile&player='.$eigenaar.'" class="smalltext">'.$eigenaar.'</a></div>';
}
?>
</center></td>
</tr>
</table>

<hr />

</table>
</center>
</span>