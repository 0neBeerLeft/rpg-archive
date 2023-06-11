<?
function getSetting($setting) {

    global $db;

    $query = "SELECT * FROM settings WHERE setting = :setting";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':setting', $setting, PDO::PARAM_STR);
    $stmt->execute();
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);

    return $settings['value'];

}

function getRealIpAddress() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

#Language event returnen naar goeie
function GetEventLanguage($land) {
    if (($land == 'Nederland') || ($land == 'Belgium'))
        return 'nl'; elseif ($land == 'Germany')
        return 'de';
    elseif ($land == 'Spain')
        return 'es';
    elseif ($land == 'Poland')
        return 'pl';
    else return 'nl';
}

#Money 1.000.000
function highamount($amount) {
    return number_format(round($amount), 0, ",", ".");
}

function ubbcode($tekst) {
    #Onnodige dingen weghalen
    $tekst = str_replace("<", "", $tekst);
    $tekst = str_replace(">", "", $tekst);

    #Enters in de textarea ook weergeven als een enter
    $tekst = nl2br($tekst);

    #Smilies
    $pad = "images/emoticons/";
    # UBB code => Bestandsnaam
    $smiley = array(":)" => "001.png", ":D" => "002.png", "xD" => "107.png", ":P" => "104.png", ";)" => "003.png", ":S" => "009.png", ":O" => "004.png", "8-)" => "050.png", "<o)" => "075.png", "(K)" => "028.png", "(BOO)" => "096.png", "(J)" => "086.png", "(V)" => "087.png", ":8)" => "088.png", ":@" => "099.png", ":$" => "008.png", ":-#" => "048.png", ":(" => "010.png", ":'(" => "011.png", ":|" => "012.png", "(H)" => "006.png", "(A)" => "014.png", "|-)" => "078.png", "(T)" => "034.png", "+o(" => "053.png", "(L)" => "015.png", ":[" => "043.png", "(G)" => "108.png", "(S)" => "109.png", ":'|" => "093.png", "(F)" => "025.png", "(Y)" => "041.png", "(N)" => "042.png");
    foreach ($smiley as $bb => $img)
        $tekst = preg_replace("#" . preg_quote($bb, '#') . "#i", "<img src=\"" . $pad . $img . "\" alt=\"" . $bb . "\" />", $tekst);

    #Als er geen [ inzit hoeft hij de rest niet te laden
    if (strpos($tekst, "[") === false) {
        return $tekst;
    }

    #Standaard ubb functies
    $tekst = preg_replace("#\[b\](.*?)\[/b\]#si", "<strong>\\1</strong>", $tekst);
    $tekst = preg_replace("#\[i\](.*?)\[/i\]#si", "<i>\\1</i>", $tekst);
    $tekst = preg_replace("#\[u\](.*?)\[/u\]#si", "<u>\\1</u>", $tekst);
    $tekst = preg_replace("#\[s\](.*?)\[/s\]#si", "<s>\\1</s>", $tekst);
    $tekst = preg_replace("#\[marquee\](.*?)\[/marquee\]#si", "<marquee>\\1</marquee>", $tekst);
    $tekst = preg_replace("#\[center\](.*?)\[/center\]#si", "<center>\\1</center>", $tekst);
    $tekst = preg_replace("#\[quote\](.*?)\[/quote\]#si", "<div class='quote'>\\1</div>", $tekst);
    $tekst = preg_replace("#\[player\](.*?)\[/player\]#si", "<a href=\"?page=profile&player=\\1\">\\1</a>", $tekst);

    #Balkje
    $tekst = str_replace("[HR]", "<HR />", $tekst);
    $tekst = str_replace("[line]", "<HR />", $tekst);
    #Kleuren
    $tekst = preg_replace("#\[color=(\#[0-9A-F]{6}|[a-z\-]+)\](.*?)\[/color\]#si", "<font color=\"\\1\">\\2</font>", $tekst);
    #URL
    $tekst = eregi_replace("\[url\][[:space:]]*(https://)?([^\\[]*)[[:space:]]*\[/url\]", "<a href=\"https://\\2\" target=\"_blank\">https://\\2</a>", $tekst);
    #Plaatje maken
    $tekst = eregi_replace("\\[img]([^\\[]*)\\[/img\\]", "<img src=\"\\1\" border=\"0\" OnLoad=\"if(this.width > 660) {this.width=660}\">", $tekst);
    #Youtuve player
    $tekst = preg_replace('_\[youtube\].*?(v=|v/)(.+?)(&.*?|/.*?)?\[/youtube\]_is', '[youtube]$2[/youtube]', $tekst);
    $tekst = preg_replace('_\[youtube\]([a-z0-9-]+?)\[/youtube\]_is', '<object width="425" height="355"><param name="movie" value="https://www.youtube.com/v/$1"></param><param name="wmode" value="transparent"></param><embed src="https://www.youtube.com/v/$1" type="application/x-shockwave-flash" wmode="transparent" width="425" height="355"></embed></object>', $tekst);
    #pokemon images
    $tekst = eregi_replace("\[animatie\]([^\[]+)\[/animatie\]", "<img src=\"images/pokemon/icon/\\1.gif\" border=\"0\">", $tekst);
    $tekst = eregi_replace("\[icon\]([^\[]+)\[/icon\]", "<img src=\"images/pokemon/icon/\\1.gif\" border=\"0\">", $tekst);
    $tekst = eregi_replace("\[icon_shiny\]([^\[]+)\[/icon_shiny\]", "<img src=\"images/shiny/icon/\\1.gif\" border=\"0\">", $tekst);
    $tekst = eregi_replace("\[back\]([^\[]+)\[/back\]", "<img src=\"images/pokemon/back/\\1.png\" border=\"0\">", $tekst);
    $tekst = eregi_replace("\[back_shiny\]([^\[]+)\[/back_shiny\]", "<img src=\"images/shiny/back/\\1.png\" border=\"0\">", $tekst);
    $tekst = eregi_replace("\[pokemon\]([^\[]+)\[/pokemon\]", "<img src=\"images/pokemon/\\1.png\" border=\"0\">", $tekst);
    $tekst = eregi_replace("\[shiny\]([^\[]+)\[/shiny\]", "<img src=\"images/shiny/\\1.png\" border=\"0\">", $tekst);


    return $tekst;
}

//Cache Query in txt
function query_cache($page, $query, $expire) {

    global $db;

    $file = 'cache/' . $page . '.txt';
    if (file_exists($file) && filemtime($file) > (time() - $expire)) {
        $createCache = unserialize(file_get_contents($file));

    } else {

        $createCache = $db->prepare($query);
        $createCache->execute();
        $createCache = $createCache->fetchAll();

        $OUTPUT = serialize($createCache);
        $fp = fopen($file, "w");
        fputs($fp, $OUTPUT);
        fclose($fp);

    }

    return $createCache;
}

//Cache Query in txt untill removed
function query_cache_onremoved($page, $query) {

    global $db;

    $file = 'cache/' . $page . '.txt';

    if (file_exists($file)) {
        $createCache = unserialize(file_get_contents($file));

    } else {

        $createCache = $db->prepare($query);
        $createCache->execute();
        $createCache = $createCache->fetchAll();

        $OUTPUT = serialize($createCache);
        $fp = fopen($file, "w");
        fputs($fp, $OUTPUT);
        fclose($fp);

    }

    return $createCache;
}

function query_cache_num($page, $query, $expire) {

    global $db;

    $file = 'cache/' . $page . '.txt';
    if (file_exists($file) && filemtime($file) > (time() - $expire)) {
        $createCache = unserialize(file_get_contents($file));

    } else {

        $createCache = $db->prepare($query);
        $createCache->execute();
        $createCache = $createCache->rowCount();

        $OUTPUT = serialize($createCache);
        $fp = fopen($file, "w");
        fputs($fp, $OUTPUT);
        fclose($fp);

    }

    return $createCache;
}

