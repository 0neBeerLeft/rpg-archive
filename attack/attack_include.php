<?php
if($_GET['page'] === 'attack/attack_map') {
    //Kijken of je wel pokemon bij je hebt
    if ($gebruiker['in_hand'] == 0) header('location: index.php');

    //Goeie taal erbij laden voor de page
    include_once('language/language-pages.php');
    include_once("attack/wild/wild-start.php");

    $countPokemon = $db->prepare("SELECT `id` FROM `pokemon_speler` WHERE `user_id`=:user_id AND `ei`='0' AND `opzak`='ja'");
    $countPokemon->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_INT);
    $countPokemon->execute();
    $countPokemon = $countPokemon->fetchAll();

    if ($countPokemon) {
        if ((isset($_POST['gebied'])) && (is_numeric($_POST['gebied']))) {
            if ($_POST['gebied'] == 1) $gebied = 'Lavagrot';
            elseif ($_POST['gebied'] == 2) $gebied = 'Vechtschool';
            elseif ($_POST['gebied'] == 3) $gebied = 'Gras';
            elseif ($_POST['gebied'] == 4) $gebied = 'Spookhuis';
            elseif ($_POST['gebied'] == 5) $gebied = 'Grot';
            elseif ($_POST['gebied'] == 6) $gebied = 'Water';
            elseif ($_POST['gebied'] == 7) $gebied = 'Strand';


            if ($gebruiker['in_hand'] == 0)
                echo '<div class="blue"><img src="images/icons/blue.png"> ' . $txt['alert_no_pokemon'] . '</div>';
            elseif (($gebied == 'Water') and ($gebruiker['Fishing rod'] == 0))
                $error = '<div class="red">' . $txt['alert_no_fishing_rod'] . '</div>';
            elseif (($gebied == 'Grot' || $_POST['gebied'] == 'Lavagrot') and ($gebruiker['Cave suit'] == 0))
                $error = '<div class="red">' . $txt['alert_no_cave_suit'] . '</div>';
            else {
                //Zeldzaamheid bepalen
                $zeldzaam = rand(1, 1000);
                $trainer = 0;

                //tussen 0 en 50 een trainer = 50:1000 kans
                if (($zeldzaam > 0 && $zeldzaam < 50)) {

                    $trainer = 1;

                    //tussen 50 en 53 een zeer zeldzaam = 3:1000 kans
                } elseif (($zeldzaam > 50 && $zeldzaam < 53)) {

                    $zeldzaamheid = 3;

                    //tussen 53 en 253 een zeldzaam = 200:1000 kans
                } elseif (($zeldzaam > 53 && $zeldzaam < 253)) {

                    $zeldzaamheid = 2;

                    //tussen 253 en 1000 een normale = 747:1000 kans
                } else {

                    $zeldzaamheid = 1;

                }
                //for logging
                $legend = "Nee";

                //legendkans vergroter actief
                if ((3 * 3600) + $gebruiker['legendkans'] >= time()) {

                    //tussen 0 en 50 een trainer = 50:1000 kans
                    if (($zeldzaam > 0 && $zeldzaam < 50)) {

                        $trainer = 1;

                        //tussen 50 en 60 een zeer zeldzaam = 10:1000 kans
                    } elseif (($zeldzaam > 50 && $zeldzaam < 60)) {

                        $zeldzaamheid = 3;

                        //tussen 60 en 260 een zeldzaam = 200:1000 kans
                    } elseif (($zeldzaam > 60 && $zeldzaam < 260)) {

                        $zeldzaamheid = 2;

                        //tussen 260 en 1000 een normale = 725:1000 kans
                    } else {

                        $zeldzaamheid = 1;

                    }
                    $legend = "Ja";
                }

                if ($zeldzaamheid == '') {
                    $zeldzaamheid = 1;
                }

                if ($trainer == 1) {

                    $getTrainer = $db->prepare("SELECT `naam` FROM `trainer` WHERE `badge`='' AND (`gebied`=:gebied OR `gebied`='All') ORDER BY rand() limit 1");
                    $getTrainer->bindValue(':gebied', $gebied, PDO::PARAM_STR);
                    $getTrainer->execute();
                    $getTrainer = $getTrainer->fetchAll();

                    include('attack/trainer/trainer-start.php');

                    $pokemonSelect = $pokemon_sql->fetchAll();

                    $opzak = count($pokemonSelect);
                    $level = 0;
                    foreach ($pokemonSelect as $pokemon) {
                        $level += $pokemon['level'];
                    }
                    $trainer_ave_level = $level / $opzak;
                    //Make Fight
                    $info = create_new_trainer_attack($getTrainer['naam'], $trainer_ave_level, $gebied);
                    if (empty($info['bericht'])) header("Location: ?page=attack/trainer/trainer-attack");
                    else echo "<div class='red'>" . $txt['alert_no_pokemon'] . "</div>";
                } else {
                    if (($gebruiker['rank'] > 15) && (!empty($gebruiker['lvl_choose']))) {
                        $level = explode("-", $gebruiker['lvl_choose']);
                        $leveltegenstander = rand($level[0], $level[1]);
                    } else $leveltegenstander = rankpokemon($gebruiker['rank']);

                    function getPokemon($zeldzaamheid, $wereld, $gebied)
                    {
                        global $db;

                        $getPokemon = $db->prepare("SELECT wild_id,zeldzaamheid FROM `pokemon_wild` 
                    WHERE `wereld`=:wereld
                    AND `zeldzaamheid`=:zeldzaamheid
                    AND `zeldzaamheid` != 4
                    AND (`gebied`=:gebied OR `gebied`='')");
                        $getPokemon->bindValue(':zeldzaamheid', $zeldzaamheid, PDO::PARAM_INT);
                        $getPokemon->bindValue(':wereld', $wereld, PDO::PARAM_STR);
                        $getPokemon->bindValue(':gebied', $gebied, PDO::PARAM_STR);
                        $getPokemon->execute();
                        $query = $getPokemon->fetchAll();

                        return $query;
                    }

                    while (true) {

                        $query = getPokemon($zeldzaamheid, $gebruiker['wereld'], $gebied);

                        if ($query) {
                            break;
                        }
                        if (empty($query) and $zeldzaamheid == 1) {
                            $zeldzaamheid = rand(2, 3);
                            $query = getPokemon($zeldzaamheid, $gebruiker['wereld'], $gebied);
                            if ($query) {
                                break;
                            }
                        }
                        if (empty($query) and $zeldzaamheid == 2) {
                            $random = rand(1, 2);
                            if ($random == 1) {
                                $zeldzaamheid = 3;
                            } else {
                                $zeldzaamheid = 1;
                            }
                            $query = getPokemon($zeldzaamheid, $gebruiker['wereld'], $gebied);
                            if ($query) {
                                break;
                            }
                        }
                        if (empty($query) and $zeldzaamheid == 3) {
                            $zeldzaamheid = rand(1, 2);
                            $query = getPokemon($zeldzaamheid, $gebruiker['wereld'], $gebied);
                            if ($query) {
                                break;
                            }
                        }
                    }

                    $query = $query[array_rand($query)];

                    if ($zeldzaamheid == 3) {
                        $zzchip = 'Zeer zeldzaam';
                    } elseif ($zeldzaamheid == 2) {
                        $zzchip = 'Beetje zeldzaam';
                    } else {
                        $zzchip = 'Niet zeldzaam';
                    }
                    if (($gebruiker['Pokedex zzchip'] == 1) and ($gebruiker['Pokedex'] == 1)) {
                        $_SESSION['zzchip'] = $zzchip;
                    } else {
                        $_SESSION['zzchip'] = "??";
                    }


                    $info = create_new_attack($query['wild_id'], $leveltegenstander, $gebied);
                    if (empty($info['bericht'])) {
                        header("Location: ?page=attack/wild/wild-attack");
                        die();
                    } else {
                        echo "<div class='red'>" . $txt['alert_no_pokemon'] . "</div>";
                    }

                }
            }
        }
    }
}