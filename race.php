<?
#Load language
$page = 'race';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

#Checks
if ($gebruiker['rank'] < 4) echo '<div class="red"><img src="images/icons/red.png"> ' . $txt['alert_to_low_rank'] . '</div>';
elseif ($gebruiker['in_hand'] == 0) echo '<div class="red"><img src="images/icons/red.png"> ' . $txt['alert_no_pokemon_in_hand'] . '</div>';
elseif (($_GET['id'] == '') || ($_GET['code'] == '') || ($_GET['accept'] == '')) echo '<div class="red"><img src="images/icons/red.png"> ' . $txt['alert_link_invalid'] . '</div>';
elseif ((!is_numeric($_GET['id'])) || (!is_numeric($_GET['code'])) || (!is_numeric($_GET['accept']))) echo '<div class="red"><img src="images/icons/red.png"> ' . $txt['alert_link_invalid'] . '</div>';
elseif (($_GET['accept'] != 0) && ($_GET['accept'] != 1)) echo '<div class="red"><img src="images/icons/red.png"> ' . $txt['alert_link_invalid'] . '</div>';
else {
    $sql = mysql_query("SELECT races.*, gebruikers.username, gebruikers.land, gebruikers.missie_6 FROM races INNER JOIN gebruikers ON races.uitdager = gebruikers.user_id WHERE races.race_id = '" . $_GET['id'] . "' AND races.code = '" . $_GET['code'] . "' AND races.tegenstander = '" . $_SESSION['id'] . "'");
    $select = mysql_fetch_assoc($sql);
    if (mysql_num_rows($sql) == 0) echo '<div class="blue"><img src="images/icons/blue.png"> ' . $txt['alert_race_invalid'] . '</div>';
    elseif ((($select['silver'] > $gebruiker['silver']) || ($select['gold'] > $gebruiker['gold'])) && ($_GET['accept'] == 1)) echo '<div class="red"><img src="images/icons/red.png"> ' . $txt['alert_not_enough_money'] . '</div>';
    else {
        #Als accept 0 is
        if ($_GET['accept'] == 0) {
            #Race verwijderen
            mysql_query("DELETE FROM races WHERE race_id = '" . $_GET['id'] . "' AND code = '" . $_GET['code'] . "' AND tegenstander = '" . $_SESSION['id'] . "'");

            #Geef uitdager geld terug
            mysql_query("UPDATE gebruikers SET silver = silver + '" . $select['silver'] . "', gold = gold + '" . $select['gold'] . "' WHERE user_id = '" . $select['uitdager'] . "'");

            #Event taal pack includen
            //$eventlanguage = GetEventLanguage($select['land']);
            include('language/events/language-events-nl.php');

            #Bericht opstellen na wat de language van de user is
            $event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower"> ' . $txt['race_denied'];

            #Melding geven aan de uitdager
            mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
			VALUES (NULL, NOW(), '" . $select['uitdager'] . "', '" . $event . "', '0')");

            #Melding hier ff weergeven
            echo '<div class="green"><img src="images/icons/green.png"> ' . $txt['success_denied'] . '</div>';
        } #Als race is geaccepteerd
        else {
            #Ff fixen dat diegene betaald voor de race
            mysql_query("UPDATE gebruikers SET silver = silver - '" . $select['silver'] . "', gold = gold - '" . $select['gold'] . "' WHERE user_id = '" . $_SESSION['id'] . "'");

            #Gegevens ophalen van de race
            $speed_you = mysql_fetch_assoc(mysql_query("SELECT speed, naam FROM pokemon_speler INNER JOIN pokemon_wild ON pokemon_speler.wild_id = pokemon_wild.wild_id WHERE user_id = '" . $_SESSION['id'] . "' ORDER BY speed DESC LIMIT 1"));
            $rand_you = rand(1, 15);
            $you_tree = rand(1, 10);
            $naam_you = $speed_you['naam'];

            $speed_opp = mysql_fetch_assoc(mysql_query("SELECT speed, naam FROM pokemon_speler INNER JOIN pokemon_wild ON pokemon_speler.wild_id = pokemon_wild.wild_id WHERE user_id = '" . $select['uitdager'] . "' ORDER BY speed DESC LIMIT 1"));
            $rand_opp = rand(1, 15);
            $opp_tree = rand(1, 10);
            $naam_opp = $speed_opp['naam'];

            #De race:
            if ($you_tree != 1) {
                $speed_you2 = ($speed_you['speed'] / 100) * $rand_you;
                $your_speed = $speed_you2 + $speed_you['speed'];
                $your_time = round(5000 / ($your_speed / 2));
            } else $your_time = 999999;

            if ($opp_tree != 3) {
                $speed_opp2 = ($speed_opp['speed'] / 100) * $rand_opp;
                $opp_speed = $speed_opp2 + $speed_opp['speed'];
                $opp_time = round(5000 / ($opp_speed / 2));
            } else $opp_time = 999999;

            #Jij verliest
            if ($your_time > $opp_time) {
                #Beetje rank eraf halen
                rankeraf('race');

                ###Winnaar
                $silverwinst = $select['silver'] * 2;
                $goldwinst = $select['gold'] * 2;
                mysql_query("UPDATE gebruikers SET silver = silver + '" . $silverwinst . "', gold = gold + '" . $goldwinst . "', races_winst = races_winst + '1' WHERE user_id = '" . $select['uitdager'] . "'");
                //complete mission 6
                if ($select['missie_6'] == 0) {
                    mysql_query("UPDATE `gebruikers` SET `missie_6`=1, `silver`=`silver`+1750,`rankexp`=rankexp+200 WHERE `user_id`='" . $select['uitdager'] . "'");
                    echo showToastr("info", "Je hebt een missie behaald!");
                }

                #Dit include het bestand waar de woorden instaan
                //$eventlanguage = GetEventLanguage($select['land']);
                include('language/events/language-events-nl.php');

                #Bericht opstellen na wat de language van de user is
                if ($your_time == 999999) $bericht_win = '<img src="images/icons/green.png" width="16" height="16" class="imglower"> ' . $txt['event_race_won_your'] . ' ' . $naam_opp . ' ' . $txt['event_finished_in'] . ' ' . $opp_time . ' ' . $txt['event_sec_the'] . ' ' . $naam_you . ' ' . $txt['event_from'] . ' ' . $gebruiker['username'] . ' ' . $txt['event_hit_tree_ko'];

                else $bericht_win = '<img src="images/icons/green.png" width="16" height="16" class="imglower"> ' . $txt['event_race_won_your'] . ' ' . $naam_opp . ' ' . $txt['event_finished_in'] . ' ' . $opp_time . ' ' . $txt['event_sec_the'] . ' ' . $naam_you . ' ' . $txt['event_from'] . ' ' . $gebruiker['username'] . ' ' . $txt['event_finished_in'] . ' ' . $your_time . ' ' . $txt['event_sec'];

                #Melding geven aan de uitdager
                mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
				VALUES (NULL, NOW(), '" . $select['uitdager'] . "', '" . $bericht_win . "', '0')");

                ###Verliezer
                mysql_query("UPDATE gebruikers SET races_verlies = races_verlies + '1' WHERE user_id = '" . $_SESSION['id'] . "'");

                #Ophalen wat voor language de user heeft
                $eventlanguage = GetEventLanguage($gebruiker['land']);
                include('language/events/language-events-nl.php');

                #Bericht opstellen na wat de language van de user is
                if ($your_time == 999999) $bericht_lost = '<img src="images/icons/red.png" width="16" height="16" class="imglower"> ' . $txt['event_race_lost_your'] . ' ' . $naam_you . ' ' . $txt['event_hit_tree_ko'] . ' ' . $txt['event_the'] . ' ' . $naam_opp . ' ' . $txt['event_from'] . ' ' . $select['username'] . ' ' . $txt['event_finished_in'] . ' ' . $opp_time . ' ' . $txt['event_sec'];

                else $bericht_lost = '<img src="images/icons/red.png" width="16" height="16" class="imglower"> ' . $txt['event_race_lost_your'] . ' ' . $naam_you . ' ' . $txt['event_finished_in'] . ' ' . $your_time . ' ' . $txt['event_sec_the'] . ' ' . $naam_opp . ' ' . $txt['event_from'] . ' ' . $select['username'] . ' ' . $txt['event_finished_in'] . ' ' . $opp_time . ' ' . $txt['event_sec'];


                #Melding geven aan de uitdager
                mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
				VALUES (NULL, NOW(), '" . $_SESSION['id'] . "', '" . $bericht_lost . "', '0')");

            } #Jij wint
            elseif ($opp_time > $your_time) {
                #Beetje rank erbij doen
                rankerbij('race', $txt);

                ###Winnaar
                $silverwinst = $select['silver'] * 2;
                $goldwinst = $select['gold'] * 2;
                mysql_query("UPDATE gebruikers SET silver = silver + '" . $silverwinst . "', gold = gold + '" . $goldwinst . "', races_winst = races_winst + '1' WHERE user_id = '" . $_SESSION['id'] . "'");

                #Ophalen wat voor language de user heeft
                $eventlanguage = GetEventLanguage($gebruiker['land']);
                include('language/events/language-events-nl.php');

                #Bericht opstellen na wat de language van de user is
                if ($opp_time == 999999) $bericht_win = '<img src="images/icons/green.png" width="16" height="16" class="imglower"> ' . $txt['event_race_won_your'] . ' ' . $naam_you . ' ' . $txt['event_finished_in'] . ' ' . $your_time . ' ' . $txt['event_sec_the'] . ' ' . $naam_opp . ' ' . $txt['event_from'] . ' ' . $select['username'] . ' ' . $txt['event_hit_tree_ko'];

                else $bericht_win = '<img src="images/icons/green.png" width="16" height="16" class="imglower"> ' . $txt['event_race_won_your'] . ' ' . $naam_you . ' ' . $txt['event_finished_in'] . ' ' . $your_time . ' ' . $txt['event_sec_the'] . ' ' . $naam_opp . ' ' . $txt['event_from'] . ' ' . $select['username'] . ' ' . $txt['event_finished_in'] . ' ' . $opp_time . ' ' . $txt['event_sec'];

                #Melding geven aan de uitdager
                mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
				VALUES (NULL, NOW(), '" . $_SESSION['id'] . "', '" . $bericht_win . "', '0')");

                ###Verliezer
                mysql_query("UPDATE gebruikers SET races_verlies = races_verlies + '1' WHERE user_id = '" . $select['uitdager'] . "'");

                #Ophalen wat voor language de user heeft
                //$eventlanguage = GetEventLanguage($select['land']);
                include('language/events/language-events-nl.php');

                #Bericht opstellen na wat de language van de user is
                if ($opp_time == 999999) $bericht_lost = '<img src="images/icons/red.png" width="16" height="16" class="imglower"> ' . $txt['event_race_lost_your'] . ' ' . $naam_opp . ' ' . $txt['event_hit_tree_ko'] . ' ' . $txt['event_the'] . ' ' . $naam_you . ' ' . $txt['event_from'] . ' ' . $gebruiker['username'] . ' ' . $txt['event_finished_in'] . ' ' . $your_time . ' ' . $txt['event_sec'];

                else $bericht_lost = '<img src="images/icons/red.png" width="16" height="16" class="imglower"> ' . $txt['event_race_lost_your'] . ' ' . $naam_opp . ' ' . $txt['event_finished_in'] . ' ' . $opp_time . ' ' . $txt['event_sec_the'] . ' ' . $naam_you . ' ' . $txt['event_from'] . ' ' . $gebruiker['username'] . ' ' . $txt['event_finished_in'] . ' ' . $your_time . ' ' . $txt['event_sec'];

                #Melding geven aan de uitdager
                mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
				VALUES (NULL, NOW(), '" . $select['uitdager'] . "', '" . $bericht_lost . "', '0')");
            } else {
                #Gelijkspel
                $silverwinst = $select['silver'];
                $goldwinst = $select['gold'];
                mysql_query("UPDATE gebruikers SET silver = silver + '" . $silverwinst . "', gold = gold + '" . $goldwinst . "' WHERE user_id = '" . $_SESSION['id'] . "'");

                #Ophalen wat voor language de user heeft
                $eventlanguage = GetEventLanguage($gebruiker['land']);
                include('language/events/language-events-nl.php');

                #Bericht opstellen na wat de language van de user is
                if (($opp_time == 999999) && ($your_time == 999999)) $bericht_draw_you = '<img src="images/icons/blue.png" width="16" height="16" class="imglower"> ' . $txt['event_race_draw_your'] . ' ' . $naam_you . ' ' . $txt['event_and_the'] . ' ' . $naam_opp . ' ' . $txt['event_from'] . ' ' . $select['username'] . ' ' . $txt['event_hit_both_tree'];

                else $bericht_draw_you = '<img src="images/icons/blue.png" width="16" height="16" class="imglower"> ' . $txt['event_race_draw_your'] . ' ' . $naam_you . ' ' . $txt['event_and_the'] . ' ' . $naam_opp . ' ' . $txt['event_from'] . ' ' . $select['username'] . ' ' . $txt['event_finished_both_in'] . ' ' . $your_time . ' ' . $txt['event_sec'];

                #Melding geven aan de uitdager
                mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
				VALUES (NULL, NOW(), '" . $_SESSION['id'] . "', '" . $bericht_draw_you . "', '0')");

                #Verliezer
                mysql_query("UPDATE gebruikers SET silver = silver + '" . $silverwinst . "', gold = gold + '" . $goldwinst . "' WHERE user_id = '" . $select['uitdager'] . "'");

                #Ophalen wat voor language de user heeft
                //$eventlanguage = GetEventLanguage($select['land']);
                include('language/events/language-events-nl.php');

                #Bericht opstellen na wat de language van de user is
                if (($opp_time == 999999) && ($your_time == 999999)) $bericht_draw_opp = '<img src="images/icons/blue.png" width="16" height="16" class="imglower"> ' . $txt['event_race_draw_your'] . ' ' . $naam_opp . ' ' . $txt['event_and_the'] . ' ' . $naam_you . ' ' . $txt['event_from'] . ' ' . $gebruiker['username'] . ' ' . $txt['event_hit_both_tree'];

                else $bericht_draw_opp = '<img src="images/icons/blue.png" width="16" height="16" class="imglower"> ' . $txt['event_race_draw_your'] . ' ' . $naam_opp . ' ' . $txt['event_and_the'] . ' ' . $naam_you . ' ' . $txt['event_from'] . ' ' . $gebruiker['username'] . ' ' . $txt['event_finished_both_in'] . ' ' . $your_time . ' ' . $txt['event_sec'];

                #Melding geven aan de uitdager
                mysql_query("INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
				VALUES (NULL, NOW(), '" . $select['uitdager'] . "', '" . $bericht_draw_opp . "', '0')");
            }

            mysql_query("DELETE FROM races WHERE race_id = '" . $_GET['id'] . "' AND code = '" . $_GET['code'] . "' AND tegenstander = '" . $_SESSION['id'] . "'");
            echo '<div class="green"><img src="images/icons/green.png"> ' . $txt['success_accepted'] . '</div>';


        }
    }
}
?>