function update_pokedex($wild_id, $old_id, $wat) {
    $load = mysql_fetch_assoc(mysql_query("SELECT pok_gezien, pok_bezit, pok_gehad FROM gebruikers WHERE user_id ='" . $_SESSION['id'] . "'"));
    $pokedex_bezit = in_array($wild_id, explode(",", $load['pok_bezit']));
    $pokedex_gehad = in_array($wild_id, explode(",", $load['pok_gehad']));
    $pokedex_gehad_old = in_array($old_id, explode(",", $load['pok_gehad']));
    $pokedex_gezien = in_array($wild_id, explode(",", $load['pok_gezien']));
    if ($wat == 'ei') {
        if ($pokedex_gezien === false)
            $query = "`pok_gezien`=concat(pok_gezien,'," . $wild_id . "')";
        if ($pokedex_bezit === false)
            $query .= ",`pok_bezit`=concat(pok_bezit,'," . $wild_id . "')";
    } elseif ($wat == 'zien') {
        if ($pokedex_gezien === false)
            $query = "`pok_gezien`=concat(pok_gezien,'," . $wild_id . "')";
    } elseif ($wat == 'vangen') {
        if ($pokedex_bezit === false)
            $query = "`pok_bezit`=concat(pok_bezit,'," . $wild_id . "')";
    } elseif ($wat == 'release') {
        if ($pokedex_gehad === false)
            $query = "`pok_gehad`=concat(pok_gehad,'," . $wild_id . "')";
    } elseif ($wat == 'buy') {
        if ($pokedex_gezien === false)
            $query = "`pok_gezien`=concat(pok_gezien,'," . $wild_id . "')";
        if ($pokedex_bezit === false)
            $query .= ",`pok_bezit`=concat(pok_bezit,'," . $wild_id . "')";
    } elseif ($wat == 'evo') {
        if ($pokedex_gezien === false)
            $query = "`pok_gezien`=concat(pok_gezien,'," . $wild_id . "')";
        if ($pokedex_bezit === false)
            $query .= ",`pok_bezit`=concat(pok_bezit,'," . $wild_id . "')";
        if ($pokedex_gehad_old === false)
            $query .= ",`pok_gehad`=concat(pok_gehad,'," . $old_id . "')";
    }
    if (!empty($query))
        mysql_query("UPDATE gebruikers SET " . $query . " WHERE user_id='" . $_SESSION['id'] . "'");
}

//Calculate min/max price
function max_min_price($pokemon) {
    $pokemon['zeldzaamheid'] *= 10;
    $shinywaard = 150;
    if ($pokemon['shiny'] == 0)
        $shinywaard = 100;

    $waard = $pokemon['level'] * $pokemon['zeldzaamheid'] * 30 / 100 * $shinywaard;

    $maxprice = $waard * 1.5;
    if ($maxprice > 999)
        $maxprice += 1;

    $max_min['maxprice'] = $maxprice;
    $max_min['minimum'] = round($waard / 2);
    $max_min['waard'] = $waard;
    //Waard ff mooi maken
    $max_min['minimum_mooi'] = highamount($max_min['minimum']);
    $max_min['waard_mooi'] = highamount($waard);
    $max_min['maxprice_mooi'] = highamount($maxprice);
    return $max_min;
}

//Check if player can see the page
function page_timer($page, $timer) {
    $zien = array('home', 'account-options', 'pokemoninfo', 'rankinglist', 'statistics', 'forum-categories', 'forum-threads', 'forum-messages', 'promotion', 'modify-order', 'extended', 'items', 'house', 'pokedex', 'inbox', 'send-message', 'read-message', 'events', 'buddylist', 'blocklist', 'area-messenger', 'search-user', 'profile', 'logout', 'area-market', 'information');
    if ($timer == 'jail') {
        array_push($zien, "jail");
    }
    return in_array($page, $zien);
}

#Als speler er rank bij krijgt
function rankerbij($soort, $txt) {
    global $db;
    global $pokemonnaam;

    #Kijken wat speler gedaan heeft
    if ($soort == "race")
        $soort = 1; elseif ($soort == "standaard")
        $soort = 1;
    elseif ($soort == "werken")
        $soort = 2;
    elseif ($soort == "whoisitquiz")
        $soort = 2;
    elseif ($soort == "attack")
        $soort = 3;
    elseif ($soort == "jail")
        $soort = 3;
    elseif ($soort == "trainer")
        $soort = 4;
    elseif ($soort == "gym")
        $soort = 5;
    elseif ($soort == "duel")
        $soort = 5;

    //Kijken of de speler niet boven de max zit.
    $rankSelectQuery = "SELECT `land`, `rankexp`, `rankexpnodig`, `rank` FROM `gebruikers` WHERE `user_id`= :user_id";
    $stmt = $db->prepare($rankSelectQuery);
    $stmt->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();
    $spelerrank = $stmt->fetch(PDO::FETCH_ASSOC);

    $rank = rank($spelerrank['rank']);
    $uitkomst = round((($rank['ranknummer'] / 0.11) * $soort) / 3);

    $uitkomstQuery = "UPDATE `gebruikers` SET `rankexp`=`rankexp`+:uitkomst WHERE `user_id`=:user_id";
    $stmt = $db->prepare($uitkomstQuery);
    $stmt->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_STR);
    $stmt->bindParam(':uitkomst', $uitkomst, PDO::PARAM_STR);
    $stmt->execute();

    //Heeft speler genoeg punten om rank omhoog te gaan?
    $spelerrank['rankexp'] = $spelerrank['rankexp'] + $uitkomst;
    if ($spelerrank['rankexpnodig'] <= $spelerrank['rankexp']) {
        //Punten berekenen wat speler over heeft
        $rankexpover = $spelerrank['rankexp'] - $spelerrank['rankexpnodig'];
        //Nieuwe rank level bepalen
        $ranknieuw = $spelerrank['rank'] + 1;

        //Gegevens laden van de nieuwe ranklevel
        $ranknieuwQuery = "SELECT `naam`, `punten`, `naam` FROM `rank` WHERE `ranknummer`=:ranknieuw";
        $stmt = $db->prepare($ranknieuwQuery);
        $stmt->bindParam(':ranknieuw', $ranknieuw, PDO::PARAM_STR);
        $stmt->execute();
        $query = $stmt->fetch(PDO::FETCH_ASSOC);

        //Nieuwe gegevens opslaan bij de gebruiker
        if ($ranknieuw == 34){

            $rankNewQuery = "UPDATE `gebruikers` SET `rank`='33', `rankexp`='1', `rankexpnodig`='170000000' WHERE `user_id`=:user_id";
            $stmt = $db->prepare($rankNewQuery);
            $stmt->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_STR);
            $stmt->execute();
        }else{

            $rankNewQuery = "UPDATE `gebruikers` SET `rank`=:ranknieuw, `rankexp`=:rankexpover, `rankexpnodig`=:punten WHERE `user_id`=:user_id";
            $stmt = $db->prepare($rankNewQuery);
            $stmt->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_STR);
            $stmt->bindParam(':punten', $query['punten'], PDO::PARAM_STR);
            $stmt->bindParam(':rankexpover', $rankexpover, PDO::PARAM_STR);
            $stmt->bindParam(':ranknieuw', $ranknieuw, PDO::PARAM_STR);
            $stmt->execute();

        }

        //load event language
        $eventlanguage = GetEventLanguage($spelerrank['land']);
        include('language/events/language-events-'.$eventlanguage.'.php');
        $rank = rank($ranknieuw);

        if ($pokemonnaam) {
            $event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower" /> ' . $pokemonnaam . ' ' . $txt['event_is_level_up'];
        } else {
            $event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower" /> ' . $txt['event_rank_up'].' '.$rank['ranknaam'];;
        }
            #Melding geven aan de uitdager
            $query = "INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen,type)
	                    VALUES (NULL, NOW(), :user_id, :event, '0', '')";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_STR);
            $stmt->bindParam(':event', $event, PDO::PARAM_STR);
            $event = $stmt->execute();
    }
}

