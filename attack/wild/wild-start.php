<?
function create_new_attack($computer_id, $computer_level, $gebied)
{

    global $db;

    //Delete last attack logs
    $deleteAttack = $db->prepare("DELETE FROM `aanval_log` WHERE `user_id`=:user_id;
                                          DELETE FROM `pokemon_speler_gevecht` WHERE `user_id`=:user_id");
    $deleteAttack->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_INT);
    $deleteAttack->execute();

    unset($deleteAttack);

    //Create Attack log
    create_aanval_log($gebied);

    //First we create new computer
    $attack_info = create_new_computer($computer_id, $computer_level);

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
    } //There Are no living pokemon.
    else {
        //Clear Computer
        //Clear Player
        $deleteAttack = $db->prepare("DELETE FROM `pokemon_wild_gevecht` WHERE `id`=:computer_id;
                                                DELETE FROM `pokemon_speler_gevecht` WHERE `user_id`=:user_id");
        $deleteAttack->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_INT);
        $deleteAttack->bindValue(':computer_id', $attack_info['computer_id'], PDO::PARAM_INT);
        $deleteAttack->execute();

        unset($deleteAttack);
    }

    return $attack_info;
}

function create_aanval_log($gebied)
{

    global $db;

    $createAttackLog = $db->prepare("INSERT INTO `aanval_log` (`user_id`, `gebied`) VALUES (:user_id, :computer_id)");
    $createAttackLog->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_INT);
    $createAttackLog->bindValue(':computer_id', $gebied, PDO::PARAM_STR);
    $createAttackLog->execute();

    $attackLog = $db->lastInsertId();

    unset($createAttackLog);

    $_SESSION['attack']['aanval_log_id'] = $attackLog;
}

function save_attack($attack_info)
{

    global $db;

    $gebruikt = ',' . $attack_info['pokemonid'] . ',';

    //UPDATE Query
    //Save Player Page Status
    $saveAttack = $db->prepare("UPDATE `aanval_log` SET 
    `laatste_aanval`=:begin, 
    `tegenstanderid`=:computer_id, 
    `pokemonid`=:pokemonid, 
    `gebruikt_id`=:gebruikt 
    WHERE `id`=:aanval_log_id;
    UPDATE `gebruikers` SET `pagina`='attack' WHERE `user_id`=:user_id");
    $saveAttack->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_INT);
    $saveAttack->bindValue(':aanval_log_id', $_SESSION['attack']['aanval_log_id'], PDO::PARAM_INT);
    $saveAttack->bindValue(':begin', $attack_info['begin'], PDO::PARAM_INT);
    $saveAttack->bindValue(':computer_id', $attack_info['computer_id'], PDO::PARAM_INT);
    $saveAttack->bindValue(':pokemonid', $attack_info['pokemonid'], PDO::PARAM_INT);
    $saveAttack->bindValue(':gebruikt', $gebruikt, PDO::PARAM_STR);
    $saveAttack->execute();

    unset($saveAttack);
}

function who_can_start($attack_info)
{

    global $db;

    //Kijken wie de meeste speed heeft, die mag dus beginnen.
    //Speed stat tegenstander -> $speedstat
    //Pokemons laden die de speler opzak heeft
    $nummer = 0;
    $opzaksql = $db->prepare("SELECT pokemon_speler.id, pokemon_speler.opzak_nummer, pokemon_speler.leven, pokemon_speler.speed, pokemon_speler.ei, pokemon_wild.naam FROM pokemon_speler INNER JOIN pokemon_wild ON pokemon_speler.wild_id = pokemon_wild.wild_id WHERE `user_id`=:user_id AND `opzak`='ja' ORDER BY `opzak_nummer` ASC");
    $opzaksql->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_INT);
    $opzaksql->execute();
    $opzaksql = $opzaksql->fetchAll();
    //Alle pokemon opzak stuk voor stuk behandelen
    foreach ($opzaksql as $opzak) {
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

    global $db;

    //Spelers van de pokemon laden die hij opzak heeft
    $pokemonopzaksql = $db->prepare("SELECT * FROM pokemon_speler WHERE `user_id`=:user_id AND `opzak`='ja' ORDER BY opzak_nummer ASC");
    $pokemonopzaksql->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_INT);
    $pokemonopzaksql->execute();
    $pokemonopzaksql = $pokemonopzaksql->fetchAll();

    //Nieuwe stats berekenen aan de hand van karakter, en opslaan
    foreach ($pokemonopzaksql as $pokemonopzak) {
        //Alle gegevens opslaan, incl nieuwe stats

        $saveStats = $db->prepare("INSERT INTO `pokemon_speler_gevecht` (`id`, `user_id`, `aanval_log_id`, `levenmax`, `leven`, `exp`, `totalexp`, `effect`, `hoelang`) 
                                            VALUES (:uid, :user_id, :aanval_log_id, :levenmax, :leven, :exp, :totalexp, :effect, :hoelang)");
        $saveStats->bindValue(':uid', $pokemonopzak['id'], PDO::PARAM_INT);
        $saveStats->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_INT);
        $saveStats->bindValue(':aanval_log_id', $_SESSION['attack']['aanval_log_id'], PDO::PARAM_INT);
        $saveStats->bindValue(':levenmax', $pokemonopzak['levenmax'], PDO::PARAM_STR);
        $saveStats->bindValue(':leven', $pokemonopzak['leven'], PDO::PARAM_STR);
        $saveStats->bindValue(':exp', $pokemonopzak['exp'], PDO::PARAM_STR);
        $saveStats->bindValue(':totalexp', $pokemonopzak['totalexp'], PDO::PARAM_STR);
        $saveStats->bindValue(':effect', $pokemonopzak['effect'], PDO::PARAM_STR);
        $saveStats->bindValue(':hoelang', $pokemonopzak['hoelang'], PDO::PARAM_STR);
        $saveStats->execute();

    }
    unset($saveAttack);
}

