<?
if ((isset($_GET['opzak_nummer'])) AND (isset($_GET['duel_id'])) AND (isset($_GET['wie'])) AND (isset($_GET['sid']))) {
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
    //Load duel info
    $duel_info = duel_info($_GET['duel_id']);
    //Check if attack was correct, and screen has to refresh
    $good = 0;
    //Default Values
    $pokemon_old_id = "";
    //Load language
    $page = 'attack/duel/duel-attack';
    //Goeie taal erbij laden voor de page
    include_once('../../language/language-pages.php');
    if (empty($duel_info['id']))
        $message = "Er is iets fout gegaan het gevecht is daarom over.";
    if (($duel_info['u_klaar'] != 1) OR ($duel_info['t_klaar'] != 1))
        $message = $txt['opponent_not_ready'];
    elseif ((strtotime(date("Y-m-d H:i:s")) - $duel_info['laatste_beurt_tijd'] > 60) AND (($duel_info['volgende_beurt'] == $_SESSION['naam']) OR (!strpos($duel_info['laaste_beurt'], $_SESSION['naam']))))
        $message = $txt['too_late_lost'];
    elseif ((strtotime(date("Y-m-d H:i:s")) - $duel_info['laatste_beurt_tijd'] > 60) AND (($duel_info['volgende_beurt'] != $_SESSION['naam']) OR (!strpos($duel_info['laaste_beurt'], $_SESSION['naam']))))
        $message = $txt['opponent_too_late'];
    elseif ($duel_info['volgende_beurt'] == "end_screen")
        $message = $txt['fight_over'];
    elseif (($duel_info['volgende_beurt'] != $_SESSION['naam']) AND ($duel_info['volgende_zet'] == "wisselen"))
        $message = $txt['opponent_must_change'];
    elseif (($duel_info['volgende_beurt'] != $_SESSION['naam']) AND (!empty($duel_info['volgende_beurt'])))
        $message = $txt['opponent_must_attack'];
    else {
        //Load New Pokemon Data
        $change_pokemon = mysql_fetch_array(mysql_query("SELECT pokemon_wild.*, pokemon_speler.*, pokemon_speler_gevecht.*, pokemon_speler.wild_id AS wildid FROM pokemon_wild INNER JOIN pokemon_speler ON pokemon_speler.wild_id = pokemon_wild.wild_id INNER JOIN pokemon_speler_gevecht ON pokemon_speler.id = pokemon_speler_gevecht.id  WHERE pokemon_speler.user_id='" . $_SESSION['id'] . "' AND pokemon_speler.opzak='ja' AND pokemon_speler.opzak_nummer='" . $_GET['opzak_nummer'] . "'"));

        //Does The Pokemon excist
        if (!empty($change_pokemon['id'])) {
            if ($change_pokemon['leven'] <= 0) {
                $message = $txt['pokemon_is_ko'];
            } else {
                if ($duel_info['uitdager'] == $_SESSION['naam']) {
                    $duel_info['you'] = "u";
                    //Load All Opoonent Info
                    $opponent_info = pokemon_data($duel_info['t_pokemonid']);
                    //Load Pokemon id
                    $pokemon_info = pokemon_data($duel_info['u_pokemonid']);
                    if ($pokemon_info['leven'] == 0) {
                        $pokemon_old_id = $pokemon_info['id'];
                        $used_id = "," . $change_pokemon['id'] . ",";
                        if ($change_pokemon['speed'] > $opponent_info['speed']) $vol_be = $duel_info['uitdager'];
                        else $vol_be = $duel_info['tegenstander'];
                    } else {
                        $used = explode(",", $duel_info['u_used_id']);
                        if (!in_array($change_pokemon['id'], $used))
                            $used_id = $duel_info['u_used_id'] . "," . $change_pokemon['id'] . ",";
                        $vol_be = $duel_info['tegenstander'];
                    }
                } elseif ($duel_info['tegenstander'] == $_SESSION['naam']) {
                    $duel_info['you'] = "t";
                    //Load All Opoonent Info
                    $opponent_info = pokemon_data($duel_info['u_pokemonid']);
                    //Load Pokemon id
                    $pokemon_info = pokemon_data($duel_info['t_pokemonid']);
                    if ($pokemon_info['leven'] == 0) {
                        $pokemon_old_id = $pokemon_info['id'];
                        $used_id = "," . $change_pokemon['id'] . ",";
                        if ($change_pokemon['speed'] >= $opponent_info['speed']) $vol_be = $duel_info['tegenstander'];
                        else $vol_be = $duel_info['uitdager'];
                    } else {
                        $used = explode(",", $duel_info['t_used_id']);
                        if (!in_array($change_pokemon['id'], $used))
                            $used_id = $duel_info['t_used_id'] . "," . $change_pokemon['id'] . ",";
                        $vol_be = $duel_info['uitdager'];
                    }
                }

                $time = strtotime(date("Y-m-d H:i:s"));

                mysql_query("UPDATE `duel` SET `" . $duel_info['you'] . "_pokemonid`='" . $change_pokemon['id'] . "', `" . $duel_info['you'] . "_used_id`='" . $used_id . "', `laatste_beurt_tijd`='" . $time . "', `laatste_beurt`='" . $_GET['wie'] . "', `laatste_aanval`='wissel', `volgende_beurt`='" . $vol_be . "', `volgende_zet`='', `last_pokemon_id`='" . $pokemon_old_id . "' WHERE `id`='" . $_GET['duel_id'] . "'");

                if ($vol_be == $_SESSION['naam']) $message = "Jij brengt " . $change_pokemon['naam'] . "<br />" . $txt['your_turn'];
                else  $message = "Jij brengt " . $change_pokemon['naam'] . "<br />" . $vol_be . " " . $txt['opponents_turn'];

                $good = 1;
            }
        } else $message = "Error: 1001<br />Info: " . $change_pokemon['id'] . " - " . $_GET['opzak_nummer'] . " - " . $_SESSION['id'];
    }
    echo $message . " | " . $good . " | " . $change_pokemon['naam'] . " | " . $change_pokemon['level'] . " | " . $change_pokemon['shiny'] . " | " . $change_pokemon['leven'] . " | " . $change_pokemon['levenmax'] . " | " . $change_pokemon['exp'] . " | " . $change_pokemon['expnodig'] . " | " . $change_pokemon['aanval_1'] . " | " . $change_pokemon['aanval_2'] . " | " . $change_pokemon['aanval_3'] . " | " . $change_pokemon['aanval_4'] . " | " . $_GET['opzak_nummer'] . " | " . $vol_be . " | " . $change_pokemon['wildid'];
}
?>