//Als speler er rank bij krijgt
function rankeraf($soort) {

    global $db;

    //Kijken wat speler gedaan heeft
    if ($soort == "werken")
        $soort = 1; elseif ($soort == "race")
        $soort = 1;
    elseif ($soort == "whoisitquiz")
        $soort = 2;
    elseif ($soort == "attack_run")
        $soort = 2;
    elseif ($soort == "attack_lose")
        $soort = 3;

    //Kijken als speler niet boven de max zit.
    $rankSelectQuery = "SELECT `rank` FROM `gebruikers` WHERE `user_id`= :user_id";
    $stmt = $db->prepare($rankSelectQuery);
    $stmt->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();
    $spelerrank = $stmt->fetch(PDO::FETCH_ASSOC);

    $rank = rank($spelerrank['rank']);
    $uitkomst = floor(($rank['ranknummer'] / 0.15) * $soort) / 3;

    $uitkomstQuery = "UPDATE `gebruikers` SET `rankexp`=`rankexp`-:uitkomst WHERE `user_id`=:user_id";
    $stmt = $db->prepare($uitkomstQuery);
    $stmt->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_STR);
    $stmt->bindValue(':uitkomst', $uitkomst, PDO::PARAM_STR);
    $stmt->execute();
}

//Berekenen als het effect moet gebeuren of niet.
function kans($nummer) {

    //Willekeurig getal nemen tussen 1 en 100
    $getal = rand(1, 100);
    //Als nummer bijv. 50 is word deze loop 50x uitgevoerd
    for ($i = 1; $i <= $nummer; $i++) {
        $kans = rand(1, 100);
        if ($getal == $kans)
            return true;
    }
    return false;
}

//Als pokemon aanval leert of evolueert
function levelgroei($levelnieuw, $pokemon) {
    global $db;

    //Gegevens laden van pokemon die leven groeit uit levelen tabel
    $rankSelectQuery = "SELECT `id`, `level`, `trade`, `wild_id`, `wat`, `nieuw_id`, `aanval` FROM `levelen` WHERE `wild_id`=:wild_id AND `stone`=''";
    $stmt = $db->prepare($rankSelectQuery);
    $stmt->bindParam(':wild_id', $pokemon['wild_id'], PDO::PARAM_INT);
    $stmt->execute();
    $levelensql = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //Voor elke actie kijken als het klopt.
    foreach ($levelensql as $levelen) {
        //als de actie een aanval leren is

        if ($levelen['wat'] == "att") {
            //Komt het benodigde level overeen
            if ($levelen['level'] == $levelnieuw) {
                //Kent de pokemon deze aanval al
                if (($pokemon['aanval_1'] != $levelen['aanval']) AND ($pokemon['aanval_2'] != $levelen['aanval']) AND ($pokemon['aanval_3'] != $levelen['aanval']) AND ($pokemon['aanval_4'] != $levelen['aanval'])) {
                    if ((empty($pokemon['aanval_1'])) OR (empty($pokemon['aanval_2'])) OR (empty($pokemon['aanval_3'])) OR (empty($pokemon['aanval_4']))) {
                        if (!empty($pokemon['aanval_1'])) {
                            $setAttack = "UPDATE `pokemon_speler` SET `aanval_1`=:aanval WHERE `id`=:pokemon";
                            $stmt = $db->prepare($setAttack);
                            $stmt->bindValue(':pokemon', $pokemon['id'], PDO::PARAM_STR);
                            $stmt->bindValue(':aanval', $levelen['aanval'], PDO::PARAM_STR);
                            $stmt->execute();

                        } elseif (!empty($pokemon['aanval_2'])) {
                            $setAttack = "UPDATE `pokemon_speler` SET `aanval_2`=:aanval WHERE `id`=:pokemon";
                            $stmt = $db->prepare($setAttack);
                            $stmt->bindValue(':pokemon', $pokemon['id'], PDO::PARAM_STR);
                            $stmt->bindValue(':aanval', $levelen['aanval'], PDO::PARAM_STR);
                            $stmt->execute();

                        } elseif (!empty($pokemon['aanval_3'])) {
                            $setAttack = "UPDATE `pokemon_speler` SET `aanval_3`=:aanval WHERE `id`=:pokemon";
                            $stmt = $db->prepare($setAttack);
                            $stmt->bindValue(':pokemon', $pokemon['id'], PDO::PARAM_STR);
                            $stmt->bindValue(':aanval', $levelen['aanval'], PDO::PARAM_STR);
                            $stmt->execute();

                        } elseif (!empty($pokemon['aanval_4'])) {
                            $setAttack = "UPDATE `pokemon_speler` SET `aanval_4`=:aanval WHERE `id`=:pokemon";
                            $stmt = $db->prepare($setAttack);
                            $stmt->bindValue(':pokemon', $pokemon['id'], PDO::PARAM_STR);
                            $stmt->bindValue(':aanval', $levelen['aanval'], PDO::PARAM_STR);
                            $stmt->execute();

                        }
                        if (!$_SESSION['aanvalnieuw'])
                            $_SESSION['aanvalnieuw'] = base64_encode($pokemon['id'] . "/" . $levelen['aanval']);
                    }
                }
            } //gaat de pokemon evolueren
        } elseif ($levelen['wat'] == "evo") {
            //Is het level groter of gelijk aan de level die benodigd is? Naar andere pagina gaan
            if (($levelen['level'] <= $levelnieuw) OR (($levelen['trade'] == 1) AND ($pokemon['trade'] == "1.5"))) {
                $code = base64_encode($pokemon['id'] . "/" . $levelen['nieuw_id']);
                if (!$_SESSION['evolueren']) {
                    $_SESSION['evolueren'] = $code;
                } elseif ((!$_SESSION['evolueren2']) && ($_SESSION['evolueren'] != $code)) {
                    $_SESSION['evolueren2'] = $code;
                } elseif ((!$_SESSION['evolueren3']) && ($_SESSION['evolueren'] != $code) && ($_SESSION['evolueren2'] != $code)) {
                    $_SESSION['evolueren3'] = $code;
                } elseif ((!$_SESSION['evolueren4']) && ($_SESSION['evolueren'] != $code) && ($_SESSION['evolueren2'] != $code) && ($_SESSION['evolueren3'] != $code)) {
                    $_SESSION['evolueren4'] = $code;
                } elseif ((!$_SESSION['evolueren5']) && ($_SESSION['evolueren'] != $code) && ($_SESSION['evolueren2'] != $code) && ($_SESSION['evolueren3'] != $code) && ($_SESSION['evolueren4'] != $code)) {
                    $_SESSION['evolueren5'] = $code;
                } elseif ((!$_SESSION['evolueren6']) && ($_SESSION['evolueren'] != $code) && ($_SESSION['evolueren2'] != $code) && ($_SESSION['evolueren3'] != $code) && ($_SESSION['evolueren4'] != $code) && ($_SESSION['evolueren5'] != $code)) {
                    $_SESSION['evolueren6'] = $code;
                }
            } else {
                return true;
            }
        }
    }
}

