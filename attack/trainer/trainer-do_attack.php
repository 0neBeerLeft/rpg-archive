<?
//Is all the information send
if ((isset($_GET['attack_name'])) && (isset($_GET['wie'])) && (isset($_GET['aanval_log_id'])) && (isset($_GET['sid']))) {
    //Session On
    session_start();
    //Connect With Database
    include_once("../../includes/config.php");
    //Connect With Database
    include_once("../../includes/ingame.inc.php");

    //include global definitions
    chdir('../../');
    include_once('includes/globaldefs.php');
    chdir('attack/trainer/');

    //Include Attack Functions
    include("../attack.inc.php");
    $page = 'attack/trainer/trainer-attack';
    //Goeie taal erbij laden voor de page
    include_once('../../language/language-pages.php');
    //Load Attack Info
    $aanval_log = aanval_log($_GET['aanval_log_id']);
    //Check if the right aanval_log is choosen
    if ($aanval_log['user_id'] != $_SESSION['id']) exit;
    //Load Pokemon Info
    $pokemon_info = pokemon_data($aanval_log['pokemonid']);
    //Check if the right pokemon is choosen
    if ($pokemon_info['user_id'] != $_SESSION['id']) exit;
    //Load User Information
    $gebruiker = mysql_fetch_array(mysql_query("SELECT * FROM `gebruikers`, `gebruikers_item` WHERE ((`gebruikers`.`user_id`='" . $_SESSION['id'] . "') AND (`gebruikers_item`.`user_id`='" . $_SESSION['id'] . "'))"));
    //Change name for male and female
    $pokemon_info['naam_goed'] = pokemon_naam($pokemon_info['naam'], $pokemon_info['roepnaam']);
    //Set Database Table
    $pokemon_info['table'] = "pokemon_speler_gevecht";
    //Load Computer Info
    $computer_info = computer_data($aanval_log['tegenstanderid']);
    //Change name for male and female
    $computer_info['naam_goed'] = computer_naam($computer_info['naam']);
    //Set Database Table
    $computer_info['table'] = "pokemon_wild_gevecht";
    $win_lose = 0;
    //Is the new pokemon alive
    if ($pokemon_info['leven'] == 0) $message = $pokemon_info['naam_goed'] . $txt['new_pokemon_dead'];
    //The fight is ended
    elseif ($aanval_log['laatste_aanval'] == "klaar") $message = $txt['fight_finished'];
    else {
        switch ($_GET['wie']) {
            case "pokemon":
                //Turn Check
                if (($aanval_log['laatste_aanval'] == "pokemon") OR ($aanval_log['laatste_aanval'] == "computereersteaanval")) {
                    $message = $computer_info['naam'] . " " . $txt['must_attack'];
                    $next_turn = 1;
                } //Is te Pokemon Alive
                elseif ($pokemon_info['leven'] <= 0) {
                    $message = "Jou " . $pokemon_info['naam_goed'] . " " . $txt['is_ko'];
                } else {
                    if (($_GET['attack_name'] != $pokemon_info['aanval_1']) AND ($_GET['attack_name'] != $pokemon_info['aanval_2']) AND ($_GET['attack_name'] != $pokemon_info['aanval_3']) AND ($_GET['attack_name'] != $pokemon_info['aanval_4'])) {
                        echo "Error: 4003<br />Info: " . $_GET['attack_name'] . "/" . $pokemon_info['id'];
                        exit;
                    }
                    $attack_name = $_GET['attack_name'];
                    $attack_status['last_attack'] = "pokemon";
                    $next_turn = 1;
                    $attacker_info = $pokemon_info;
                    $opponent_info = $computer_info;
                    $attack_status['opponent'] = "computer";
                }

                break;

            case "computer":
                //Turn Check
                if (($aanval_log['laatste_aanval'] == "computer") OR ($aanval_log['laatste_aanval'] == "spelereersteaanval")) {
                    $message = $pokemon_info['naam'] . " moet aanvallen";
                } else {
                    //Check Wich Attack Computer have.
                    $computer_attack = 0;
                    if (!empty($computer_info['aanval_1'])) $computer_attack += 1;
                    if (!empty($computer_info['aanval_2'])) $computer_attack += 1;
                    if (!empty($computer_info['aanval_3'])) $computer_attack += 1;
                    if (!empty($computer_info['aanval_4'])) $computer_attack += 1;
                    $computer_attack = "aanval_" . rand(1, $computer_attack);
                    $attack_name = $computer_info[$computer_attack];
                    $attack_status['last_attack'] = "computer";
                    $next_turn = 0;
                    $attacker_info = $computer_info;
                    $opponent_info = $pokemon_info;
                    $attack_status['opponent'] = "pokemon";
                }

                break;

            default:
                $message = "Error: 4001";
                exit;
        }

        //Attack Begin
        //Set Default Attack Values
        $attack_status['continu'] = 1;

        //Check For effect
        if ($attacker_info['effect'] != "") {
            $new_attacker_info['hoelang'] = $attacker_info['hoelang'] - 1;
            if ($new_attacker_info['hoelang'] == 0) {
                $new_attacker_info['effect'] = "";
            }

            $attack_status['continu'] = 0;
            if ($attacker_info['effect'] == "Flinch") {
                //Effect Empty
                $attacker_info['effect'] = "";
                $message = $attacker_info['naam_goed'] . " " . $txt['flinched'];
            } elseif ($attacker_info['effect'] == "Sleep") {
                if ($new_attacker_info['hoelang'] > 1) {
                    $message = $attacker_info['naam_goed'] . " " . $txt['sleep'];
                } elseif ($new_attacker_info['hoelang'] == 0) {
                    $message = $attacker_info['naam_goed'] . " " . $txt['awake'];
                }
            } elseif ($attacker_info['effect'] == "Frozen_solid") {
                if ($new_attacker_info['hoelang'] > 1) {
                    $message = $attacker_info['naam_goed'] . " " . $txt['frozen'];
                } elseif ($new_attacker_info['hoelang'] == 0) {
                    $message = $attacker_info['naam_goed'] . " " . $txt['no-frozen'];
                }
            } elseif ($attacker_info['effect'] == "Paralyzed") {
                $attack_change = rand(1, 3);
                if ($new_attacker_info['hoelang'] == 0) {
                    $message = $attacker_info['naam_goed'] . " " . $txt['not_paralyzed'];
                } elseif ($attack_change == 2) {
                    $attack_status['continu'] = 1;
                } elseif ($new_attacker_info['hoelang'] > 1) {
                    $message = $attacker_info['naam_goed'] . " " . $txt['paralyzed'];
                }
            }
        }

        //Load Attack Infos
        $attack_info = mysql_fetch_array(mysql_query("SELECT `naam`, `sterkte`, `hp_schade`, `soort`, `mis`, `aantalkeer`, `effect_kans`, `effect_naam`, `stappen`, `laden`, `recoil`, `extra`, `andereaanval`, `ontwijk` FROM `aanval` WHERE `naam`='" . $attack_name . "'"));

        if ($attack_info['naam'] == "") {
            if ($_GET['wie'] == "computer") $next_turn = 1;
            echo "Oeps er is iets fout gegaan.<br/>Onze excuses voor het ongemak, de foutmelding is opgeslagen.";

            $file = 'logs.txt';
            // Open the file to get existing content
            $current = file_get_contents($file);
            // Append a new person to the file
            $current .= "Foutcode: 4002<br />Info: " . $attack_name . "\n";
            // Write the contents back to the file
            file_put_contents($file, $current);
            exit;
        } //Attack Can Continu
        elseif ($attack_status['continu'] != 0) {

            //Calculate Life Decreasing
            $life_decrease = life_formula($attacker_info, $opponent_info, $attack_info);

            //     //Kijken als een aanval in het voordeel of nadeel is
            //     $attack_adv = attack_to_defender_advantage($attack_info['soort'],$opponent_info);
            //     //
            //     $attackder_adv = attacker_with_attack_advantage($attacker_info,$attack_info);
            //     //
            //     $luck = rand(217,255);
            //     //((2A/5+2)*B*C)/D)/50)+2)*X)*Y/10)*Z)/255
            //     // A = level
            //     // B = aanvallers attack
            //     // C = Kracht van de aanval
            //     // D = Verdedigers defence
            //     // X = Aanval type zelfde als Pokemon type. Zo ja dan 1.5, anders 1
            //     // Y = voordeel van de aanval
            //     // Z = willekeurig getal tussen 217 en 255
            //
            //     $life_decrease = round(((((((((2*$attacker_info['level']/5+2)*$attacker_info['attack']*$attack_info['sterkte'])/$opponent_info['defence'])/50)+2)*$attackder_adv)*$attack_adv)*$luck)/255);
            //
            // //     //Als er een andere aanval geladen moet worden.
            // //     if($select['andereaanval'] != ""){
            // //       $aanval = andereaanval($select['andereaanval'],'speler',$tegenstander);
            // //       //Gegevens laden van de nieuw aanval
            // //       $query  = mysql_query("SELECT `naam`, `sterkte`, `hp_schade`, `soort`, `mis`, `aantalkeer`, `effect_naam`, `effect_kans`, `stappen`, `laden`, `recoil`, `extra`, `andereaanval` FROM `aanval` WHERE `naam`='".$aanval."'");
            // //       $select = mysql_fetch_array($query);
            // //     }
        }

        if ($life_decrease > 0) $life_off = 1;
        else $life_off = 0;

        $levenover = $opponent_info['leven'] - $life_decrease;
        //$message .= "<br />".$levenover;
        $attack_status['fight_end'] = 0;
        if ($levenover <= 0) {
            //Gevecht klaar als dit de tegenstander is
            $next_turn = 0;
            $levenover = 0;
            $attack_status['fight_end'] = 1;
            if ($attack_status['last_attack'] == "computer") {
                //Alle pokemons van de speler tellen
                $speler_pokemon = mysql_num_rows(mysql_query("SELECT pokemon_speler_gevecht.id FROM pokemon_speler_gevecht INNER JOIN pokemon_speler ON pokemon_speler_gevecht.id = pokemon_speler.id WHERE pokemon_speler_gevecht.aanval_log_id = '" . $_GET['aanval_log_id'] . "' AND pokemon_speler_gevecht.leven > '0' AND pokemon_speler.ei = '0'"));
                //Kan hij geen pokemon wisselen
                if (($speler_pokemon <= 1) OR (empty($speler_pokemon))) {
                    $aantalbericht = $txt['fight_over'];
                    $attack_status['last_attack'] = "end_screen";
                } else {
                    $aantalbericht = $txt['choose_another_pokemon'];
                    $attack_status['fight_end'] = 0;
                    $attack_status['last_attack'] = "speler_wissel";
                }

                $message = $computer_info['naam_goed'] . " " . $txt['use_attack_1'] . " " . $attack_info['naam'] . $txt['use_attack_2'] . $aantalbericht;

            } elseif ($attack_status['last_attack'] == "pokemon") {
                //Alle Pokemons van trainer tellen
                $trainer_pokemon = mysql_num_rows(mysql_query("SELECT `id` FROM `pokemon_wild_gevecht` WHERE `aanval_log_id`='" . $_GET['aanval_log_id'] . "' AND `leven`>'0'"));
                if (($trainer_pokemon <= 1) OR (empty($trainer_pokemon))) {
                    $win_lose = 1;
                    $attack_status['last_attack'] = "end_screen";
                    $attack_status['fight_end'] = 1;
                } else {
                    $aantalbericht = $aanval_log['trainer'] . " " . $txt['opponent_choose_pokemon'];
                    $attack_status['fight_end'] = 0;
                    $attack_status['last_attack'] = "trainer_wissel";
                }

                $message = $pokemon_info['naam_goed'] . " " . $txt['use_attack_1'] . " " . $attack_info['naam'] . $txt['use_attack_2_hit'] . " " . $computer_info['naam_goed'] . " " . $txt['is_ko'];

                $return = one_pokemon_exp($aanval_log, $pokemon_info, $computer_info, $txt);
                $message .= $return['bericht'];
            }
        } else {
            $message = $attacker_info['naam_goed'] . " " . $txt['did'] . " " . $attack_info['naam'] . $txt['hit!'];
            if ($_GET['wie'] == 'computer') $message .= $txt['your_attack_turn'];
            else $message .= "<br />" . $opponent_info['naam_goed'] . " " . $txt['opponent_choose_attack'];
        }

        //Update
        mysql_query("UPDATE `" . $opponent_info['table'] . "` SET `leven`='" . $levenover . "' WHERE `id`='" . $opponent_info['id'] . "'");
        //Update Aanval Log
        mysql_query("UPDATE `aanval_log` SET `laatste_aanval`='" . $attack_status['last_attack'] . "', `beurten`=`beurten`+'1' WHERE `id`='" . $aanval_log['id'] . "'");

        if ($win_lose == 2) attack_lost($gebruiker, $aanval_log['id'], $aanval_log['tegenstanderid']);

    }
    $new_exp = $pokemon_info['exp'] + $return['exp'];
    echo $message . " | " . $next_turn . " | " . $levenover . " | " . $opponent_info['levenmax'] . " | " . $attack_status['opponent'] . " | " . $life_off . " | " . $attack_status['fight_end'] . " | " . $life_decrease . " | " . $opponent_info['id'] . " | " . $pokemon_info['opzak_nummer'] . " | " . $new_exp . " | " . $pokemon_info['expnodig'];
}
?>