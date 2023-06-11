<?
if ((isset($_GET['attack_name'])) AND (isset($_GET['duel_id'])) AND (isset($_GET['wie'])) AND (isset($_GET['sid']))) {
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
    //Load duel info
    $duel_info = duel_info($_GET['duel_id']);
    //Check if attack was correct, and screen has to refresh
    $good = 0;
    //Default Value
    $attack_status['winner'] = "";
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
    elseif ($_SESSION['naam'] != $_GET['wie'])
        $message = "error: 9001";
    elseif (($duel_info['volgende_beurt'] != $_SESSION['naam']) AND ($duel_info['volgende_zet'] == "wisselen"))
        $message = $txt['opponent_must_change'];
    elseif (($duel_info['volgende_beurt'] != $_SESSION['naam']) AND (!empty($duel_info['volgende_beurt'])))
        $message = $txt['opponent_must_attack'];
    else {
        //Check Who attacks
        if ($duel_info['uitdager'] == $_SESSION['naam']) {
            //Load All Opponent Info
            $opponent_info = pokemon_data($duel_info['t_pokemonid']);
            $opponent_info['naam_goed'] = pokemon_naam($opponent_info['naam'], $opponent_info['roepnaam']);
            $opponent_info['username'] = $duel_info['tegenstander'];
            //Load All Pokemon Info
            $pokemon_info = pokemon_data($duel_info['u_pokemonid']);
            $pokemon_info['naam_goed'] = pokemon_naam($pokemon_info['naam'], $pokemon_info['roepnaam']);
            //Other Check
            $attack_status['next_turn'] = $duel_info['tegenstander'];
            $attack_status['you'] = "u";
        } elseif ($duel_info['tegenstander'] == $_SESSION['naam']) {
            //Load All Opoonent Info
            $opponent_info = pokemon_data($duel_info['u_pokemonid']);
            $opponent_info['naam_goed'] = pokemon_naam($opponent_info['naam'], $opponent_info['roepnaam']);
            $opponent_info['username'] = $duel_info['uitdager'];
            //Load All Pokemon Info
            $pokemon_info = pokemon_data($duel_info['t_pokemonid']);
            $pokemon_info['naam_goed'] = pokemon_naam($pokemon_info['naam'], $pokemon_info['roepnaam']);
            //Other Check
            $attack_status['next_turn'] = $duel_info['uitdager'];
            $attack_status['you'] = "t";
        }

        if (($_GET['attack_name'] != $pokemon_info['aanval_1']) AND ($_GET['attack_name'] != $pokemon_info['aanval_2']) AND ($_GET['attack_name'] != $pokemon_info['aanval_3']) AND ($_GET['attack_name'] != $pokemon_info['aanval_4'])) {
            $message = "Error: 4003<br />Info: " . $_GET['attack_name'] . "/" . $pokemon_info['id'];
        } elseif ($pokemon_info['leven'] == 0) {
            $message = $pokemon_info['naam_goed'] . " " . $txt['have_to_change'];
        } else {
            //Attack Begin
            //Set Default Attack Values
            $attack_status['continu'] = 1;
            //Check For effect
            if ($attacker_info['effect'] != "") {
                $new_attacker_info['hoelang'] = $pokemon_info['hoelang'] - 1;
                if ($new_attacker_info['hoelang'] == 0) {
                    $new_attacker_info['effect'] = "";
                }
                $attack_status['continu'] = 0;
                if ($pokemon_info['effect'] == "Flinch") {
                    //Effect Empty
                    $pokemon_info['effect'] = "";
                    $message = $pokemon_info['naam_goed'] . " " . $txt['flinched'];
                } elseif ($pokemon_info['effect'] == "Sleep") {
                    if ($new_attacker_info['hoelang'] > 1) {
                        $message = $pokemon_info['naam_goed'] . " " . $txt['sleep'];
                    } elseif ($new_attacker_info['hoelang'] == 0) {
                        $message = $pokemon_info['naam_goed'] . " " . $txt['awake'];
                    }
                } elseif ($pokemon_info['effect'] == "Frozen_solid") {
                    if ($new_attacker_info['hoelang'] > 1) {
                        $message = $pokemon_info['naam_goed'] . " " . $txt['frozen'];
                    } elseif ($new_attacker_info['hoelang'] == 0) {
                        $message = $pokemon_info['naam_goed'] . " " . $txt['no-frozen'];
                    }
                } elseif ($pokemon_info['effect'] == "Paralyzed") {
                    $attack_change = rand(1, 3);
                    if ($new_attacker_info['hoelang'] == 0) {
                        $message = $pokemon_info['naam_goed'] . " " . $txt['not_paralyzed'];
                    } elseif ($attack_change == 2) {
                        $attack_status['continu'] = 1;
                    } elseif ($new_attacker_info['hoelang'] > 1) {
                        $message = $pokemon_info['naam_goed'] . " " . $txt['paralyzed'];
                    }
                }
            }

            //Load Attack Infos
            $attack_info = mysql_fetch_array(mysql_query("SELECT `naam`, `sterkte`, `hp_schade`, `soort`, `mis`, `aantalkeer`, `effect_kans`, `effect_naam`, `stappen`, `laden`, `recoil`, `extra`, `andereaanval`, `ontwijk` FROM `aanval` WHERE `naam`='" . $_GET['attack_name'] . "'"));

            if (empty($attack_info['naam'])) {
                echo "Error: 4002<br />Info: " . $attack_name . " | " . $good;
                exit;
            } //Attack Can Continu
            elseif ($attack_status['continu'] != 0) {
                //Calculate Life Decreasing
                $life_decrease = life_formula($pokemon_info, $opponent_info, $attack_info);
            }

            if ($life_decrease > 0) $life_off = 1;
            else $life_off = 0;

            $levenover = $opponent_info['leven'] - $life_decrease;
            if ($levenover <= 0) {
                //Gevecht klaar als dit de tegenstander is
                $levenover = 0;
                //Alle pokemons van de speler tellen
                $tegenstander = mysql_num_rows(mysql_query("SELECT psg.id FROM pokemon_speler_gevecht AS psg INNER JOIN pokemon_speler AS ps ON psg.id = ps.id WHERE ps.user_id='" . $opponent_info['user_id'] . "' AND psg.leven>'0' AND ps.ei='0'"));
                //Kan hij geen pokemon wisselen
                if ($tegenstander == 1) {
                    $aantalbericht = "Het gevecht is afgelopen.";
                    $attack_status['next_turn'] = "end_screen";
                    $attack_status['winner'] = $_SESSION['naam'];
                    $good = 2;
                } else {
                    $aantalbericht = $opponent_info['username'] . " " . $txt['opponent_change'];
                    $attack_status['next_move'] = "wisselen";
                    $good = 1;
                }
                $message = $pokemon_info['naam_goed'] . " " . $txt['use_attack_1'] . " " . $attack_info['naam'] . $txt['use_attack_2_hit'] . " " . $opponent_info['naam_goed'] . " " . $txt['is_ko'] . "<br />" . $aantalbericht;
                $return = duel_one_dead($duel_info, $attack_status['you'], $pokemon_info, $opponent_info, $txt);
                $message .= $return['bericht'];
            } else {
                $message = $pokemon_info['naam_goed'] . " " . $txt['did'] . " " . $attack_info['naam'] . $txt['hit!'];
                $message .= "<br />" . $opponent_info['username'] . " " . $txt['opponent_choose_attack'];
                $good = 1;
            }

            $time = strtotime(date("Y-m-d H:i:s"));

            mysql_query("UPDATE `duel` SET `winner`='" . $attack_status['winner'] . "', `laatste_beurt_tijd`='" . $time . "', `laatste_beurt`='" . $_GET['wie'] . "', `laatste_aanval`='" . $_GET['attack_name'] . "', `schade`='" . $life_decrease . "', `volgende_beurt`='" . $attack_status['next_turn'] . "', `volgende_zet`='" . $attack_status['next_move'] . "' WHERE `id`='" . $_GET['duel_id'] . "'");

            //Update Pokemon Life
            mysql_query("UPDATE `pokemon_speler_gevecht` SET `leven`='" . $levenover . "' WHERE `id`='" . $opponent_info['id'] . "'");
            $exp_new = $pokemon_info['exp'] + $return['exp'];
        }
    }
    echo $message . " | " . $good . " | " . $life_decrease . " | " . $levenover . " | " . $opponent_info['levenmax'] . " | " . $return['exp'] . " | " . $exp_new . " | " . $pokemon_info['expnodig'];
}
?>