//Als pokemon level groeit
function nieuwestats($pokemon, $levelnieuw, $nieuwexp) {

    global $db;

    //Gegevens opzoeken in de experience tabel en karakter tabel
    $explevel = $levelnieuw + 1;
    if ($explevel < 101){

        $infoQuery = "SELECT experience.punten, karakters.* 
                      FROM experience INNER JOIN karakters 
                      WHERE experience.soort=:groei AND experience.level=:explevel AND karakters.karakter_naam=:karakter";
        $stmt = $db->prepare($infoQuery);
        $stmt->bindParam(':groei', $pokemon['groei'], PDO::PARAM_STR);
        $stmt->bindParam(':explevel', $explevel, PDO::PARAM_STR);
        $stmt->bindParam(':karakter', $pokemon['karakter'], PDO::PARAM_STR);
        $stmt->execute();
        $info = $stmt->fetch(PDO::FETCH_ASSOC);

    }else {

        $infoQuery = "SELECT * FROM karakters WHERE karakter_naam=:karakter";
        $stmt = $db->prepare($infoQuery);
        $stmt->bindParam(':karakter', $pokemon['karakter'], PDO::PARAM_STR);
        $stmt->execute();
        $info = $stmt->fetch(PDO::FETCH_ASSOC);
        $info['punten'] = 0;
    }
    //Exp bereken dat de pokemon over gehouden heeft en mee neemt naar het volgend level.
    $expover = $nieuwexp - $pokemon['expnodig'];
    //Nieuwe stats en hp berekenen
    //Bron: https://www.upokecenter.com/games/rs/guides/id.html
    //Stats berekenen
    //Formule Stats = int((int(int(A*2+B+int(C/4))*D/100)+5)*E)
    $attackstat = round(((((($pokemon['attack_base'] * 2 + $pokemon['attack_iv'] + floor($pokemon['attack_ev'] / 4)) * $levelnieuw / 100) + 5) * 1) + $pokemon['attack_up']) * $info['attack_add']);
    $defencestat = round(((((($pokemon['defence_base'] * 2 + $pokemon['defence_iv'] + floor($pokemon['defence_ev'] / 4)) * $levelnieuw / 100) + 5) * 1) + $pokemon['defence_up']) * $info['defence_add']);
    $speedstat = round(((((($pokemon['speed_base'] * 2 + $pokemon['speed_iv'] + floor($pokemon['speed_ev'] / 4)) * $levelnieuw / 100) + 5) * 1) + $pokemon['speed_up']) * $info['speed_add']);
    $spcattackstat = round(((((($pokemon['spc.attack_base'] * 2 + $pokemon['spc.attack_iv'] + floor($pokemon['spc.attack_ev'] / 4)) * $levelnieuw / 100) + 5) * 1) + $pokemon['spc_up']) * $info['spc.attack_add']);
    $spcdefencestat = round(((((($pokemon['spc.defence_base'] * 2 + $pokemon['spc.defence_iv'] + floor($pokemon['spc.defence_ev'] / 4)) * $levelnieuw / 100) + 5) * 1) + $pokemon['spc_up']) * $info['spc.defence_add']);
    $hpstat = round(((((($pokemon['hp_base'] * 2 + $pokemon['hp_iv'] + floor($pokemon['hp_ev'] / 4)) * $levelnieuw / 100) + $levelnieuw) + 10) + $pokemon['hp_up']) * $info['speed_add']);

    //Stats opslaan
    $saveStatsQuery = "UPDATE `pokemon_speler` SET 
                        `level`=:levelnieuw, 
                        `levenmax`=:hpstat, 
                        `leven`=:hpstat, 
                        `exp`=:expover, 
                        `expnodig`=:punten, 
                        `attack`=:attackstat, 
                        `defence`=:defencestat, 
                        `speed`=:speedstat, 
                        `spc.attack`=:spcattackstat, 
                        `spc.defence`=:spcdefencestat, 
                        `effect`='', 
                        `hoelang`='' 
                        WHERE `id`=:pokemonUid";
    $stmt = $db->prepare($saveStatsQuery);
    $stmt->bindParam(':levelnieuw', $levelnieuw, PDO::PARAM_STR);
    $stmt->bindParam(':hpstat', $hpstat, PDO::PARAM_STR);
    $stmt->bindParam(':expover', $expover, PDO::PARAM_STR);
    $stmt->bindParam(':punten', $info['punten'], PDO::PARAM_STR);
    $stmt->bindParam(':attackstat', $attackstat, PDO::PARAM_STR);
    $stmt->bindParam(':defencestat', $defencestat, PDO::PARAM_STR);
    $stmt->bindParam(':speedstat', $speedstat, PDO::PARAM_STR);
    $stmt->bindParam(':spcattackstat', $spcattackstat, PDO::PARAM_STR);
    $stmt->bindParam(':spcdefencestat', $spcdefencestat, PDO::PARAM_STR);
    $stmt->bindParam(':pokemonUid', $pokemon['id'], PDO::PARAM_STR);
    $stmt->execute();

    return $info['punten'];
}

//Tabel welke pokemon level je tegenkomt
function rankpokemon($ranknummer) {
    /*
    Ranks en per rank de levels van de wilde pokemon
    1. Newbie		5
    2. Junior		5-10
    3. Senior		5-15
    4. Casual		8-20
    5. Trainer		10-25
    6. Great Trainer	13-30
    7. Traveller		15-35
    8. Macho		18-40
    9. Gym Leader		20-45
    10. Shiny Trainer	25-50
    11. Elite Trainer	28-55
    12. Commander		30-60
    13. Professional	33-65
    14. Hero		35-70
    15. King 		38-75
    16. Champion		40-80
    17. Legendary		43-85
    18. Untouchable		45-90
    19. God			48-95
    20. Pokemon Master	50-100
    */

    if ($ranknummer == 1)
        return 5; elseif ($ranknummer == 2)
        return rand(5, 10);
    elseif ($ranknummer == 3)
        return rand(5, 15);
    elseif ($ranknummer == 4)
        return rand(8, 20);
    elseif ($ranknummer == 5)
        return rand(10, 25);
    elseif ($ranknummer == 6)
        return rand(13, 30);
    elseif ($ranknummer == 7)
        return rand(15, 35);
    elseif ($ranknummer == 8)
        return rand(18, 40);
    elseif ($ranknummer == 9)
        return rand(20, 45);
    elseif ($ranknummer == 10)
        return rand(25, 50);
    elseif ($ranknummer == 11)
        return rand(28, 55);
    elseif ($ranknummer == 12)
        return rand(30, 60);
    elseif ($ranknummer == 13)
        return rand(33, 65);
    elseif ($ranknummer == 14)
        return rand(35, 70);
    elseif ($ranknummer == 15)
        return rand(38, 75);
    elseif ($ranknummer == 16)
        return rand(40, 80);
    elseif ($ranknummer == 17)
        return rand(43, 85);
    elseif ($ranknummer == 18)
        return rand(45, 90);
    elseif ($ranknummer == 19)
        return rand(48, 95);
    elseif ($ranknummer == 20)
        return rand(50, 100);
    else return 5;
}

