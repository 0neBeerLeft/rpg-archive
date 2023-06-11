<?
#include dit script als je de pagina alleen kunt zien als je ingelogd bent.
include('includes/security.php');

$page = 'extended';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

#Als je geen pokemon bij je hebt stoppen we de pagina
if ($gebruiker['in_hand'] == 0) {
    echo showAlert('red', $txt['alert_no_pokemon_in_hand']);
    return false;
}

?>

<center>
    <?php
    #Pokemons opzak weergeven op het scherm
    foreach ($pokemon_all as $pokemon) {
        #Gegevens juist laden voor de pokemon
        $pokemon = pokemonei($pokemon);
        #Naam veranderen als het male of female is.
        $pokemon['naam'] = pokemon_naam($pokemon['naam'], $pokemon['roepnaam']);

        #Default no shiny
        $shinyimg = 'pokemon';
        $shinystar = '';
        $shinytext = '';
        #No color
        $aanval2type = "";
        $aanval3type = "";
        $aanval4type = "";

        if ($pokemon['shiny'] == 1) {
            $shinyimg = 'shiny';
            $shinystar = '<img src="images/icons/lidbetaald.png" width="16" height="16" style="margin-bottom:-3px;" border="0" alt="Shiny" title="Shiny">';
        }

        #Ff geheckt raken fixen
        if ($pokemon['gehecht'] == 1) $gehecht = '<img src="images/icons/friend.png" width="16" height="16" alt="' . $txt['begin_pokemon'] . '" title="' . $txt['begin_pokemon'] . '">';
        else $gehecht = '';

        #Aanval fixen
        if ($pokemon['aanval_2'] == '') $aanval2 = '';
        else $aanval2 = ' | ' . $pokemon['aanval_2'];
        if ($pokemon['aanval_3'] == '') $aanval3 = '';
        else $aanval3 = ' | ' . $pokemon['aanval_3'];
        if ($pokemon['aanval_4'] == '') $aanval4 = '';
        else $aanval4 = ' | ' . $pokemon['aanval_4'];

        if ($pokemon['ei'] != 1) {
            echo '
            <table width="610" class="border_black" cellspacing="0" cellpadding="0">
              <tr>
                <td width="130"><img src="images/items/' . $pokemon['gevongenmet'] . '.png" alt="' . $txt['catched_with'] . ' ' . $pokemon['gevongenmet'] . '." title="' . $txt['catched_with'] . ' ' . $pokemon['gevongenmet'] . '." /></td>
                <td width="120"><strong>' . $txt['pokemon'] . '</strong></td>
                <td width="110" style="padding-left: 2px;">' . $pokemon['def_naam'] . $shinystar . '</td>
                <td width="80"><strong>' . $txt['attack_points'] . '</strong></td>
                <td width="50">' . $pokemon['attack'] . '</td>
              </tr>
              <tr>
                <td width="130" rowspan="11"><center>' . $gehecht . '<br /><img src="' . $pokemon['link'] . '" alt="' . $pokemon['def_naam'] . '" title="' . $pokemon['def_naam'] . '"/></center></td>
                <td><strong>' . $txt['clamour_name'] . '</strong></td>
                <td style="padding-left: 2px;">' . $pokemon['naam'] . '</td>
                <td><strong>' . $txt['defence_points'] . '</strong></td>
                <td>' . $pokemon['defence'] . '</td>
              </tr>
              <tr>
                <td><strong>' . $txt['type'] . '</strong></td>
                <td>' . htmlspecialchars_decode($pokemon['type']) . '</td>
                <td><strong>' . $txt['speed_points'] . '</strong></td>
                <td>' . $pokemon['speed'] . '</td>
              </tr>
              <tr>
                <td><strong>' . $txt['level'] . '</strong></td>
                <td style="padding-left: 2px;">' . $pokemon['level'] . '</td>
                <td><strong>' . $txt['spc_attack_points'] . '</strong></td>
                <td>' . $pokemon['spcattack'] . '</td>
              </tr>
              <tr>
              </tr>
              <td><strong>' . $txt['mood'] . '</strong></td>
            	<td style="padding-left: 2px;">' . $pokemon['karakter'] . '</td>
            	<td><strong>' . $txt['spc_defence_points'] . '</strong></td>
              <td>' . $pokemon['spcdefence'] . '</td>
              </tr>
			  <tr>
			  	<td style="padding-top:10px;"><strong>' . $txt['attacks'] . '</strong></td>
				<td style="padding-top:10px;" colspan="3">' . $pokemon['aanval_1'] . $aanval2 . $aanval3 . $aanval4 . '</td>
			  </tr>
              <tr>
            	<td colspan="4">
              <table width="480" style="padding-top:10px;" cellspacing="0" cellpadding="0">
              	<tr>
              	  <td>	<table width="480">
				  			<tr>
								<td width="260"><div class="bar_red">
  						  							<div class="progress" style="width: ' . $pokemon['levenprocent'] . '%"></div>
  					  							</div></td>
								<td width="220" class="smalltext">' . $pokemon['leven'] . ' / ' . $pokemon['levenmax'] . '</td>
							</tr>
						</table>
				  </td>
                </tr>
                <tr>
                  <td><table width="480">
				  			<tr>
								<td width="260"><div class="bar_blue">
  						  				<div class="progress" style="width: ' . $pokemon['expprocent'] . '%"></div>
  					  				</div></td>
								<td width="220" class="smalltext">' . $pokemon['exp'] . ' / ' . $pokemon['expnodig'] . '</td>
							</tr>
						</table></td>
                </tr>
              </table>
              </tr>
            </table>
            <br />';
        } else {
            echo '
            <table width="610" class="border_black" cellspacing="0" cellpadding="0">
              <tr>
                <td width="120"><center><img src="' . $pokemon['link'] . '" /></center></td>
                <td width="490">' . $txt['egg_will_hatch_in'] . ' ' . $pokemon['afteltijd'] . '</td>
              </tr>
            </table>
			<br />
          ';
        }
    }
    ?>
</center>