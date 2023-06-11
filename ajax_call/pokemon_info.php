<?
session_start();
date_default_timezone_set('Europe/Amsterdam');

include('../includes/config.php');
include('../includes/ingame.inc.php');

$page = 'information';
$_GET['category'] = 'pokemon-info';
//Goeie taal erbij laden voor de page
include_once('../language/language-pages.php');

if (isset($_GET['pokemon']) && $_GET['pokemon'] != $txt['choosepokemon']) {
    $pokemonSQL = $db->prepare("SELECT pokemon_wild.wild_id, naam, zeldzaamheid, type1, type2, gebied, wereld, COUNT(pokemon_speler.wild_id) AS hoeveelingame
                    FROM pokemon_wild
                    LEFT JOIN pokemon_speler
                    ON pokemon_wild.wild_id = pokemon_speler.wild_id
                    WHERE pokemon_wild.wild_id = :pokemon
                    GROUP BY pokemon_wild.wild_id");
    $pokemonSQL->bindParam(':pokemon', $_GET['pokemon'], PDO::PARAM_STR);
    $pokemonSQL->execute();
    $info = $pokemonSQL->fetch(PDO::FETCH_ASSOC);

    $levelensql = $db->prepare("SELECT * FROM levelen WHERE wild_id = :pokemon ORDER BY level ASC");
    $levelensql->bindParam(':pokemon', $_GET['pokemon'], PDO::PARAM_STR);
    $levelensql->execute();
    $aantallevelen = $levelensql->fetchColumn();

    if ($info['zeldzaamheid'] == 1) $zeldzaam = $txt['not_rare'];
    elseif ($info['zeldzaamheid'] == 2) $zeldzaam = $txt['a_bit_rare'];
    else $zeldzaam = $txt['very_rare'];

    if (empty($info['gebied'])) $gebied = $txt['not_a_favorite_place'];
    else $gebied = $info['gebied'] . ' ' . $txt['is_his_favorite_place'];

    $info['type1'] = strtolower($info['type1']);
    $info['type2'] = strtolower($info['type2']);

    if (empty($info['type2'])) $info['type'] = '<table><tr><td><div class="type ' . $info['type1'] . '">' . $info['type1'] . '</div></td></tr></table>';
    else $info['type'] = '<table><tr><td><div class="type ' . $info['type1'] . '">' . $info['type1'] . '</div></td><td> <div class="type ' . $info['type2'] . '">' . $info['type2'] . '</div></td></tr></table>';

    echo '
    <div style="padding-bottom: 20px;">
      <table width="400">
        <tr>
          <td width="200"><img src="images/pokemon/' . $info['wild_id'] . '.gif" alt="normal ' . $info['naam'] . '" title="' . $info['naam'] . '"><img src="images/shiny/' . $info['wild_id'] . '.gif" alt="Shiny ' . $info['naam'] . '" title="' . $info['naam'] . '"></td>
          <td width="200" valign="bottom"><div style="padding-left:8px;"># ' . $info['wild_id'] . '<br />
		  ' . $info['naam'] . '<br />
          ' . $info['type'] . '
          ' . $info['naam'] . ' ' . $txt['is'] . ' ' . $zeldzaam . '.<br />
          ' . $txt['lives_in'] . ' ' . $info['wereld'] . '.<br />
          ' . $gebied . '<br />
		  ' . $txt['how_much_1'] . ' ' . highamount($info['hoeveelingame']) . ' ' . $info['naam'] . 's ' . $txt['how_much_2'] . '</div>
          </td>
        </tr>
      </table>
    </div>

    <table width="400">
      <tr>
        <td colspan="2" style="padding-right:20px;"><center><h3>' . $txt['attack&evolution'] . '</h3></center></td>
      </tr>';

    if ($aantallevelen == 0) {
        echo '
			<tr>
				<td colspan="2">' . $txt['no_attack_or_evolve'] . '</td>
		 	</tr>';
    } elseif ($aantallevelen > 0) {
        echo '
  		<tr>
  			<td width="100"><strong>' . $txt['level'] . '</strong></td>
  			<td width="100"><strong>' . $txt['evolution'] . '</strong></td>
  		</tr>';

        while ($levelen = $levelensql->fetch(PDO::FETCH_ASSOC)) {
            if ($levelen['wat'] == 'att') {
                echo '
    			<tr>
    				<td>' . $levelen['level'] . '</td>
    				<td>' . $levelen['aanval'] . '</td>
    		 	</tr>';
            } else {
                $evolutieSQL = $db->query("SELECT wild_id, naam FROM pokemon_wild WHERE wild_id = '" . $levelen['nieuw_id'] . "'");
                $evolutie = $evolutieSQL->fetch(PDO::FETCH_ASSOC);
            }

            if ($levelen['wat'] == 'evo' && $levelen['level'] < 100) {
                echo '
          <tr>
          <td>' . $levelen['level'] . '</td>
          <td><img src="images/pokemon/icon/' . $evolutie['wild_id'] . '.gif" alt="' . $evolutie['naam'] . '" title="' . $evolutie['naam'] . '"></td>
          </tr>
        ';
            } elseif ($levelen['wat'] == 'evo' && $levelen['stone'] != '') {
                if (file_exists("../images/items/" . $levelen['stone'] . ".png")) {
                    $stone = "images/items/" . $levelen['stone'] . ".png";
                } else {
                    $stone = "images/megastones/" . strtolower($levelen['stone']) . ".png";
                }
                echo '
    			<tr>
    				<td><img src="' . $stone . '" alt="' . $levelen['stone'] . '" title="' . $levelen['stone'] . '"></td>
    				<td><img src="images/pokemon/icon/' . $evolutie['wild_id'] . '.gif" alt="' . $evolutie['naam'] . '" title="' . $evolutie['naam'] . '"></td>
    		 	</tr>';
            } elseif ($levelen['wat'] == 'evo' && $levelen['trade'] == 1) {
                echo '
    			<tr>
    				<td><img src="images/icons/trade.png" alt="Trade" title="Trade"></td>
    				<td><img src="images/pokemon/icon/' . $evolutie['wild_id'] . '.gif" alt="' . $evolutie['naam'] . '" title="' . $evolutie['naam'] . '"></td>
    		 	</tr>';
            }
        }
    }
    echo '
		<tr>
			<td colspan="2" align="center"><br/><a href="?page=catched&pokemon=' . $info['wild_id'] . '" class="button">Wie heeft hem gevangen?</a></td>
		</tr>';
    echo '</table>';
}