function pokemon_popup($pokemon, $txt) {
    $img = "";
    $pokemon['powertotal'] = $pokemon['attack'] + $pokemon['defence'] + $pokemon['speed'] + $pokemon['spc.attack'] + $pokemon['spc.defence'];
    if ($pokemon['shiny'] == 1)
        $img = "<img src=&quot;images/icons/lidbetaald.png&quot; style=&quot;margin-bottom: -3px;&quot;>";
    if ($pokemon['gehecht'] == 1)
        $gehecht = "<tr><td><strong>" . $txt['popup_begin'] . "</strong></td><td style=&quot;padding-left: 4px;&quot;><img src=&quot;images/icons/friend.png&quot;></td></tr>"; else $gehecht = "";
    if ($pokemon['aanval_2'] == "")
        $aanval2 = ""; else $aanval2 = "2. " . $pokemon['aanval_2'];
    if ($pokemon['aanval_3'] == "")
        $aanval3 = ""; else $aanval3 = "3. " . $pokemon['aanval_3'];
    if ($pokemon['aanval_4'] == "")
        $aanval4 = ""; else $aanval4 = "4. " . $pokemon['aanval_4'];

    return '<table width=&quot;288&quot; border=&quot;0&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot;><tr><td><strong>#:</strong></td><td style=&quot;padding-left: 2px;&quot;>' . $pokemon['wild_id'] . '</td></tr><tr><td><strong>' . $txt['popup_pokemon'] . '</strong></td><td style=&quot;padding-left: 2px;&quot;>' . $pokemon['def_naam'] . $img . '</td></tr><tr><td><strong>' . $txt['popup_clamour_name'] . '</strong></td><td style=&quot;padding-left: 2px;&quot;>' . $pokemon['roepnaam'] . '</td></tr><tr><td><strong>' . $txt['popup_type'] . '</strong></td><td>' . $pokemon['type'] . '</td></tr><tr><td><strong>' . $txt['popup_level'] . '</strong></td><td style=&quot;padding-left: 2px;&quot;>' . $pokemon['level'] . '</td></tr><tr><td><strong>' . $txt['popup_mood'] . '</strong></td><td style=&quot;padding-left: 2px;&quot;>' . $pokemon['karakter'] . '</td></tr><tr><td><strong>' . $txt['pop_up_powertotal'] . '</strong></td><td style=&quot;padding-left: 2px;&quot;>' . $pokemon['powertotal'] . '</td></tr>' . $gehecht . '<tr><td><strong>' . $txt['popup_ball'] . '</strong></td><td style=&quot;padding-left: 0px;&quot;><img src=&quot;images/items/' . $pokemon['gevongenmet'] . '.png&quot;></td></tr><tr><td colspan=&quot;2&quot;>&nbsp;</td></tr><tr><td><strong>' . $txt['popup_attacks'] . '</strong></td><td>&nbsp;</td></tr><tr><td width=&quot;144&quot;>1. ' . $pokemon['aanval_1'] . '</td><td width=&quot;144&quot;>' . $aanval2 . '</td></tr><tr><td>' . $aanval3 . '</td><td>' . $aanval4 . '</td></tr></table><table width=&quot;300&quot; border=&quot;0&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; style=&quot;padding-top:8px;&quot;><tr><td width=&quot;34&quot;><strong>' . $txt['popup_hp'] . '</strong></td><td width=&quot;266&quot;><div class=&quot;bar_red&quot;><div class=&quot;progress&quot; style=&quot;width: ' . $pokemon['levenprocent'] . '%;&quot;></div></div></td></tr><tr><td><strong>' . $txt['popup_exp'] . '</strong></td><td><div class=&quot;bar_blue&quot;><div class=&quot;progress&quot; style=&quot;width: ' . $pokemon['expprocent'] . '%;&quot;></div></div></td></tr></table>';
}

//Ranknaam bepalen a.d.v ranknummer(a)
function rank($ranknummer) {

    global $db;

    //Gegevens laden vanaf ranknummer
    $getRank = "SELECT `naam` FROM `rank` WHERE `ranknummer`=:ranknummer";
    $stmt = $db->prepare($getRank);
    $stmt->bindParam(':ranknummer', $ranknummer, PDO::PARAM_STR);
    $stmt->execute();
    $query = $stmt->fetch(PDO::FETCH_ASSOC);

    //Gegevens opstellen
    $rank['ranknummer'] = $ranknummer;
    $rank['ranknaam'] = $query['naam'];
    //Gegevens terug sturen
    return $rank;
}

//Maak pokemon naam goed ivm roepnaam & male/female
function pokemon_naam($oud, $roepnaam) {
    $new_name = $oud;
    //Heeft de pokemon een roepnaam
    if (!empty($roepnaam))
        $new_name = $roepnaam; //Staat er een f/m achter de naam Male/Female Character maken
    elseif (eregi(' ', $oud)) {
        $pokemon = explode(" ", $oud);
        if ($pokemon[1] == "f")
            $new_name = $pokemon[0] . " &#9792;"; elseif ($pokemon[1] == "m")
            $new_name = $pokemon[0] . " &#9794;";
        else $new_name = $oud;
    }
    //Nieuw naam terug sturen
    return $new_name;
}

//Maak Computer naam goed ivm male/female
function computer_naam($old) {
    //Staat er een f/m achter de naam Male/Female Character maken
    if (eregi(' ', $old)) {
        $pokemon = explode(" ", $old);
        if ($pokemon[1] == "f")
            return $pokemon[0] . " &#9792;"; elseif ($pokemon[1] == "m")
            return $pokemon[0] . " &#9794;";
        else return $old;
    } //Naam bevat geen spatie
    else return $old;
}

//Pokemonei function
function pokemonei($geg) {
    if ($geg['ei'] == 1) {
        $ei = True;
        //Beide tijden opvragen, en strtotime van maken
        $tijdtoen = strtotime($geg['ei_tijd']);
        $tijdnu = strtotime(date('Y-m-d H:i:s'));
        //Is er geen tijd dus niet goed geactieveerd, geen pokemon
        if ($tijdtoen == "") {
            //Link maken voor het plaatje van de pokemon
            $new['animatie'] = "images/icons/egg.gif";
            $new['little'] = "images/icons/egg_big.gif";
            $new['link'] = "images/icons/egg_big.gif";
            //Geen leven opgeven
            $new['levenproc'] = "";
            //Andere naam voor de pokemon en de level
            $new['naam'] = "";
            $new['level'] = "";
            $new['ei'] = 1;
        } //Als het verschil minder dan 600 sec is, dan hele ei
        elseif ($tijdnu - $tijdtoen < 300) {
            //Bereken hoeveel tijd er nog over is
            $new['tijdover'] = 600 - ($tijdnu - $tijdtoen);
            $new['afteltijd'] = strftime("%M:%S", $new['tijdover']);
            //Link maken voor het plaatje van de pokemon
            $new['animatie'] = "images/icons/egg.gif";
            $new['little'] = "images/icons/egg_big.gif";
            $new['link'] = "images/icons/egg_big.gif";
            //Geen leven opgeven
            $new['levenproc'] = "Nog " . $new['afteltijd'] . " tot het ei uitkomt";
            //Alles andere naam toewijzen
            $new['ei'] = 1;
            $new['wild_id'] = '??';
            $new['naam'] = "??";
            $new['def_naam'] = "??";
            $new['roepnaam'] = "??";
            $new['id'] = $geg['id'];
            $new['attack'] = "??";
            $new['leven'] = "??";
            $new['levenmax'] = "??";
            $new['defence'] = "??";
            $new['type1'] = "??";
            $new['type2'] = "??";
            $new['speed'] = "??";
            $new['level'] = "??";
            $new['exp'] = "??";
            $new['totalexp'] = "??";
            $new['expnodig'] = "??";
            $new['spcattack'] = "??";
            $new['spcdefence'] = "??";
            $new['lvl_hook'] = "(lvl ??)";
            $new['level_1'] = "-";
            $new['type'] = "<div style=&quot;padding-left:2px;&quot;>??</div>";
            $new['gevongenmet'] = "Poke ball";
            $new['karakter'] = "??";
            $new['aanval_1'] = "??";
            $new['aanval_2'] = "??";
            $new['aanval_3'] = "??";
            $new['aanval_4'] = "??";
        } //Als het verschil meer dan 600 sec is maar minder dan 900 dan halve ei
        elseif ($tijdnu - $tijdtoen < 600) {
            //Bereken hoeveel tijd er nog over is
            $new['tijdover'] = 600 - ($tijdnu - $tijdtoen);
            $new['afteltijd'] = strftime("%M:%S", $new['tijdover']);
            //Link maken voor het plaatje van de pokemon
            $new['link'] = "images/icons/egg_big.gif";
            $new['little'] = "images/icons/egg_big.gif";
            $new['animatie'] = "images/icons/egg_hatching.gif";
            //Geen leven opgeven
            $new['levenproc'] = "Nog " . $new['afteltijd'] . " tot het ei uitkomt";
            //Alles andere naam toewijzen
            $new['ei'] = 1;
            $new['wild_id'] = '??';
            $new['naam'] = "??";
            $new['def_naam'] = "??";
            $new['roepnaam'] = "??";
            $new['shiny'] = 0;
            $new['id'] = $geg['id'];
            $new['attack'] = "??";
            $new['leven'] = "??";
            $new['levenmax'] = "??";
            $new['defence'] = "??";
            $new['type1'] = "??";
            $new['type2'] = "??";
            $new['speed'] = "??";
            $new['level'] = "??";
            $new['exp'] = "??";
            $new['totalexp'] = "??";
            $new['expnodig'] = "??";
            $new['spcattack'] = "??";
            $new['spcdefence'] = "??";
            $new['lvl_hook'] = "(lvl ??)";
            $new['lvl_stripe'] = "-";
            $new['type'] = "<div style=&quot;padding-left:2px;&quot;>??</div>";
            $new['gevongenmet'] = "Poke ball";
            $new['karakter'] = "??";
            $new['aanval_1'] = "??";
            $new['aanval_2'] = "??";
            $new['aanval_3'] = "??";
            $new['aanval_4'] = "??";
        } else $ei = False;
    } else $ei = False;
    if (!$ei) {
        //Link maken voor het plaatje van de pokemon \
        foreach ($geg as $k => $v) {
            if (!is_numeric($k))
                $new[$k] = $v;
        }
        $new['ei'] = 0;
        $new['naamklein'] = strtolower($geg['naam']);

        ##**##
        if ($geg['shiny'] == 1) {
            $new['link'] = "images/shiny/" . $new['wild_id'] . ".gif";
            $new['animatie'] = "images/shiny/icon/" . $new['wild_id'] . ".gif";
        } else {
            $new['link'] = "images/pokemon/" . $new['wild_id'] . ".gif";
            $new['animatie'] = "images/pokemon/icon/" . $new['wild_id'] . ".gif";
        }
        #Andere naam voor de pokemon en de level
        #Alles andere naam toewijzen
        $new['karakter'] = ucfirst($geg['karakter']);
        $new['def_naam'] = $geg['naam'];
        if (empty($geg['roepnaam']))
            $new['roepnaam'] = $geg['naam']; else {
            $new['roepnaam'] = $geg['roepnaam'];
            $new['naam'] = $geg['naam'];
        }
        if ($geg['leven'] > 0)
            $new['levenprocent'] = round(($geg['leven'] / $geg['levenmax']) * 100); else $new['levenprocent'] = 0;
        if ($geg['expnodig'] > 0)
            $new['expprocent'] = round(($geg['exp'] / $geg['expnodig']) * 100); else $new['expprocent'] = 0;
        $new['levenmin100'] = 100 - $new['levenprocent'];
        $new['type1'] = strtolower($geg['type1']);
        $new['type2'] = strtolower($geg['type2']);
        //Heeft de pokemon twee types?
        if (empty($new['type2']))
            $new['type'] = '<table><tr><td><div class=&quot;type ' . $new['type1'] . '&quot;>' . $new['type1'] . '</div></td></tr></table>'; else $new['type'] = '<table><tr><td><div class=&quot;type ' . $new['type1'] . '&quot;>' . $new['type1'] . '</div></td><td> <div class=&quot;type ' . $new['type2'] . '&quot;>' . $new['type2'] . '</div></td></tr></table>';
        $new['lvl_hook'] = "(lvl " . $geg['level'] . ")";
        $new['level_1'] = $geg['level'];
        $new['expmin100'] = 100 - $new['expprocent'];
        $new['spcattack'] = $geg['spc.attack'];
        $new['spcdefence'] = $geg['spc.defence'];
    }
    return $new;
}