function create_new_computer($computer_id, $computer_level)
{

    global $db;

    //Load pokemon basis
    $new_computer_sql = $db->prepare("SELECT * FROM `pokemon_wild` WHERE `wild_id`=:computer_id");
    $new_computer_sql->bindValue(':computer_id', $computer_id, PDO::PARAM_INT);
    $new_computer_sql->execute();
    $new_computer_sql = $new_computer_sql->fetch();

    //We create new computer pokemon
    $new_computer = create_new_computer_pokemon($new_computer_sql, $computer_id, $computer_level);

    //We create new stats for computer
    $new_computer = create_new_computer_stats($new_computer, $new_computer_sql, $computer_level);

    //Save Computer
    $computer = save_new_computer($new_computer, $computer_level);

    return $computer;
}

function save_new_computer($new_computer, $computer_level)
{

    global $db;

    //Computer Shiny?
    $randshiny = rand(1, 200);
    if ($randshiny == 150) $shiny = 1;
    else $shiny = 0;

    //Save Computer
    $saveComputer = $db->prepare("INSERT INTO `pokemon_wild_gevecht` (`wildid`, `aanval_log_id`, `shiny`, `level`, `levenmax`, `leven`, `attack`, `defence`, `speed`, `spc.attack`, `spc.defence`, `aanval_1`, `aanval_2`, `aanval_3`, `aanval_4`) 
    VALUES (:uid, :aanval_log_id, :shiny, :computer_level, :hpstat, :hpstat, :attackstat, :defencestat, :speedstat, :spcattackstat, :spcdefencestat, :aanval1, :aanval2, :aanval3, :aanval4)");
    $saveComputer->bindValue(':uid', $new_computer['id'], PDO::PARAM_INT);
    $saveComputer->bindValue(':aanval_log_id', $_SESSION['attack']['aanval_log_id'], PDO::PARAM_INT);
    $saveComputer->bindValue(':shiny', $shiny, PDO::PARAM_STR);
    $saveComputer->bindValue(':computer_level', $computer_level, PDO::PARAM_STR);
    $saveComputer->bindValue(':hpstat', $new_computer['hpstat'], PDO::PARAM_STR);
    $saveComputer->bindValue(':attackstat', $new_computer['attackstat'], PDO::PARAM_STR);
    $saveComputer->bindValue(':defencestat', $new_computer['defencestat'], PDO::PARAM_STR);
    $saveComputer->bindValue(':speedstat', $new_computer['speedstat'], PDO::PARAM_STR);
    $saveComputer->bindValue(':spcattackstat', $new_computer['spcattackstat'], PDO::PARAM_STR);
    $saveComputer->bindValue(':spcdefencestat', $new_computer['spcdefencestat'], PDO::PARAM_STR);
    $saveComputer->bindValue(':aanval1', $new_computer['aanval1'], PDO::PARAM_STR);
    $saveComputer->bindValue(':aanval2', $new_computer['aanval2'], PDO::PARAM_STR);
    $saveComputer->bindValue(':aanval3', $new_computer['aanval3'], PDO::PARAM_STR);
    $saveComputer->bindValue(':aanval4', $new_computer['aanval4'], PDO::PARAM_STR);
    $saveComputer->execute();

    //Get Computer Id
    $attack_info['computer_id'] = $db->lastInsertId();
    unset($saveComputer);

    $attack_info['computer_wildid'] = $new_computer['id'];
    $attack_info['computer_speed'] = $new_computer['speedstat'];

    return $attack_info;
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

    global $db;

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
        $levelenquery = $db->prepare("SELECT * FROM `levelen` WHERE `wild_id`=:uid AND `level`<=:computer_level AND `stone`='' ORDER BY `id` ASC");
        $levelenquery->bindValue(':uid', $new_computer['id'], PDO::PARAM_INT);
        $levelenquery->bindValue(':computer_level', $computer_level, PDO::PARAM_INT);
        $levelenquery->execute();
        $levelenquery = $levelenquery->fetchAll();

        //Voor elke pokemon alle gegeven behandelen
        foreach ($levelenquery as $groei) {
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