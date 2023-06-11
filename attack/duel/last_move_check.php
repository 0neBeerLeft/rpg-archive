<?
if ((isset($_GET['duel_id'])) AND (isset($_GET['sid']))) {
    //Session On
    session_start();
    //Connect With Database
    include_once("../../includes/config.php");
    //Include Default Functions
    include_once("../../includes/ingame.inc.php");

    //include global definitions
    chdir('../../');
    include_once('includes/globaldefs.php');
    chdir('attack/duel/');

    //Include Duel Functions
    include_once("duel.inc.php");
    //Include Attack Functions
    include_once("../../attack/attack.inc.php");
    //Load language
    $page = 'attack/duel/duel-attack';
    //Goeie taal erbij laden voor de page
    include_once('../../language/language-pages.php');
    //Load Duel Data
    $duel_sql = mysql_query("SElECT `id`, `uitdager`, `tegenstander`, `u_pokemonid`, `t_pokemonid`, `laatste_beurt_tijd`, `laatste_beurt`, `laatste_aanval`, `schade`, `volgende_beurt`, `last_pokemon_id` FROM `duel` WHERE `id`='" . $_GET['duel_id'] . "'");

    //Default text
    $refresh = 0;
    $info1 = "";
    $info2 = "";
    $info3 = "";
    $info4 = "";
    $info5 = "";
    $info6 = "";
    $info7 = "";

    //If there is no duel
    if (mysql_num_rows($duel_sql) == 1) {
        $duel_info = mysql_fetch_array($duel_sql);
        $time_left = strtotime(date("Y-m-d H:i:s")) - $duel_info['laatste_beurt_tijd'];
        if ($time_left > 61) {
            if ($duel_info['uitdager'] == $duel_info['volgende_beurt']) $winner = $duel_info['tegenstander'];
            elseif ($duel_info['tegenstander'] == $duel_info['volgende_beurt']) $winner = $duel_info['uitdager'];
            $mes = $txt['opponent_too_late'];
            mysql_query("UPDATE `duel` SET `winner`='" . $winner . "' WHERE `id`='" . $duel_info['id'] . "'");
            $refresh = 2;
        } else {
            if ($duel_info['uitdager'] == $_SESSION['naam']) {
                if ($duel_info['laatste_beurt'] == $duel_info['tegenstander']) {
                    if ($duel_info['laatste_aanval'] == "wissel") {
                        $new_pok = mysql_fetch_array(mysql_query("SELECT pw.wild_id, pw.naam, ps.roepnaam, ps.shiny, ps.level as pokemonlevel, psg.levenmax, psg.leven 
FROM pokemon_wild AS pw
 INNER JOIN pokemon_speler AS ps ON pw.wild_id = ps.wild_id 
 INNER JOIN pokemon_speler_gevecht AS psg ON psg.id = ps.id 
 WHERE psg.id='" . $duel_info['t_pokemonid'] . "'"));
                        $info1 = $new_pok['wild_id'];
                        $info2 = $pokemon_info['naam_goed'] = pokemon_naam($new_pok['naam'], $new_pok['roepnaam']);
                        $info3 = $new_pok['shiny'];
                        $info4 = $new_pok['leven'];
                        $info5 = $new_pok['levenmax'];
                        $info6 = $duel_info['last_pokemon_id'];
                        $info7 = $new_pok['pokemonlevel'];
                        if ($duel_info['volgende_beurt'] == $duel_info['uitdager']) {
                            $refresh = 1;
                            $mes = $duel_info['tegenstander'] . " " . $txt['opponent_have_changed_you_attack'];
                        } else {
                            $refresh = 3;
                            $mes = $duel_info['tegenstander'] . " " . $txt['you_have_changed_opponent_attack'];
                        }
                    } elseif ($duel_info['volgende_beurt'] == $_SESSION['naam']) {
                        $pokemon_info = pokemon_data($duel_info['u_pokemonid']);
                        $pokemon_info['naam_goed'] = pokemon_naam($pokemon_info['naam'], $pokemon_info['roepnaam']);
                        $opponent_info = pokemon_data($duel_info['t_pokemonid']);
                        $opponent_info['naam_goed'] = pokemon_naam($opponent_info['naam'], $opponent_info['roepnaam']);
                        $refresh = 1;
                        $info1 = $pokemon_info['leven'];
                        $info2 = $pokemon_info['levenmax'];
                        $info3 = $pokemon_info['naam_goed'];
                        $info4 = $pokemon_info['opzak_nummer'];
                        if ($pokemon_info['leven'] == 0) {
                            $mes = $opponent_info['naam_goed'] . " " . $txt['did'] . " " . $duel_info['laatste_aanval'] . $txt['you_have_to_change'];
                            $refresh = 4;
                        } else {
                            $mes = $opponent_info['naam_goed'] . " " . $txt['did'] . " " . $duel_info['laatste_aanval'] . ". " . $txt['your_turn'];
                            $refresh = 1;
                        }
                    } elseif ($duel_info['volgende_beurt'] == "end_screen") {
                        $pokemon_info = pokemon_data($duel_info['u_pokemonid']);
                        $pokemon_info['naam_goed'] = pokemon_naam($pokemon_info['naam'], $pokemon_info['roepnaam']);
                        $opponent_info = pokemon_data($duel_info['t_pokemonid']);
                        $opponent_info['naam_goed'] = pokemon_naam($opponent_info['naam'], $opponent_info['roepnaam']);
                        $refresh = 1;
                        $info1 = $pokemon_info['leven'];
                        $info2 = $pokemon_info['levenmax'];
                        $refresh = 5;
                        $mes = $opponent_info['naam_goed'] . " " . $txt['did'] . " " . $duel_info['laatste_aanval'] . $txt['youre_defeated'];
                    } else {
                        $mes = "Error: 1101<br />Info: " . $duel_info['volgende_beurt'];
                    }
                }
            } elseif ($duel_info['tegenstander'] == $_SESSION['naam']) {
                if ($duel_info['laatste_beurt'] == $duel_info['uitdager']) {
                    if ($duel_info['laatste_aanval'] == "wissel") {
                        $new_pok = mysql_fetch_array(mysql_query("SELECT pw.wild_id, pw.naam, ps.roepnaam, ps.shiny, ps.level as pokemonlevel, psg.levenmax, psg.leven 
FROM pokemon_wild AS pw 
INNER JOIN pokemon_speler AS ps ON pw.wild_id = ps.wild_id 
INNER JOIN pokemon_speler_gevecht AS psg ON psg.id = ps.id 
WHERE psg.id='" . $duel_info['u_pokemonid'] . "'"));
                        $info1 = $new_pok['wild_id'];
                        $info2 = $pokemon_info['naam_goed'] = pokemon_naam($new_pok['naam'], $new_pok['roepnaam']);
                        $info3 = $new_pok['shiny'];
                        $info4 = $new_pok['leven'];
                        $info5 = $new_pok['levenmax'];
                        $info6 = $duel_info['last_pokemon_id'];
                        $info7 = $new_pok['pokemonlevel'];
                        if ($duel_info['volgende_beurt'] == $duel_info['tegenstander']) {
                            $refresh = 1;
                            $mes = $duel_info['uitdager'] . " " . $txt['opponent_have_changed_you_attack'];
                        } else {
                            $refresh = 3;
                            $mes = $duel_info['uitdager'] . " " . $txt['you_have_changed_opponent_attack'];
                        }
                    } elseif ($duel_info['volgende_beurt'] == $_SESSION['naam']) {
                        $pokemon_info = pokemon_data($duel_info['t_pokemonid']);
                        $pokemon_info['naam_goed'] = pokemon_naam($pokemon_info['naam'], $pokemon_info['roepnaam']);
                        $opponent_info = pokemon_data($duel_info['u_pokemonid']);
                        $opponent_info['naam_goed'] = pokemon_naam($opponent_info['naam'], $opponent_info['roepnaam']);
                        $refresh = 1;
                        $info1 = $pokemon_info['leven'];
                        $info2 = $pokemon_info['levenmax'];
                        $info3 = $pokemon_info['naam_goed'];
                        $info4 = $pokemon_info['opzak_nummer'];
                        if ($pokemon_info['leven'] == 0) {
                            $mes = $opponent_info['naam_goed'] . " " . $txt['did'] . " " . $duel_info['laatste_aanval'] . $txt['you_have_to_change'];
                            $refresh = 4;
                        } else {
                            $mes = $opponent_info['naam_goed'] . " " . $txt['did'] . " " . $duel_info['laatste_aanval'] . ". " . $txt['your_turn'];
                            $refresh = 1;
                        }
                    } elseif ($duel_info['volgende_beurt'] == "end_screen") {
                        $refresh = 5;
                        $pokemon_info = pokemon_data($duel_info['t_pokemonid']);
                        $pokemon_info['naam_goed'] = pokemon_naam($pokemon_info['naam'], $pokemon_info['roepnaam']);
                        $opponent_info = pokemon_data($duel_info['u_pokemonid']);
                        $opponent_info['naam_goed'] = pokemon_naam($opponent_info['naam'], $opponent_info['roepnaam']);
                        $info1 = $pokemon_info['leven'];
                        $info2 = $pokemon_info['levenmax'];
                        $mes = $opponent_info['naam_goed'] . " " . $txt['did'] . " " . $duel_info['laatste_aanval'] . $txt['youre_defeated'];
                    } else {
                        $mes = "Error: 1101<br />Info: " . $duel_info['volgende_beurt'] . "/" . $duel_info['id'];
                    }
                }
            }
        }
    } else {
        $mes = "Error: 6001";
    }
    echo $refresh . " | " . $mes . " | " . $duel_info['laatste_aanval'] . " | " . $info1 . " | " . $info2 . " | " . $info3 . " | " . $info4 . " | " . $info5 . " | " . $info6 . " | " . $info7 . " | " . $time_left;
}
?>