function getCurrentMusic($page) {
    if ($page == "account-options" or $page == "promotion" or $page == "buddylist" or $page == "blocklist") {
        echo "<iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/103391758&amp;color=3395e8&amp;auto_play=true&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false\"></iframe>";
    } elseif ($page == "information" or $page == "ranklist" or $page == "search-user" or $page == "statistics" or $page == "rankinglist" or $page == "home" or $page == "forum-categories" or $page == "extended" or $page == "modify-order" or $page == "modify-order-old" or $page == "house" or $page == "release" or $page == "items" or $page == "badges" or $page == "pokedex") {
        echo "<iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/103392445&amp;color=3395e8&amp;auto_play=true&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false\"></iframe>";
    } elseif ($page == "attack/attack_map" or $page == "work" or $page == "travel" or $page == "fishing") {
        echo "<iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/103392286&amp;color=3395e8&amp;auto_play=true&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false\"></iframe>";
    } elseif ($page == "attack/wild/wild-attack") {
        echo "<iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/103391800&amp;color=3395e8&amp;auto_play=true&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false\"></iframe>";
    } elseif ($page == "attack/trainer/trainer-attack") {
        echo "<iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/102617182&amp;color=3395e8&amp;auto_play=true&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false\"></iframe>";
    } elseif ($page == "pokemoncenter") {
        echo "<iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/103391814&amp;color=3395e8&amp;auto_play=true&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false\"></iframe>";
    } elseif ($page == "attack/gyms") {
        echo "<iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/103392216&amp;color=3395e8&amp;auto_play=true&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false\"></iframe>";
    } elseif ($page == "town" or $page == "bank" or $page == "daycare" or $page == "name-specialist" or $page == "shiny-specialist" or $page == "market" or $page == "pokemarket") {
        echo "<iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/102616707&amp;color=3395e8&amp;auto_play=true&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false\"></iframe>";
    } elseif ($page == "casino" or $page == "flip-a-coin" or $page == "who-is-it-quiz" or $page == "wheel-of-fortune" or $page == "poke-scrambler" or $page == "kluis" or $page == "mystery-gift") {
        echo "<iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/103392413&amp;color=3395e8&amp;auto_play=true&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false\"></iframe>";
    } elseif ($page == "sell" or $page == "transferlist") {
        echo "<iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/103393033&amp;color=3395e8&amp;auto_play=true&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false\"></iframe>";
    } elseif ($page == "jail") {
        echo "<iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/103392765&amp;color=3395e8&amp;auto_play=true&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false\"></iframe>";
    } elseif ($page == "trade-center") {
        echo "<iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/102616707&amp;color=3395e8&amp;auto_play=true&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false\"></iframe>";
    } elseif ($page == "spy") {
        echo "<iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/103393033&amp;color=3395e8&amp;auto_play=true&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false\"></iframe>";
    } elseif ($page == "attack/duel/invite" or $page == "race-invite") {
        echo "<iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/102617182&amp;color=3395e8&amp;auto_play=true&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false\"></iframe>";
    } elseif ($page == "clan-make" or $page == "clan-rank" or $page == "clan-profile" or $page == "clan-invite") {
        echo "<iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/103392445&amp;color=3395e8&amp;auto_play=true&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false\"></iframe>";
    } else {
        echo "<iframe width=\"100%\" height=\"166\" scrolling=\"no\" frameborder=\"no\" src=\"https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/103392445&amp;color=3395e8&amp;auto_play=true&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false\"></iframe>";
    }
}

function getBans($to, $from, $type) {

    global $db;

    $getBans = "SELECT * FROM ban WHERE gebruiker = :to AND banned = :fromU AND type = :typeB";
    $stmt = $db->prepare($getBans);
    $stmt->bindParam(':to', $to, PDO::PARAM_STR);
    $stmt->bindParam(':fromU', $from, PDO::PARAM_STR);
    $stmt->bindParam(':typeB', $type, PDO::PARAM_STR);
    $stmt->execute();
    $banQueryResult = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($banQueryResult['banned'] == $from) {
        return true;
    } else {
        return false;
    }

}

function refresh($time = null, $url = null) {

    if ($url != "") {
        echo "<meta http-equiv='refresh' content='{$time}' url='{$url}'>";
    } else {
        if ($time != "" && $time >= 0 && $time <= 60) {
            echo "<meta http-equiv='refresh' content='{$time}'>";
        } else {
            echo "<meta http-equiv='refresh' content='3'>";
        }
    }
}

function showAlert($type, $message) {

    $alert = '<div id="notification" class=' . $type . '>' . $message . '</div>';

    return $alert;

}

function showToastr($type, $message) {

    $toast = '<script>toastr["' . $type . '"]("' . $message . '")</script>';

    return $toast;

}

