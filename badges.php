<?php
include('includes/security.php');

$page = 'badges';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

$badgeQuery = "SELECT * FROM gebruikers_badges WHERE user_id = :user_id";
$badge = $db->prepare($badgeQuery);
$badge->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_STR);
$badge->execute();
$badge = $badge->fetchAll(PDO::FETCH_ASSOC);

if ($gebruiker['Badge case'] == 0) {

    echo showAlert('red', $txt['alert_dont_have_badgebox']);
} else {

    echo '
  <center>
  <table width="600">
  	<tr>
    	<td><div id="badgebox"><strong>' . $txt['badges'] . ' Kanto</strong><br />';

    if ($badge['Boulder'] == 1) echo '<img src="images/badges/Boulder.png" width="40" height="40" alt="Boulder Badge" title="Boulder Badge" />';
    if ($badge['Cascade'] == 1) echo '<img src="images/badges/Cascade.png" width="40" height="40" alt="Cascade Badge" title="Cascade Badge" />';
    if ($badge['Thunder'] == 1) echo '<img src="images/badges/Thunder.png" width="40" height="40" alt="Thunder Badge" title="Thunder Badge" />';
    if ($badge['Rainbow'] == 1) echo '<img src="images/badges/Rainbow.png" width="40" height="40" alt="Rainbow Badge" title="Rainbow Badge" />';
    if ($badge['Marsh'] == 1) echo '<img src="images/badges/Marsh.png" width="40" height="40" alt="Marsh Badge" title="Marsh Badge" />';
    if ($badge['Soul'] == 1) echo '<img src="images/badges/Soul.png" width="40" height="40" alt="Soul Badge" title="Soul Badge" />';
    if ($badge['Volcano'] == 1) echo '<img src="images/badges/Volcano.png" width="40" height="40" alt="Volcano Badge" title="Volcano Badge" />';
    if ($badge['Earth'] == 1) echo '<img src="images/badges/Earth.png" width="40" height="40" alt="Earth Badge" title="Earth Badge" />';

    if ($badge['Boulder'] == 0 && $badge['Cascade'] == 0 && $badge['Thunder'] == 0 && $badge['Rainbow'] == 0 && $badge['Marsh'] == 0 && $badge['Soul'] == 0 && $badge['Volcano'] == 0 && $badge['Earth'] == 0) echo $txt['no_badges_from'] . ' Kanto';

    echo '</div>
			  <div id="badgebox"><strong>' . $txt['badges'] . ' Johto</strong><br />';

    if ($badge['Zephyr'] == 1) echo '<img src="images/badges/Zephyr.png" width="40" height="40" alt="Zephyr Badge" title="Zephyr Badge" />';
    if ($badge['Hive'] == 1) echo '<img src="images/badges/Hive.png" width="40" height="40" alt="Hive Badge" title="Hive Badge" />';
    if ($badge['Plain'] == 1) echo '<img src="images/badges/Plain.png" width="40" height="40" alt="Plain Badge" title="Plain Badge" />';
    if ($badge['Fog'] == 1) echo '<img src="images/badges/Fog.png" width="40" height="40" alt="Fog Badge" title="Fog Badge" />';
    if ($badge['Storm'] == 1) echo '<img src="images/badges/Storm.png" width="40" height="40" alt="Storm Badge" title="Storm Badge" />';
    if ($badge['Mineral'] == 1) echo '<img src="images/badges/Mineral.png" width="40" height="40" alt="Mineral Badge" title="Mineral Badge" />';
    if ($badge['Glacier'] == 1) echo '<img src="images/badges/Glacier.png" width="40" height="40" alt="Glacier Badge" title="Glacier Badge" />';
    if ($badge['Rising'] == 1) echo '<img src="images/badges/Rising.png" width="40" height="40" alt="Rising Badge" title="Rising Badge" />';

    if ($badge['Zephyr'] == 0 && $badge['Hive'] == 0 && $badge['Plain'] == 0 && $badge['Fog'] == 0 && $badge['Storm'] == 0 && $badge['Mineral'] == 0 && $badge['Glacier'] == 0 && $badge['Rising'] == 0) echo $txt['no_badges_from'] . ' Johto';

    echo '</div>
			  <div id="badgebox"><strong>' . $txt['badges'] . ' Hoenn</strong><br />';

    if ($badge['Stone'] == 1) echo '<img src="images/badges/Stone.png" width="40" height="40" alt="Stone Badge" title="Stone Badge" />';
    if ($badge['Knuckle'] == 1) echo '<img src="images/badges/Knuckle.png" width="40" height="40" alt="Knuckle Badge" title="Knuckle Badge" />';
    if ($badge['Dynamo'] == 1) echo '<img src="images/badges/Dynamo.png" width="40" height="40" alt="Dynamo Badge" title="Dynamo Badge" />';
    if ($badge['Heat'] == 1) echo '<img src="images/badges/Heat.png" width="40" height="40" alt="Heat Badge" title="Heat Badge" />';
    if ($badge['Balance'] == 1) echo '<img src="images/badges/Balance.png" width="40" height="40" alt="Balance Badge" title="Balance Badge" />';
    if ($badge['Feather'] == 1) echo '<img src="images/badges/Feather.png" width="40" height="40" alt="Feather Badge" title="Feather Badge" />';
    if ($badge['Mind'] == 1) echo '<img src="images/badges/Mind.png" width="40" height="40" alt="Mind Badge" title="Mind Badge" />';
    if ($badge['Rain'] == 1) echo '<img src="images/badges/Rain.png" width="40" height="40" alt="Rain Badge" title="Rain Badge" />';

    if ($badge['Stone'] == 0 && $badge['Knuckle'] == 0 && $badge['Dynamo'] == 0 && $badge['Heat'] == 0 && $badge['Balance'] == 0 && $badge['Feather'] == 0 && $badge['Mind'] == 0 && $badge['Rain'] == 0) echo $txt['no_badges_from'] . ' Hoenn';

    echo '</div>
			  <div id="badgebox"><strong>' . $txt['badges'] . ' Sinnoh</strong><br />';

    if ($badge['Coal'] == 1) echo '<img src="images/badges/Coal.png" width="40" height="40" alt="Coal Badge" title="Coal Badge" />';
    if ($badge['Forest'] == 1) echo '<img src="images/badges/Forest.png" width="40" height="40" alt="Forest Badge" title="Forest Badge" />';
    if ($badge['Cobble'] == 1) echo '<img src="images/badges/Cobble.png" width="40" height="40" alt="Cobble Badge" title="Cobble Badge" />';
    if ($badge['Fen'] == 1) echo '<img src="images/badges/Fen.png" width="40" height="40" alt="Fen Badge" title="Fen Badge" />';
    if ($badge['Relic'] == 1) echo '<img src="images/badges/Relic.png" width="40" height="40" alt="Relic Badge" title="Relic Badge" />';
    if ($badge['Mine'] == 1) echo '<img src="images/badges/Mine.png" width="40" height="40" alt="Mine Badge" title="Mine Badge" />';
    if ($badge['Icicle'] == 1) echo '<img src="images/badges/Icicle.png" width="40" height="40" alt="Icicle Badge" title="Icicle Badge" />';
    if ($badge['Beacon'] == 1) echo '<img src="images/badges/Beacon.png" width="40" height="40" alt="Beacon Badge" title="Beacon Badge" />';

    if ($badge['Coal'] == 0 && $badge['Forest'] == 0 && $badge['Cobble'] == 0 && $badge['Fen'] == 0 && $badge['Relic'] == 0 && $badge['Mine'] == 0 && $badge['Icicle'] == 0 && $badge['Beacon'] == 0) echo $txt['no_badges_from'] . ' Sinnoh';

    echo '</div>
			  <div id="badgebox"><strong>' . $txt['badges'] . ' Unova</strong><br />';

    if ($badge['Trio'] == 1) echo '<img src="images/badges/Trio.png" width="40" height="40" alt="Trio Badge" title="Trio Badge" />';
    if ($badge['Basic'] == 1) echo '<img src="images/badges/Basic.png" width="40" height="40" alt="Basic Badge" title="Basic Badge" />';
    if ($badge['Insect'] == 1) echo '<img src="images/badges/Insect.png" width="40" height="40" alt="Insect Badge" title="Insect Badge" />';
    if ($badge['Bolt'] == 1) echo '<img src="images/badges/Bolt.png" width="40" height="40" alt="Bolt Badge" title="Bolt Badge" />';
    if ($badge['Quake'] == 1) echo '<img src="images/badges/Quake.png" width="40" height="40" alt="Quake Badge" title="Quake Badge" />';
    if ($badge['Jet'] == 1) echo '<img src="images/badges/Jet.png" width="40" height="40" alt="Jet Badge" title="Jet Badge" />';
    if ($badge['Freeze'] == 1) echo '<img src="images/badges/Freeze.png" width="40" height="40" alt="Freeze Badge" title="Freeze Badge" />';
    if ($badge['Legend'] == 1) echo '<img src="images/badges/Legend.png" width="40" height="40" alt="Legend Badge" title="Legend Badge" />';

    if ($badge['Trio'] == 0 && $badge['Basic'] == 0 && $badge['Insect'] == 0 && $badge['Bolt'] == 0 && $badge['Quake'] == 0 && $badge['Jet'] == 0 && $badge['Freeze'] == 0 && $badge['Legend'] == 0) echo $txt['no_badges_from'] . ' Unova';

    echo '</div>
			  <div id="badgebox"><strong>' . $txt['badges'] . ' Kalos</strong><br />';

    if ($badge['Bug'] == 1) echo '<img src="images/badges/Bug.png" width="40" height="40" alt="Bug Badge" title="Bug Badge" />';
    if ($badge['Cliff'] == 1) echo '<img src="images/badges/Cliff.png" width="40" height="40" alt="Cliff Badge" title="Cliff Badge" />';
    if ($badge['Rumble'] == 1) echo '<img src="images/badges/Rumble.png" width="40" height="40" alt="Rumble Badge" title="Rumble Badge" />';
    if ($badge['Plant'] == 1) echo '<img src="images/badges/Plant.png" width="40" height="40" alt="Plant Badge" title="Plant Badge" />';
    if ($badge['Voltage'] == 1) echo '<img src="images/badges/Voltage.png" width="40" height="40" alt="Voltage Badge" title="Voltage Badge" />';
    if ($badge['Fairy'] == 1) echo '<img src="images/badges/Fairy.png" width="40" height="40" alt="Fairy Badge" title="Fairy Badge" />';
    if ($badge['Psychic'] == 1) echo '<img src="images/badges/Psychic.png" width="40" height="40" alt="Psychic Badge" title="Psychic Badge" />';
    if ($badge['Iceberg'] == 1) echo '<img src="images/badges/Iceberg.png" width="40" height="40" alt="Iceberg Badge" title="Iceberg Badge" />';

    if ($badge['Bug'] == 0 && $badge['Cliff'] == 0 && $badge['Rumble'] == 0 && $badge['Plant'] == 0 && $badge['Voltage'] == 0 && $badge['Fairy'] == 0 && $badge['Psychic'] == 0 && $badge['Iceberg'] == 0) echo $txt['no_badges_from'] . ' Kalos';

    echo '</div></td>
				  	</tr>
				</table>
				</center>';
}