<?php
$page = 'statistics';
//Goeie taal erbij laden voor de page
include_once('language/language-pages.php');
?>
<center>

<a href="?page=statslegends"><img src="img/ui-christmas/statistics2.png" /></a><a href="?page=statsnonlegends"><img src="img/ui-christmas/statistics3.png" /></a><a href="?page=statistics"><img src="img/ui-christmas/statistics1.png" /></a>

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
							  WHERE gebruikers.account_code = '1'
							  GROUP BY pokemon_speler.id ORDER BY strongestpokemon DESC LIMIT 12");

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
}
?>
</center></td>
</tr>
</table>

<hr />

<?php # Top stats #

$total = mysql_fetch_array(mysql_query("SELECT SUM(silver + bank) AS rubytotal,
										SUM(gewonnen + verloren) AS matchestotal,
										SUM(aantalpokemon) AS pokemontotal,
										COUNT(user_id) AS userstotal 
										FROM gebruikers WHERE account_code = '1'"));
										
$total['userstotal'] = number_format(round($total['userstotal']),0,",",".");
$total['rubytotal'] = number_format(round($total['rubytotal']),0,",",".");
$total['pokemontotal'] = number_format(round($total['pokemontotal']),0,",",".");
$total['matchestotal'] = number_format(round($total['matchestotal']),0,",",".");
?>
	<table width="250" border="0">
		<tr>
        	<td colspan="2"><center><h3><?php echo $txt['game_data']; ?></h3></center></td>
        </tr>
        <tr>
        	<td><?php echo $txt['users_total']; ?></td>
            <td><img src="images/icons/lid.png" style="margin-bottom:-3px;" /> <strong><?php echo $total['userstotal']; ?></strong></td>
        </tr>
        <tr>
        	<td><?php echo $txt['silver_in_game']; ?></td>
            <td><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> <strong><?php echo $total['rubytotal']; ?></strong></td>
        </tr>
        <tr>
        	<td><?php echo $txt['pokemon_total']; ?></td>
            <td><img src="images/icons/ball.gif" style="margin-bottom:-3px;" /> <strong><?php echo $total['pokemontotal']; ?></strong></td>
        </tr>
        <tr>
        	<td><?php echo $txt['matches_played']; ?></td>
            <td><img src="images/icons/tegenstander.png" style="margin-bottom:-3px;" /> <strong><?php echo $total['matchestotal']; ?></strong></td>
        </tr>
    </table>

<hr />

<? # Top 5 silver # ?>

	<table width="320" border="0">
		<tr>
        	<td colspan="3"><center><h3><?php echo $txt['top5_silver_users']; ?></h3></center></td>
        </tr>
        <tr>
        	<td width="50"><strong><?php echo $txt['#']; ?></strong></td>
            <td width="120"><strong><?php echo $txt['who']; ?></strong></td>
            <td width="150"><strong><?php echo $txt['silver']; ?></strong></td>
        </tr>
<?php
$top5rubysql = mysql_query("SELECT username, premiumaccount, SUM(silver + bank) AS totaal FROM gebruikers WHERE account_code='1' GROUP BY user_id ORDER BY totaal DESC LIMIT 5");

while($top5ruby = mysql_fetch_array($top5rubysql)){
	
	if($top5ruby['premiumaccount'] == 0) $star = '';
	else $star = '<img src="images/icons/lidbetaald.png" width="16" height="16" border="0" alt="Premiumlid" title="Premiumlid" style="margin-bottom:-3px;">';
	$top5ruby['totaal'] = number_format(round($top5ruby['totaal']),0,",",".");
	$number ++;
	
  echo '<tr>
  			<td>'.$number.'.</td>
			<td><a href="index.php?page=profile&player='.$top5ruby['username'].'">'.$top5ruby['username'].''.$star.'</a></td>
			<td><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> '.$top5ruby['totaal'].'</td>
		</tr>';
}
?>
</table>

<hr />

<? # Top 5 aantal pokemon # ?>

	<table width="320" border="0">
		<tr>
        	<td colspan="3"><center><h3><?php echo $txt['top5_pokemon_total']; ?></h3></center></td>
        </tr>
        <tr>
        	<td width="50"><strong><?php echo $txt['#']; ?></strong></td>
            <td width="120"><strong><?php echo $txt['who']; ?></strong></td>
            <td width="150"><strong><?php echo $txt['number']; ?></strong></td>
        </tr>
<?php
$top5aantalpokemonsql = mysql_query("SELECT username, premiumaccount, aantalpokemon FROM gebruikers WHERE account_code='1' ORDER BY aantalpokemon DESC LIMIT 5");

while($top5aantalpokemon = mysql_fetch_array($top5aantalpokemonsql)){
	
	if($top5aantalpokemon['premiumaccount'] == 0) $star = '';
	else $star = '<img src="images/icons/lidbetaald.png" width="16" height="16" border="0" alt="Premiumlid" title="Premiumlid" style="margin-bottom:-3px;">';
	$numberap ++;
	
  echo '<tr>
  			<td>'.$numberap.'.</td>
			<td><a href="index.php?page=profile&player='.$top5aantalpokemon['username'].'">'.$top5aantalpokemon['username'].''.$star.'</a></td>
			<td><img src="images/icons/ball.gif" style="margin-bottom:-3px;" /> '.$top5aantalpokemon['aantalpokemon'].'</td>
		</tr>';
}
?>
</table>

<hr />

<? # Top 5 gevechten # ?>

	<table width="320" border="0">
		<tr>
        	<td colspan="3"><center><h3><?php echo $txt['top5_matches_played']; ?></h3></center></td>
        </tr>
        <tr>
        	<td width="50"><strong><?php echo $txt['#']; ?></strong></td>
            <td width="120"><strong><?php echo $txt['who']; ?></strong></td>
            <td width="150"><strong><?php echo $txt['matches']; ?></strong></td>
        </tr>
<?php
$top5fightssql = mysql_query("SELECT username, premiumaccount, SUM(gewonnen - verloren) AS gevechten FROM gebruikers WHERE account_code='1' GROUP BY user_id ORDER BY gevechten DESC LIMIT 5");

while($top5fights = mysql_fetch_array($top5fightssql)){
	
	if($top5fights['premiumaccount'] == 0) $star = '';
	else $star = '<img src="images/icons/lidbetaald.png" width="16" height="16" border="0" alt="Premiumlid" title="Premiumlid" style="margin-bottom:-3px;">';
	$top5fights['gevechten'] = number_format(round($top5fights['gevechten']),0,",",".");
	$numberfights ++;
	
  echo '<tr>
  			<td>'.$numberfights.'.</td>
			<td><a href="index.php?page=profile&player='.$top5fights['username'].'">'.$top5fights['username'].''.$star.'</a></td>
			<td><img src="images/icons/tegenstander.png" style="margin-bottom:-3px;" /> '.$top5fights['gevechten'].'</td>
		</tr>';
}
?>
</table>

<hr />

<?php # TOP 10 nieuwste leden # ?>

	<table width="320" border="0">
		<tr>
        	<td colspan="3"><center><h3><?php echo $txt['top10_new_users']; ?></h3></center></td>
        </tr>
        <tr>
        	<td width="50"><strong><?php echo $txt['#']; ?></strong></td>
            <td width="120"><strong><?php echo $txt['who']; ?></strong></td>
            <td width="150"><strong><?php echo $txt['when']; ?></strong></td>
        </tr>
<?php
$top10newsql = mysql_query("SELECT username, premiumaccount, aanmeld_datum FROM gebruikers WHERE account_code='1' ORDER BY aanmeld_datum DESC LIMIT 0,10");

while($ledennieuw = mysql_fetch_array($top10newsql)){
	
	if($ledennieuw['premiumaccount'] == 0) $star = '';
	else $star = '<img src="images/icons/lidbetaald.png" width="16" height="16" border="0" alt="Premiumlid" title="Premiumlid" style="margin-bottom:-3px;">';
	$j++;
	
  echo '<tr>
  			<td>'.$j.'.</td>
			<td><img src="images/icons/lid.png" style="margin-bottom:-3px;" /> <a href="index.php?page=profile&player='.$ledennieuw['username'].'">'.$ledennieuw['username'].''.$star.'</a></td>
			<td>'.$ledennieuw['aanmeld_datum'].'</td>
		</tr>';
}
?>
</table>
</center>
</span>