function getUserID($username) {

    global $db;

    $getUser = $db->prepare("SELECT `user_id` FROM `gebruikers` WHERE `username`=:username");
    $getUser->bindParam(':username', $username, PDO::PARAM_STR);
    $getUser->execute();
    $getUser = $getUser->fetch();

    if ($getUser) {
        return $getUser['user_id'];
    }
}

function getUsername($userId) {

    global $db;

    $getUser = $db->prepare("SELECT `username` FROM `gebruikers` WHERE `user_id`=:userid");
    $getUser->bindParam(':userid', $userId, PDO::PARAM_STR);
    $getUser->execute();
    $getUser = $getUser->fetch();

    if ($getUser) {
        return $getUser['username'];
    }
}

function find_mobile_browser() {
    if (preg_match('/(iphone|android|ipad|ipod|smartphone|mobile)/i', $_SERVER['HTTP_USER_AGENT'])) {
        return true;
    } else {
        return false;
    }
}

function replaceEmoticons($content) {
    $replaceWith = array("&lt;3" => "<img src='../images/shoutbox_icons/emoticon_heart.png' alt='heart' title='heart' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "(y)" => "<img src='../images/shoutbox_icons/emoticon_thumbsup.png' alt='thumbsup' title='thumbsup' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "(Y)" => "<img src='../images/shoutbox_icons/emoticon_thumbsup.png' alt='thumbsup' title='thumbsup' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "(V)" => "<img src='../images/shoutbox_icons/emoticon_peace.png' alt='peace' title='peace' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "(v)" => "<img src='../images/shoutbox_icons/emoticon_peace.png' alt='peace' title='peace' style='border:none;height:16px;width:16px;vertical-align: middle;' />", ":)" => "<img src='../images/shoutbox_icons/emoticon_smile.png' alt='smile' title='smile' style='border:none;height:16px;width:16px;vertical-align: middle;' />", ":-)" => "<img src='../images/shoutbox_icons/emoticon_smile.png' alt='smile' title='smile' style='border:none;height:16px;width:16px;vertical-align: middle;' />", ":=)" => "<img src='../images/shoutbox_icons/emoticon_smile.png' alt='smile' title='smile' style='border:none;height:16px;width:16px;vertical-align: middle;' />", ":=]" => "<img src='../images/shoutbox_icons/emoticon_happy.png' alt='happy' title='happy' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "=]" => "<img src='../images/shoutbox_icons/emoticon_happy.png' alt='happy' title='happy' style='border:none;height:16px;width:16px;vertical-align: middle;' />", ":-d" => "<img src='../images/shoutbox_icons/emoticon_grin.png' alt='grin' title='grin' style='border:none;height:16px;width:16px;vertical-align: middle;' />", ":d" => "<img src='../images/shoutbox_icons/emoticon_grin.png' alt='grin' title='grin' style='border:none;height:16px;width:16px;vertical-align: middle;' />", ":-D" => "<img src='../images/shoutbox_icons/emoticon_grin.png' alt='grin' title='grin' style='border:none;height:16px;width:16px;vertical-align: middle;' />", ":D" => "<img src='../images/shoutbox_icons/emoticon_grin.png' alt='grin' title='grin' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "x-d" => "<img src='../images/shoutbox_icons/emoticon_evilgrin.png' alt='evilgrin' title='evilgrin' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "xd" => "<img src='../images/shoutbox_icons/emoticon_evilgrin.png' alt='evilgrin' title='evilgrin' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "x-D" => "<img src='../images/shoutbox_icons/emoticon_evilgrin.png' alt='evilgrin' title='evilgrin' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "xD" => "<img src='../images/shoutbox_icons/emoticon_evilgrin.png' alt='evilgrin' title='evilgrin' style='border:none;height:16px;width:16px;vertical-align: middle;' />", ":(" => "<img src='../images/shoutbox_icons/emoticon_sad.png' alt='sad' title='sad' style='border:none;height:16px;width:16px;vertical-align: middle;' />", ":-(" => "<img src='../images/shoutbox_icons/emoticon_sad.png' alt='sad' title='sad' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "8)" => "<img src='../images/shoutbox_icons/emoticon_cool.png' alt='cool' title='cool' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "8-)" => "<img src='../images/shoutbox_icons/emoticon_cool.png' alt='cool' title='cool' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "B)" => "<img src='../images/shoutbox_icons/emoticon_cool.png' alt='cool' title='cool' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "B|" => "<img src='../images/shoutbox_icons/emoticon_cool.png' alt='cool' title='cool' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "(H)" => "<img src='../images/shoutbox_icons/emoticon_cool.png' alt='cool' title='cool' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "(h)" => "<img src='../images/shoutbox_icons/emoticon_cool.png' alt='cool' title='cool' style='border:none;height:16px;width:16px;vertical-align: middle;' />", ":o" => "<img src='../images/shoutbox_icons/emoticon_surprised.png' alt='surprised' title='surprised' style='border:none;height:16px;width:16px;vertical-align: middle;' />", ":-o" => "<img src='../images/shoutbox_icons/emoticon_surprised.png' alt='surprised' title='surprised' style='border:none;height:16px;width:16px;vertical-align: middle;' />", ":O" => "<img src='../images/shoutbox_icons/emoticon_surprised.png' alt='surprised' title='surprised' style='border:none;height:16px;width:16px;vertical-align: middle;' />", ":-O" => "<img src='../images/shoutbox_icons/emoticon_surprised.png' alt='surprised' title='surprised' style='border:none;height:16px;width:16px;vertical-align: middle;' />", ":P" => "<img src='../images/shoutbox_icons/emoticon_tongue.png' alt='tongue' title='tongue' style='border:none;height:16px;width:16px;vertical-align: middle;' />", ":-P" => "<img src='../images/shoutbox_icons/emoticon_tongue.png' alt='tongue' title='tongue' style='border:none;height:16px;width:16px;vertical-align: middle;' />", ":p" => "<img src='../images/shoutbox_icons/emoticon_tongue.png' alt='tongue' title='tongue' style='border:none;height:16px;width:16px;vertical-align: middle;' />", ":-p" => "<img src='../images/shoutbox_icons/emoticon_tongue.png' alt='tongue' title='tongue' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "3)" => "<img src='../images/shoutbox_icons/emoticon_waii.png' alt='waii' title='waii' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "3-)" => "<img src='../images/shoutbox_icons/emoticon_waii.png' alt='waii' title='waii' style='border:none;height:16px;width:16px;vertical-align: middle;' />", ";)" => "<img src='../images/shoutbox_icons/emoticon_wink.png' alt='wink' title='wink' style='border:none;height:16px;width:16px;vertical-align: middle;' />", ";-)" => "<img src='../images/shoutbox_icons/emoticon_wink.png' alt='wink' title='wink' style='border:none;height:16px;width:16px;vertical-align: middle;' />", ":@" => "<img src='../images/shoutbox_icons/emoticon_angry.png' alt='angry' title='angry' style='border:none;height:16px;width:16px;vertical-align: middle;' />", ":'(" => "<img src='../images/shoutbox_icons/emoticon_crying.png' alt='crying' title='crying' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "[gold]" => "<img src='../images/shoutbox_icons/emoticon_gold.png' alt='gold' title='gold' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "[silver]" => "<img src='../images/shoutbox_icons/emoticon_silver.png' alt='silver' title='silver' style='border:none;height:16px;width:16px;vertical-align: middle;' />");
    $content = strtr($content, $replaceWith);
    return $content;
}

