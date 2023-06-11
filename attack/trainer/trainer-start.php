<?
function create_new_trainer_attack($trainer, $trainer_ave_level, $gebied)
{

    //Delete last attack logs
    mysql_query("DELETE FROM `aanval_log` WHERE `user_id`='" . $_SESSION['id'] . "'");
    mysql_query("DELETE FROM `pokemon_speler_gevecht` WHERE `user_id`='" . $_SESSION['id'] . "'");

    //Create Attack log
    create_trainer_aanval_log($gebied, $trainer);

    //Create Trainer
    $attack_info = create_new_trainer($trainer, $trainer_ave_level);

    //Create Player
    create_player($attack_info);

    //Who can start
    $attack_info = who_can_start($attack_info);

    //There Are no living pokemon.
    if (empty($attack_info['bericht'])) {
        //Save Computer As Seen in Pokedex
        update_pokedex($attack_info['computer_wildid'], '', 'zien');

        //Save Attack Info
        save_attack($attack_info);

        $_SESSION['trainer']['begin_zien'] = true;
    } else {
        //Clear Computer
        mysql_query("DELETE FROM `pokemon_wild_gevecht` WHERE `id`='" . $attack_info['computer_id'] . "'");
        //Clear Player
        mysql_query("DELETE FROM `pokemon_speler_gevecht` WHERE `user_id`='" . $_SESSION['id'] . "'");
    }

    return $attack_info;
}

