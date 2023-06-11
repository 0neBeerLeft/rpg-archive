<?php
//Is all the information send
if ((isset($_GET['aanval_log_id'])) && (isset($_GET['sid']))) {
    //Session On
    session_start();
    //Connect With Database
    include_once("../../includes/config.php");
    //Include Default Functions
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
    include_once('../../language/language-general.php');
    //Load User Information
    $gebruiker = mysql_fetch_array(mysql_query("SELECT * FROM `gebruikers`, `gebruikers_item` WHERE ((`gebruikers`.`user_id`='" . $_SESSION['id'] . "') AND (`gebruikers_item`.`user_id`='" . $_SESSION['id'] . "'))"));
    if ($gebruiker['itembox'] == 'bag')
        $gebruiker['item_over'] = 20 - $gebruiker['items'];
    elseif ($gebruiker['itembox'] == 'Yellow box')
        $gebruiker['item_over'] = 50 - $gebruiker['items'];
    elseif ($gebruiker['itembox'] == 'Blue box')
        $gebruiker['item_over'] = 100 - $gebruiker['items'];
    elseif ($gebruiker['itembox'] == 'Red box')
        $gebruiker['item_over'] = 250 - $gebruiker['items'];
    //Load Data
    $aanval_log = aanval_log($_GET['aanval_log_id']);
    //Test if fight is over
    if ($aanval_log['laatste_aanval'] == "end_screen") {
        if (mysql_num_rows(mysql_query("SELECT `id` FROM pokemon_speler_gevecht WHERE `user_id`='" . $_SESSION['id'] . "' AND `leven`>'0'")) == 0) {
            if ($gebruiker['rank'] >= 4) rankeraf('attack_lose');
            //Rank Higher Than 3 Decrease silver with 25%
            if ($gebruiker['rank'] >= 3) $money = round($gebruiker['silver'] / 4);
            else $money = 0;
            $win = 0;
            //Update user
            mysql_query("UPDATE `gebruikers` SET `silver`=`silver`-'" . $money . "', `verloren`=`verloren`+'1' WHERE `user_id`='" . $_SESSION['id'] . "'");
        } else {
            $win = 1;
            //Load Trainer Data
            $trainer = mysql_fetch_array(mysql_query("SELECT * FROM `trainer` WHERE `naam`='" . $aanval_log['trainer'] . "'"));
            //HM Cut
            if ($trainer['badge'] == 'Hive') {
                mysql_query("UPDATE `gebruikers_tmhm` SET `HM01`='1' WHERE `user_id`='" . $_SESSION['id'] . "'");
                $hm = $txt['you_also_get_hm'] . ' HM01 Cut.';
            } //HM Fly
            elseif ($trainer['badge'] == 'Feather') {
                mysql_query("UPDATE `gebruikers_tmhm` SET `HM02`='1' WHERE `user_id`='" . $_SESSION['id'] . "'");
                $hm = $txt['you_also_get_hm'] . ' HM02 Fly.';
            } //HM Surf
            elseif ($trainer['badge'] == 'Cascade') {
                mysql_query("UPDATE `gebruikers_tmhm` SET `HM03`='1' WHERE `user_id`='" . $_SESSION['id'] . "'");
                $hm = $txt['you_also_get_hm'] . ' HM03 Surf.';
            } //HM Strength
            elseif ($trainer['badge'] == 'Knuckle') {
                mysql_query("UPDATE `gebruikers_tmhm` SET `HM04`='1' WHERE `user_id`='" . $_SESSION['id'] . "'");
                $hm = $txt['you_also_get_hm'] . ' HM04 Strength.';
            } //HM Flash
            elseif ($trainer['badge'] == 'Relic') {
                mysql_query("UPDATE `gebruikers_tmhm` SET `HM05`='1' WHERE `user_id`='" . $_SESSION['id'] . "'");
                $hm = $txt['you_also_get_hm'] . ' HM05 De fog.';
            } //HM Rock Smash
            elseif ($trainer['badge'] == 'Storm') {
                mysql_query("UPDATE `gebruikers_tmhm` SET `HM06`='1' WHERE `user_id`='" . $_SESSION['id'] . "'");
                $hm = $txt['you_also_get_hm'] . ' HM06 Rock Smash.';
            } //HM Waterfall
            elseif ($trainer['badge'] == 'Fen') {
                mysql_query("UPDATE `gebruikers_tmhm` SET `HM07`='1' WHERE `user_id`='" . $_SESSION['id'] . "'");
                $hm = $txt['you_also_get_hm'] . ' HM07 Waterfall.';
            } //HM Dive
            elseif ($trainer['badge'] == 'Rain') {
                mysql_query("UPDATE `gebruikers_tmhm` SET `HM08`='1' WHERE `user_id`='" . $_SESSION['id'] . "'");
                $hm = $txt['you_also_get_hm'] . ' HM08 Rock Climb.';
            }

            //Give Badge
            if (!empty($trainer['badge'])) {
                mysql_query("UPDATE `gebruikers_badges` SET `" . $trainer['badge'] . "`='1' WHERE `user_id`='" . $_SESSION['id'] . "'");
                mysql_query("UPDATE gebruikers SET badges = badges + '1' WHERE user_id = '" . $_SESSION['id'] . "'");
                rankerbij('gym', $txt);
            } else {
                #miss query van Gold + 1
                rankerbij('trainer', $txt);
            }
            //Give money
            $money = round($trainer['prijs'] * (rand(90, 110) / 100));
            mysql_query("UPDATE `gebruikers` SET `silver`=`silver`+'" . $money . "' WHERE `user_id`='" . $_SESSION['id'] . "'");
            //Maybe Give badge case
            if ($gebruiker['Badge case'] == 0)
                mysql_query("UPDATE `gebruikers_item` SET `Badge case`='1' WHERE `user_id`='" . $_SESSION['id'] . "'");
        }

        echo $trainer['badge'] . " | " . $money . " | " . $rarecandy . " | " . $hm . " | " . $win;
        //Sync pokemon
        pokemon_player_hand_update();
        //Let Pokemon grow
        pokemon_grow($txt);
        //Remove Attack
        remove_attack($_GET['aanval_log_id']);
    } else {
        header("Location: ?page=attack/trainer/trainer-attack");
    }
}
?>