function showEmoticons() {
    $emoticons = array(array("symbols" => "&lt;3", "icon" => "<img src='../images/shoutbox_icons/emoticon_heart.png' alt='heart' title='heart' style='border:none;height:16px;width:16px;vertical-align: middle;' />"), array("symbols" => "(Y)", "icon" => "<img src='../images/shoutbox_icons/emoticon_thumbsup.png' alt='thumbsup' title='thumbsup' style='border:none;height:16px;width:16px;vertical-align: middle;' />"), array("symbols" => "(V)", "icon" => "<img src='../images/shoutbox_icons/emoticon_peace.png' alt='peace' title='peace' style='border:none;height:16px;width:16px;vertical-align: middle;' />"), array("symbols" => ":)", "icon" => "<img src='../images/shoutbox_icons/emoticon_smile.png' alt='smile' title='smile' style='border:none;height:16px;width:16px;vertical-align: middle;' />"), array("symbols" => ":-)", "icon" => "<img src='../images/shoutbox_icons/emoticon_smile.png' alt='smile' title='smile' style='border:none;height:16px;width:16px;vertical-align: middle;' />"), array("symbols" => "=]", "icon" => "<img src='../images/shoutbox_icons/emoticon_happy.png' alt='happy' title='happy' style='border:none;height:16px;width:16px;vertical-align: middle;' />"), array("symbols" => ":D", "icon" => "<img src='../images/shoutbox_icons/emoticon_grin.png' alt='grin' title='grin' style='border:none;height:16px;width:16px;vertical-align: middle;' />"), array("symbols" => "xD", "icon" => "<img src='../images/shoutbox_icons/emoticon_evilgrin.png' alt='evilgrin' title='evilgrin' style='border:none;height:16px;width:16px;vertical-align: middle;' />"), array("symbols" => ":(", "icon" => "<img src='../images/shoutbox_icons/emoticon_sad.png' alt='sad' title='sad' style='border:none;height:16px;width:16px;vertical-align: middle;' />"), array("symbols" => "(H)", "icon" => "<img src='../images/shoutbox_icons/emoticon_cool.png' alt='cool' title='cool' style='border:none;height:16px;width:16px;vertical-align: middle;' />"), array("symbols" => ":o", "icon" => "<img src='../images/shoutbox_icons/emoticon_surprised.png' alt='surprised' title='surprised' style='border:none;height:16px;width:16px;vertical-align: middle;' />"), array("symbols" => ":P", "icon" => "<img src='../images/shoutbox_icons/emoticon_tongue.png' alt='tongue' title='tongue' style='border:none;height:16px;width:16px;vertical-align: middle;' />"), array("symbols" => "3-)", "icon" => "<img src='../images/shoutbox_icons/emoticon_waii.png' alt='waii' title='waii' style='border:none;height:16px;width:16px;vertical-align: middle;' />"), array("symbols" => ";)", "icon" => "<img src='../images/shoutbox_icons/emoticon_wink.png' alt='wink' title='wink' style='border:none;height:16px;width:16px;vertical-align: middle;' />"), array("symbols" => ":@", "icon" => "<img src='../images/shoutbox_icons/emoticon_angry.png' alt='angry' title='angry' style='border:none;height:16px;width:16px;vertical-align: middle;' />"), array("symbols" => ":'(", "icon" => "<img src='../images/shoutbox_icons/emoticon_crying.png' alt='crying' title='crying' style='border:none;height:16px;width:16px;vertical-align: middle;' />"), array("symbols" => "[gold]", "icon" => "<img src='../images/shoutbox_icons/emoticon_gold.png' alt='gold' title='gold' style='border:none;height:16px;width:16px;vertical-align: middle;' />"), array("symbols" => "[silver]", "icon" => "<img src='../images/shoutbox_icons/emoticon_silver.png' alt='silver' title='silver' style='border:none;height:16px;width:16px;vertical-align: middle;' />"));
    return $emoticons;
}

function insertableEmoticons() {
    $emoticons = array("<img style='cursor: pointer;' onclick=\"insertSmiley('&lt;3')\" src='../images/shoutbox_icons/emoticon_heart.png' alt='heart' title='heart' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "<img style='cursor: pointer;' onclick=\"insertSmiley('(Y)')\" src='../images/shoutbox_icons/emoticon_thumbsup.png' alt='thumbsup' title='thumbsup' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "<img style='cursor: pointer;' onclick=\"insertSmiley('(V)')\" src='../images/shoutbox_icons/emoticon_peace.png' alt='peace' title='peace' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "<img style='cursor: pointer;' onclick=\"insertSmiley(':-)')\" src='../images/shoutbox_icons/emoticon_smile.png' alt='smile' title='smile' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "<img style='cursor: pointer;' onclick=\"insertSmiley('=]')\" src='../images/shoutbox_icons/emoticon_happy.png' alt='happy' title='happy' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "<img style='cursor: pointer;' onclick=\"insertSmiley(':D')\" src='../images/shoutbox_icons/emoticon_grin.png' alt='grin' title='grin' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "<img style='cursor: pointer;' onclick=\"insertSmiley('xD')\" src='../images/shoutbox_icons/emoticon_evilgrin.png' alt='evilgrin' title='evilgrin' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "<img style='cursor: pointer;' onclick=\"insertSmiley(':(')\" src='../images/shoutbox_icons/emoticon_sad.png' alt='sad' title='sad' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "<img style='cursor: pointer;' onclick=\"insertSmiley('(H)')\" src='../images/shoutbox_icons/emoticon_cool.png' alt='cool' title='cool' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "<img style='cursor: pointer;' onclick=\"insertSmiley(':o')\" src='../images/shoutbox_icons/emoticon_surprised.png' alt='surprised' title='surprised' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "<img style='cursor: pointer;' onclick=\"insertSmiley(':P')\" src='../images/shoutbox_icons/emoticon_tongue.png' alt='tongue' title='tongue' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "<img style='cursor: pointer;' onclick=\"insertSmiley('3-)')\" src='../images/shoutbox_icons/emoticon_waii.png' alt='waii' title='waii' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "<img style='cursor: pointer;' onclick=\"insertSmiley(';)')\" src='../images/shoutbox_icons/emoticon_wink.png' alt='wink' title='wink' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "<img style='cursor: pointer;' onclick=\"insertSmiley(':@')\" src='../images/shoutbox_icons/emoticon_angry.png' alt='angry' title='angry' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "<img style='cursor: pointer;' onclick=\"insertSmiley(':\'(')\" src='../images/shoutbox_icons/emoticon_crying.png' alt='crying' title='crying' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "<img style='cursor: pointer;' onclick=\"insertSmiley('[gold]')\" src='../images/shoutbox_icons/emoticon_gold.png' alt='gold' title='gold' style='border:none;height:16px;width:16px;vertical-align: middle;' />", "<img style='cursor: pointer;' onclick=\"insertSmiley('[silver]')\" src='../images/shoutbox_icons/emoticon_silver.png' alt='silver' title='silver' style='border:none;height:16px;width:16px;vertical-align: middle;' />");
    return $emoticons;
}

function getYoutubeID($url) {
    $pattern = '%^# Match any youtube URLgetYoutubeID
        (?:https?://)?  # Optional scheme. Either http or https
        (?:www\.)?      # Optional www subdomain
        (?:             # Group host alternatives
          youtu\.be/    # Either youtu.be,
        | youtube\.com  # or youtube.com
          (?:           # Group path alternatives
            /embed/     # Either /embed/
          | /v/         # or /v/
          | /watch\?v=  # or /watch\?v=
          )             # End path alternatives.
        )               # End host alternatives.
        ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
        $%x';
    $result = preg_match($pattern, $url, $matches);
    if ($result) {
        return $matches[1];
    }
    return false;
}

function dropKans($sum) {

    if ($sum <= 2) {
        $kansGroterDan = 9990;

    } elseif ($sum <= 3) {
        $kansGroterDan = 9991;

    } elseif ($sum <= 4) {
        $kansGroterDan = 9992;

    } elseif ($sum <= 5) {
        $kansGroterDan = 9993;

    } elseif ($sum <= 6) {
        $kansGroterDan = 9994;

    } elseif ($sum <= 7) {
        $kansGroterDan = 9995;

    } elseif ($sum <= 8) {
        $kansGroterDan = 9996;

    } elseif ($sum <= 9) {
        $kansGroterDan = 9997;

    } elseif ($sum <= 10) {
        $kansGroterDan = 9998;

    } else {
        $kansGroterDan = 9999;

    }

    return $kansGroterDan;
}