function create_trainer_aanval_log($gebied, $trainer)
{
    mysql_query("INSERT INTO `aanval_log` (`user_id`, `gebied`, `trainer`)
    VALUES ('" . $_SESSION['id'] . "', '" . $gebied . "', '" . $trainer . "')");

    $_SESSION['trainer']['aanval_log_id'] = mysql_insert_id();
}

function save_attack($attack_info)
{
    $gebruikt = ',' . $attack_info['pokemonid'] . ',';

    //UPDATE Query
    mysql_query("UPDATE `aanval_log` SET `laatste_aanval`='" . $attack_info['begin'] . "', `tegenstanderid`='" . $attack_info['computer_id'] . "', `pokemonid`='" . $attack_info['pokemonid'] . "', `gebruikt_id`='" . $gebruikt . "' WHERE `id`='" . $_SESSION['trainer']['aanval_log_id'] . "'");

    //Save Player Page Status
    mysql_query("UPDATE `gebruikers` SET `pagina`='trainer-attack' WHERE `user_id`='" . $_SESSION['id'] . "'");
}

function who_can_start($attack_info)
{
    //Kijken wie de meeste speed heeft, die mag dus beginnen.
    //Speed stat tegenstander -> $speedstat
    //Pokemons laden die de speler opzak heeft
    $nummer = 0;
    $opzaksql = mysql_query("SELECT ps.id, ps.opzak_nummer, ps.leven, ps.speed, ps.ei, pw.naam  FROM pokemon_speler AS ps INNER JOIN pokemon_wild AS pw ON ps.wild_id = pw.wild_id WHERE ps.user_id = '" . $_SESSION['id'] . "' AND ps.opzak = 'ja' ORDER BY ps.opzak_nummer ASC");
    //Alle pokemon opzak stuk voor stuk behandelen
    while ($opzak = mysql_fetch_array($opzaksql)) {
        //Kijken als het level groter dan 0 is
        if (($opzak['leven'] >= 1) and ($opzak['ei'] == 0)) {
            //Elke keer nummer met 1 verhogen
            $nummer++;
            //Is het nummer 1
            if ($nummer == 1) {
                //Gegevens van de speed laden van de speler
                $attack_info['pokemon_speed'] = $opzak['speed'];
                $attack_info['pokemon'] = $opzak['naam'];
                $attack_info['pokemonid'] = $opzak['id'];
            }
        }
    }

    //Er is geen andere pokemon met leven
    //Oude pokemon gebruiken en gevecht stoppen.
    if ($nummer == 0) $attack_info['bericht'] = 'begindood';
    else {
        if ($attack_info['pokemon_speed'] >= $attack_info['computer_speed'])
            $attack_info['begin'] = "spelereersteaanval";
        else
            $attack_info['begin'] = "computereersteaanval";
    }

    return $attack_info;
}

function create_player($attack_info)
{
    //Spelers van de pokemon laden die hij opzak heeft
    $pokemonopzaksql = mysql_query("SELECT * FROM pokemon_speler WHERE `user_id`='" . $_SESSION['id'] . "' AND `opzak`='ja' ORDER BY opzak_nummer ASC");
    //Nieuwe stats berekenen aan de hand van karakter, en opslaan
    while ($pokemonopzak = mysql_fetch_array($pokemonopzaksql)) {
        //Alle gegevens opslaan, incl nieuwe stats
        mysql_query("INSERT INTO `pokemon_speler_gevecht` (`id`, `user_id`, `aanval_log_id`, `levenmax`, `leven`, `exp`, `totalexp`, `effect`, `hoelang`) 
      VALUES ('" . $pokemonopzak['id'] . "', '" . $_SESSION['id'] . "', '" . $_SESSION['trainer']['aanval_log_id'] . "', '" . $pokemonopzak['levenmax'] . "', '" . $pokemonopzak['leven'] . "', '" . $pokemonopzak['exp'] . "', '" . $pokemonopzak['totalexp'] . "', '" . $pokemonopzak['effect'] . "', '" . $pokemonopzak['hoelang'] . "')");
    }
}

function create_new_trainer($trainer, $trainer_ave_level)
{
    //Load Trainer Info
    $trainer = mysql_fetch_array(mysql_query("SELECT trainer.*, trainer_pokemon.* FROM trainer INNER JOIN trainer_pokemon ON trainer.id = trainer_pokemon.trainer_id WHERE trainer.naam = '" . $trainer . "'"));
    $count = 0;
    $pokemonwild_id = explode(",", $trainer['pokemonwild_id']);
    foreach ($pokemonwild_id as $pokemonid) {
        if (!empty($pokemonid)) {
            $level = round(($trainer_ave_level / 100) * rand(95, 130));
            if ($level > 100) $level = 100;
            if ($level < 5) $level = 5;
            $count++;

            //Load pokemon basis
            $new_computer_sql = mysql_fetch_array(mysql_query("SELECT * FROM `pokemon_wild` WHERE `wild_id`='" . $pokemonid . "'"));

            //We create new computer pokemon
            $new_computer = create_new_computer_pokemon($new_computer_sql, $pokemonid, $level);

            //We create new stats for computer
            $new_computer = create_new_computer_stats($new_computer, $new_computer_sql, $level);

            //Save Computer
            $computer = save_new_computer($new_computer, $new_computer_sql, $level);

            if ($count == 1) {
                $attack_info['computer_id'] = $computer;
                $attack_info['computer_wildid'] = $new_computer_sql['wild_id'];
                $attack_info['computer'] = $new_computer['pokemon'];
                $attack_info['computer_speed'] = $new_computer['speedstat'];
            }
        }
    }
    if ($count == 0) $attack_info['bericht'] = 'oponent_error';
    return $attack_info;
}

function save_new_computer($new_computer, $new_computer_sql, $computer_level)
{
    //Save Computer
    mysql_query("INSERT INTO `pokemon_wild_gevecht` (`wildid`, `aanval_log_id`, `level`, `levenmax`, `leven`, `attack`, `defence`, `speed`, `spc.attack`, `spc.defence`, `aanval_1`, `aanval_2`, `aanval_3`, `aanval_4`) 
    VALUES ('" . $new_computer['id'] . "', '" . $_SESSION['trainer']['aanval_log_id'] . "', '" . $computer_level . "', '" . $new_computer['hpstat'] . "', '" . $new_computer['hpstat'] . "', '" . $new_computer['attackstat'] . "', '" . $new_computer['defencestat'] . "', '" . $new_computer['speedstat'] . "', '" . $new_computer['spcattackstat'] . "', '" . $new_computer['spcdefencestat'] . "', '" . $new_computer['aanval1'] . "', '" . $new_computer['aanval2'] . "', '" . $new_computer['aanval3'] . "', '" . $new_computer['aanval4'] . "')");

    return mysql_insert_id();
}

function create_new_computer_stats($new_computer, $new_computer_sql, $computer_level)
{
    //Iv willekeurig getal tussen 2,15
    //Normaal tussen 1,31 maar wilde pokemon moet wat minder sterk zijn
    $attack_iv = rand(2, 15);
    $defence_iv = rand(2, 15);
    $speed_iv = rand(2, 15);
    $spcattack_iv = rand(2, 15);
    $spcdefence_iv = rand(2, 15);
    $hp_iv = rand(2, 15);

    //Stats berekenen
    $new_computer['attackstat'] = round(((($new_computer_sql['attack_base'] * 2 + $attack_iv) * $computer_level / 100) + 5) * 1);
    $new_computer['defencestat'] = round(((($new_computer_sql['defence_base'] * 2 + $defence_iv) * $computer_level / 100) + 5) * 1);
    $new_computer['speedstat'] = round(((($new_computer_sql['speed_base'] * 2 + $speed_iv) * $computer_level / 100) + 5) * 1);
    $new_computer['spcattackstat'] = round(((($new_computer_sql['spc.attack_base'] * 2 + $spcattack_iv) * $computer_level / 100) + 5) * 1);
    $new_computer['spcdefencestat'] = round(((($new_computer_sql['spc.defence_base'] * 2 + $spcdefence_iv) * $computer_level / 100) + 5) * 1);
    $new_computer['hpstat'] = round(((($new_computer_sql['hp_base'] * 2 + $hp_iv) * $computer_level / 100) + $computer_level) + 10);
    return $new_computer;
}

function create_new_computer_pokemon($new_computer_sql, $computer_id, $computer_level)
{
    //Alle gegevens vast stellen voordat alles begint.
    $new_computer['id'] = $new_computer_sql['wild_id'];
    $new_computer['pokemon'] = $new_computer_sql['naam'];
    $new_computer['aanval1'] = $new_computer_sql['aanval_1'];
    $new_computer['aanval2'] = $new_computer_sql['aanval_2'];
    $new_computer['aanval3'] = $new_computer_sql['aanval_3'];
    $new_computer['aanval4'] = $new_computer_sql['aanval_4'];
    $klaar = false;
    $loop = 0;
    $lastid = 0;
    //Loop beginnen
    do {
        $teller = 0;
        $loop++;
        //Levelen gegevens laden van de pokemon
        $levelenquery = mysql_query("SELECT * FROM `levelen` WHERE `wild_id`='" . $new_computer['id'] . "' AND `level`<='" . $computer_level . "' AND `stone`='' ORDER BY `id` ASC ");
        //Voor elke pokemon alle gegeven behandelen
        while ($groei = mysql_fetch_array($levelenquery)) {
            //Teller met 1 verhogen
            $teller++;
            //Is het nog binnen de level?
            if ($computer_level >= $groei['level']) {
                //Is het een aanval?
                if ($groei['wat'] == 'att') {
                    //Is er een plek vrij
                    if (empty($new_computer['aanval1'])) $new_computer['aanval1'] = $groei['aanval'];
                    elseif (empty($new_computer['aanval2'])) $new_computer['aanval2'] = $groei['aanval'];
                    elseif (empty($new_computer['aanval3'])) $new_computer['aanval3'] = $groei['aanval'];
                    elseif (empty($new_computer['aanval4'])) $new_computer['aanval4'] = $groei['aanval'];
                    //Er is geen ruimte, dan willekeurig een aanval kiezen en plaatsen
                    else {
                        if (($new_computer['aanval1'] != $groei['aanval']) and ($new_computer['aanval2'] != $groei['aanval']) and ($new_computer['aanval3'] != $groei['aanval']) and ($new_computer['aanval4'] != $groei['aanval'])) {
                            $nummer = rand(1, 4);
                            if ($nummer == 1) $new_computer['aanval1'] = $groei['aanval'];
                            elseif ($nummer == 2) $new_computer['aanval2'] = $groei['aanval'];
                            elseif ($nummer == 3) $new_computer['aanval3'] = $groei['aanval'];
                            elseif ($nummer == 4) $new_computer['aanval4'] = $groei['aanval'];
                        }
                    }
                } //Evolueert de pokemon
                elseif ($groei['wat'] == "evo") {
                    $evo = mysql_fetch_array(mysql_query("SELECT * FROM `pokemon_wild` WHERE `wild_id`='" . $groei['nieuw_id'] . "'"));
                    $new_computer['id'] = $groei['nieuw_id'];
                    $new_computer['pokemon'] = $groei['naam'];
                    $loop = 0;
                    break;
                }
            } //Er gebeurd niks dan stoppen
            else {
                $klaar = true;
                break;
            }
        }
        if ($teller == 0) {
            break;
            $klaar = true;
        }
        if ($loop == 2) {
            break;
            $klaar = true;
        }
    } while (!$klaar);
    return $new_computer;
}

?>