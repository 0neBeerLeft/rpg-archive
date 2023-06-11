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
    chdir('attack/wild/');

    //Include Attack Functions
    include("../attack.inc.php");
    $page = 'attack/wild/wild-attack';
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
    if ($pokemon_info['leven'] == 0) $message = "<b>" . $pokemon_info['naam_goed'] . "</b>" . $txt['new_pokemon_dead'];
    //You've caught the computer
    elseif ($aanval_log['laatste_aanval'] == "gevongen") $message = $txt['success_catched_1'] . "<b>" . $computer_info['naam_goed'] . "</b>" . $txt['success_catched_2'];
    //The fight is ended
    elseif ($aanval_log['laatste_aanval'] == "klaar") $message = $txt['new_pokemon_dead_1'] . "<b>" . $computer_info['naam_goed'] . "</b>" . $txt['new_pokemon_dead_2'];
    else {
        switch ($_GET['wie']) {
            case "pokemon":
                //Turn Check
                if (($aanval_log['laatste_aanval'] == "pokemon") OR ($aanval_log['laatste_aanval'] == "computereersteaanval")) {
                    $message = "<b>" . $computer_info['naam'] . "</b> " . $txt['must_attack'];
                    $next_turn = 1;
                } //Is te Pokemon Alive
                elseif ($pokemon_info['leven'] <= 0) {
                    $message = "Jou <b>" . $pokemon_info['naam_goed'] . "</b> " . $txt['is_ko'];
                } else {
                    //Check the attack
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
                    $message = "<b>" . $pokemon_info['naam'] . "</b> " . $txt['must_attack'];
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
                echo "Error: 4001";
                exit;
        }

        //Attack Begin
        //Set Default Attack Values
        $attack_status['continu'] = 1;
        $message_add = "";

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
                $message = "<b>" . $attacker_info['naam_goed'] . "</b> " . $txt['flinched'];
            } elseif ($attacker_info['effect'] == "Sleep") {
                if ($new_attacker_info['hoelang'] > 1) {
                    $message = "<b>" . $attacker_info['naam_goed'] . "</b> " . $txt['sleeps'];
                } elseif ($new_attacker_info['hoelang'] == 0) {
                    $message = "<b>" . $attacker_info['naam_goed'] . "</b> " . $txt['awake'];
                }
            } elseif ($attacker_info['effect'] == "Frozen_solid") {
                if ($new_attacker_info['hoelang'] > 1) {
                    $message = "<b>" . $attacker_info['naam_goed'] . "</b> " . $txt['frozen'];
                } elseif ($new_attacker_info['hoelang'] == 0) {
                    $message = "<b>" . $attacker_info['naam_goed'] . "</b> " . $txt['no_frozen'];
                }
            } elseif ($attacker_info['effect'] == "Paralyzed") {
                $attack_change = rand(1, 3);
                if ($new_attacker_info['hoelang'] == 0) {
                    $message = "<b>" . $attacker_info['naam_goed'] . "</b> " . $txt['not_paralyzed'];
                } elseif ($attack_change == 2) {
                    $attack_status['continu'] = 1;
                } elseif ($new_attacker_info['hoelang'] > 1) {
                    $message = "<b>" . $attacker_info['naam_goed'] . "</b> " . $txt['paralyzed'];
                }
            }
        }

        //Load Attack Infos
        $attackInfoSQL = $db->prepare("SELECT `naam`, `sterkte`, `hp_schade`, `soort`, `mis`, `aantalkeer`, `effect_kans`, `effect_naam`, `stappen`, `laden`, `recoil`, `extra`, `andereaanval`, `ontwijk` FROM `aanval` WHERE `naam`=:attackName");
        $attackInfoSQL->bindValue(':attackName', $attack_name);
        $attackInfoSQL->execute();
        $attack_info = $attackInfoSQL->fetch(PDO::FETCH_ASSOC);

        if ($attack_info['naam'] == "") {
            if ($_GET['wie'] == "computer") $next_turn = 1;

            //attack failed try once more
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

            //Load Attack Infos
            $attackInfoSQL = $db->prepare("SELECT `naam`, `sterkte`, `hp_schade`, `soort`, `mis`, `aantalkeer`, `effect_kans`, `effect_naam`, `stappen`, `laden`, `recoil`, `extra`, `andereaanval`, `ontwijk` FROM `aanval` WHERE `naam`=:attackName");
            $attackInfoSQL->bindValue(':attackName', $attack_name);
            $attackInfoSQL->execute();
            $attack_info = $attackInfoSQL->fetch(PDO::FETCH_ASSOC);


            if ($attack_info['naam'] == "") {
                echo "Oeps er is iets fout gegaan.<br/>Onze excuses voor het ongemak, de foutmelding is opgeslagen.";

                $file = 'logs.txt';
                // Open the file to get existing content
                $current = file_get_contents($file);
                // Append a new person to the file
                $current .= "Foutcode: 4002<br />Info: " . $attack_name . " - " . $computer_attack . " - " . $_GET['attack_name'] . "\n";
                // Write the contents back to the file
                file_put_contents($file, $current);
                remove_attack($_SESSION['attack']['aanval_log_id']);
                exit;
            }
        } //Attack Can Continu
        elseif ($attack_status['continu'] != 0) {
            //Check if attack does have power
            if ($attack_info['sterkte'] != 0) {
                //Calculate Life Decreasing
                $life_decrease = life_formula($attacker_info, $opponent_info, $attack_info);
            } elseif ($attack_info['hp_schade'] != 0) {
                $life_decrease = $attack_info['hp_schade'];
            }
            /*

            function effect(){


            }

            if($attack_info['effect_naam'] != ''){
            effect($attack_info);
            }

            */
            //If attack hits more then once
            if ($attack_info['aantalkeer'] != "1") {
                $multi_hit = multiple_hits($attack_info, $life_decrease);
                $life_decrease = $multi_hit['damage'];
                $message_add .= $multi_hit['message'];
            }
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

                $aantalPokemonSQL = $db->prepare("SELECT pokemon_speler_gevecht.id FROM pokemon_speler_gevecht INNER JOIN pokemon_speler ON pokemon_speler_gevecht.id = pokemon_speler.id WHERE pokemon_speler_gevecht.aanval_log_id = :attackLogId AND pokemon_speler_gevecht.leven > '0' AND pokemon_speler.ei = '0'");
                $aantalPokemonSQL->bindValue(':attackLogId', $aanval_log['id'], PDO::PARAM_INT);
                $aantalPokemonSQL->execute();
                $aantalpokemon = $aantalPokemonSQL->rowCount();

                //Kan hij geen pokemon wisselen
                if (($aantalpokemon <= 1) OR (empty($aantalpokemon))) {

                    $aantalbericht = $txt['fight_over'];
                    $attack_status['last_attack'] = "end_screen";
                } else {
                    $aantalbericht = $txt['choose_another_pokemon'];
                    $attack_status['fight_end'] = 0;
                    $attack_status['last_attack'] = "wissel";
                }

                $message = "<b>" . $computer_info['naam_goed'] . "</b> " . $txt['use_attack_1'] . " <u>" . $attack_info['naam'] . "</u>" . $txt['use_attack_2'] . $message_add . $aantalbericht;

            } elseif ($attack_status['last_attack'] == "pokemon") {
                $attack_status['last_attack'] = "end_screen";
                $message = "<b>" . $pokemon_info['naam_goed'] . "</b> " . $txt['use_attack_1'] . " <u>" . $attack_info['naam'] . "</u>" . $txt['use_attack_2_hit'] . " <b>" . $computer_info['naam_goed'] . "</b> " . $txt['is_ko'] . $message_add;
                $return = one_pokemon_exp($aanval_log, $pokemon_info, $computer_info, $txt);
            }
        } else {
            $message = "<b>" . $attacker_info['naam_goed'] . "</b> " . $txt['did'] . " <u>" . $attack_info['naam'] . "</u>" . $txt['hit!'] . $message_add;
            if ($_GET['wie'] == 'computer') $message .= $txt['your_attack_turn'];
            else $message .= "<br /><b>" . $opponent_info['naam_goed'] . "</b> " . $txt['opponent_choose_attack'];
        }

        //Update
        $updateOpponent = $db->prepare("UPDATE `" . $opponent_info['table'] . "` SET `leven`=:life WHERE `id`=:opponentId");
        $updateOpponent->bindValue(':life', $levenover);
        $updateOpponent->bindValue(':opponentId', $opponent_info['id'], PDO::PARAM_INT);
        $updateOpponent->execute();

        //Update Aanval Log
        $updateAttackLog = $db->prepare("UPDATE `aanval_log` SET `laatste_aanval`=:lastAttack, `beurten`=`beurten`+'1' WHERE `id`=:attackLogId");
        $updateAttackLog->bindValue(':lastAttack', $attack_status['last_attack']);
        $updateAttackLog->bindValue(':attackLogId', $aanval_log['id'], PDO::PARAM_INT);
        $updateAttackLog->execute();
    }

    $new_exp = $pokemon_info['exp'] + $return['exp'];
    echo $message . " | " . $next_turn . " | " . $levenover . " | " . $opponent_info['levenmax'] . " | " . $attack_status['opponent'] . " | " . $life_off . " | " . $attack_status['fight_end'] . " | " . $life_decrease . " | " . $opponent_info['id'] . " | " . $pokemon_info['opzak_nummer'] . " | " . $return['bericht'] . " | " . $new_exp . " | " . $pokemon_info['expnodig'];
}
?>