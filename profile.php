<?php
if (isset($_GET['player'])) {

    $page = 'profile';
    //Goeie taal erbij laden voor de page
    include_once('language/language-pages.php');

    //Gegevens laden van de ingevoerde gebruiker
    $profiel = $db->prepare("SELECT g.user_id, g.username, g.youtube, g.profielfoto, g.cover, g.hasStore, g.profilestore, g.views, g.respect, g.datum, g.email, g.ip_aangemeld, g.ip_ingelogd, g.silver, g.gold, g.bank, g.premiumaccount, g.admin, g.wereld, g.online, CONCAT(g.voornaam,' ',g.achternaam) AS combiname, g.land, g.`character`, g.profiel, g.buddieszien, g.teamzien, g.buddy, g.badgeszien, g.rank, g.wereld, g.clan, g.aantalpokemon, g.badges, g.gewonnen, g.verloren, COUNT(DISTINCT g.user_id) AS 'check', gi.`Badge case`	 FROM gebruikers AS g 
											INNER JOIN gebruikers_item AS gi 
											ON g.user_id = gi.user_id
											WHERE username=:player
											AND account_code != '0'
											GROUP BY `user_id`");
    $profiel->bindParam(':player', $_GET['player'], PDO::PARAM_STR);
    $profiel->execute();
    $profiel = $profiel->fetch();

    //is er geen player ingevuld dan echo
    if ($profiel['check'] != 1) {
        header("Location: index.php?page=home");
        //Anders de pagina
    } else {
        //update last view
        if($profiel['user_id'] != $_SESSION['id'] and $gebruiker['admin'] != 3) {

            $query = "UPDATE gebruikers SET views = views +1 WHERE user_id = :user_id";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':user_id', $profiel['user_id'], PDO::PARAM_STR);
            $stmt->execute();
        }
        if($profiel['respect'] == 0){
            $profiel['respect'] = '0';
        }

        $query = "SELECT `username` FROM `gebruikers` WHERE `account_code`='1' ORDER BY `rank` DESC, `rankexp` DESC, `username` ASC";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':user_id', $profiel['user_id'], PDO::PARAM_STR);
        $stmt->execute();
        $plaatssql = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //Default Values
        $medaille = "";
        $star = '';
        $plaatje = "images/icons/status_offline.png";
        $online = $txt['offline'];

        //Alle leden laten zien
        //Is het lid dat voorbij komt het zelfde als de gebruiker waar naar gekeken word
        $j = 1;
        foreach ($plaatssql as $plaats) {
            if ($profiel['username'] == $plaats['username']) {
                $voortgang = $j;
            }
            $j++;
        }
        $voortgangplaats = $voortgang . "<sup>e</sup>";

        if ($voortgang == '1') {
            $medaille = "<img src='images/icons/plaatsnummereen.png'>";
            $voortgangplaats = $voortgang . "<sup>ste</sup>";
        } elseif ($voortgang == '2')
            $medaille = "<img src='images/icons/plaatsnummertwee.png'>";
        elseif ($voortgang == '3')
            $medaille = "<img src='images/icons/plaatsnummerdrie.png'>";
        elseif ($voortgang > '3' && $voortgang <= '10')
            $medaille = "<img src='images/icons/gold_medaille.png'>";
        elseif ($voortgang > '10' && $voortgang <= '30')
            $medaille = "<img src='images/icons/silver_medaille.png'>";
        elseif ($voortgang > '30' && $voortgang <= '50')
            $medaille = "<img src='images/icons/bronze_medaille.png'>";
        elseif ($voortgang == '')
            $voortgangplaats = "<b>niet bekend.</b>";

        //Tijd voor plaatje
        $tijd = time();
        if (($profiel['online'] + 1000) > $tijd) {
            $plaatje = "images/icons/status_online.png";
            $online = $txt['online'];
        }

        #Als diegene premium is sterretje 8er zn naam
        if ($profiel['premiumaccount'] >= 1)
            $star = '<img src="images/icons/lidbetaald.png" width="16" height="16" border="0" alt="Premiumlid" title="Premiumlid" style="margin-bottom:-3px;">';

        //Rank naam laden
        $rank = rank($profiel['rank']);

        //Datum mooi maken
        $datum = explode("-", $profiel['datum']);
        $tijd = explode(" ", $datum[2]);
        $datum = $tijd[0] . "-" . $datum[1] . "-" . $datum[0] . ",&nbsp;" . $tijd[1];
        $date = substr_replace($datum, "", -3);

        $profile_silver = number_format(round($profiel['silver']), 0, ",", ".");
        $profile_gold = number_format(round($profiel['gold']), 0, ",", ".");
        $profile_bank = number_format(round($profiel['bank']), 0, ",", ".");

        if (empty($profiel['land'])) {
            $profiel['land'] = "Nederland";
        }
        ?>
        <div class="row m-container">
            <div class="cover">
                <div class="team">
                    <?
                    if ($profiel['teamzien'] == 1) {

                        $query = "SELECT pokemon_speler.*, pokemon_wild.naam, pokemon_wild.type1, pokemon_wild.type2
                                   FROM pokemon_speler
                                   INNER JOIN pokemon_wild
                                   ON pokemon_speler.wild_id = pokemon_wild.wild_id
                                   WHERE `user_id`=:user_id AND `opzak`='ja' ORDER BY `opzak_nummer` ASC";
                        $stmt = $db->prepare($query);
                        $stmt->bindValue(':user_id', $profiel['user_id'], PDO::PARAM_STR);
                        $stmt->execute();
                        $pokemon_profiel_sql = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        //Pokemons opzak weergeven op het scherm
                        foreach ($pokemon_profiel_sql as $pokemon_profile) {

                            $pokemon_profile = pokemonei($pokemon_profile);
                            $pokemon_profile['naam'] = pokemon_naam($pokemon_profile['naam'], $pokemon_profile['roepnaam']);
                            $popup = pokemon_popup($pokemon_profile, $txt);

                            echo '<img onMouseover="showhint(\'' . $popup . '\', this)" src="' . $pokemon_profile['link'] . '" alt="' . $pokemon_profile['naam'] . '" title="' . $pokemon_profile['naam'] . '"/>';
                        }
                    }
                    ?>
                </div>
                <img class="banner-img" src="<?php
                if ($profiel['cover']) {
                    echo $profiel['cover'];
                } else {
                    echo 'images/cover.png';
                } ?>" alt="" title="">

                <img class="profile-pic" src="<?php
                if ($profiel['profielfoto']) {
                    echo $profiel['profielfoto'];
                } else {
                    echo 'images/you/Ash-red.png';
                } ?>" alt="" title="">

                <div class="profile-btn">
                    <? if ($_SESSION['id'] != '') { ?>
                        <a href="?page=buddies&buddy=<? echo $profiel['username']; ?>" class="button">Toevoegen als
                            buddy</a>
                        <a href="?page=send-message&player=<? echo $profiel['username']; ?>" class="button">Stuur
                            bericht</a>
                    <?
                    } else { ?>
                        <a href="?page=register" class="button">Registreren</a>
                    <?
                    } ?>
                </div>
            </div>
        </div>
        <div class="row m-container">
            <?
            //verwerk respect
            if (isset($_POST['respect'])) {

                if ($gebruiker['respect_add'] == 0) {
                   echo showAlert("red", 'Je hebt geen respect punten, kom morgen terug.');
                }
                elseif ($gebruiker['rank'] < 2) {
                    echo showAlert("red","Je kan pas respect punten geven vanaf rank twee.");
                }
                elseif ($profiel['user_id'] == $gebruiker['user_id']) {
                    echo showAlert("red","Je kan jezelf geen respect punten geven.");
                }

                else {

                    echo showAlert("green", '<img src="images/icons/respect-add.png" style="margin-bottom: -5px;"> Je hebt ' . $profiel['username'] . ' een respect punt gegeven.');

                    $event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower"> ' . $gebruiker["username"] . ' heeft je respect gegeven.';

                    $result = $db->prepare(
                        "UPDATE `gebruikers` SET `respect`=`respect`+'1' WHERE `user_id`=:toU;
                                    UPDATE `gebruikers` SET `respect_add`=`respect_add`-'1' WHERE `user_id`=:fromU;
                                    INSERT INTO gebeurtenis (datum, ontvanger_id, bericht, gelezen)  VALUES (NOW(), :toU, :event, '0');
                                    INSERT INTO respect_log (id, who, reciever, received_on, what) VALUES (NULL, :fromUname, :toUname, NOW(), '1')");
                    $result->bindValue(':toU', $profiel['user_id'], PDO::PARAM_INT);
                    $result->bindValue(':toUname', $profiel['username'], PDO::PARAM_STR);
                    $result->bindValue(':fromU', $gebruiker['user_id'], PDO::PARAM_INT);
                    $result->bindValue(':fromUname', $gebruiker['username'], PDO::PARAM_STR);
                    $result->bindValue(':event', $event, PDO::PARAM_STR);
                    $result = $result->execute();

                    if ($gebruiker['missie_5'] == 0) {

                        $result = $db->prepare("UPDATE `gebruikers` SET `missie_5`=1, `silver`=`silver`+1500,`rankexp`=rankexp+100 WHERE `user_id`=:fromU");
                        $result->bindValue(':fromU', $gebruiker['user_id'], PDO::PARAM_INT);
                        $result = $result->execute();

                        echo showToastr("info", "Je hebt een missie behaald!");
                    }
                }
                echo '<br/>';
            }
            if (isset($_POST['disrespect'])) {
                if ($gebruiker['respect_add'] == 0) {
                    echo showAlert("red", 'Je hebt geen respect punten, kom morgen terug.');
                }
                elseif ($gebruiker['rank'] < 2) {
                    echo showAlert("red","Je kan pas respect punten geven vanaf rank twee.");
                }
                elseif ($profiel['user_id'] == $gebruiker['user_id']) {
                    echo showAlert("red","Je kan jezelf geen respect punten geven.");
                }

                else {

                    echo showAlert("green",'<img src="images/icons/respect-remove.png" style="margin-bottom: -5px;"> Je hebt ' . $profiel['username'] . ' een respect punt afgenomen.');

                    $result = $db->prepare(
                        "UPDATE `gebruikers` SET `respect`=`respect`-'1' WHERE `user_id`=:toU;
                                    UPDATE `gebruikers` SET `respect_add`=`respect_add`+'1' WHERE `user_id`=:fromU;
                                    INSERT INTO gebeurtenis (datum, ontvanger_id, bericht, gelezen)  VALUES (NOW(), :toU, :event, '0');
                                    INSERT INTO respect_log (id, who, reciever, received_on, what) VALUES (NULL, :fromUname, :toUname, NOW(), '1')");
                    $result->bindValue(':toU', $profiel['user_id'], PDO::PARAM_INT);
                    $result->bindValue(':toUname', $profiel['username'], PDO::PARAM_STR);
                    $result->bindValue(':fromU', $gebruiker['user_id'], PDO::PARAM_INT);
                    $result->bindValue(':fromUname', $gebruiker['username'], PDO::PARAM_STR);
                    $result->bindValue(':event', $event, PDO::PARAM_STR);
                    $result = $result->execute();

                    if($gebruiker['missie_5'] == 0){

                        $result = $db->prepare("UPDATE `gebruikers` SET `missie_5`=1, `silver`=`silver`+1500,`rankexp`=rankexp+100 WHERE `user_id`=:fromU");
                        $result->bindValue(':fromU', $gebruiker['user_id'], PDO::PARAM_INT);
                        $result = $result->execute();

                        echo showToastr("info", "Je hebt een missie behaald!");
                    }
                }
            }
            ?>
            <!-- About me -->
            <div class="col-md-6">
                <div class="block">
                    <h2><? echo $profiel['username']; ?></h2>
                    <div class="block-body">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="60%"><strong><?php echo $txt['username']; ?></strong></td>
                                <td width="40%"><a
                                        href=javascript:chatWith('<? echo $profiel['username']; ?>')><?php echo $profiel['username']; ?></a><?php echo $star; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong><?php echo 'Clan:'; ?></strong></td>
                                <td><? echo '<a href="?page=clan-profile&clan=' . $profiel['clan'] . '">' . $profiel['clan'] . '</a>'; ?></td>
                            </tr>
                            <tr>
                                <td><strong><?php echo $txt['status']; ?></strong></td>
                                <td><img src="<? echo $plaatje; ?>"/><? echo $online; ?></td>
                            </tr>
                            <tr>
                                <td><strong><?php echo $txt['rank']; ?></strong></td>
                                <td><? echo $rank['ranknaam']; ?></td>
                            </tr>
                            <tr>
                                <td><strong><?php echo $txt['rank_number']; ?></strong></td>
                                <td><? echo $medaille; ?><? echo $voortgangplaats; ?></td>
                            </tr>
                            <tr>
                                <td><strong><?php echo $txt['country']; ?></strong></td>
                                <td><img src="images/flags/<? echo $profiel['land']; ?>.png"
                                         title="<? echo $profiel['land']; ?>"/></td>
                            </tr>
                            <tr>
                                <td><strong><?php echo $txt['date_started']; ?></strong></td>
                                <td><? echo $date; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="block">
                    <h2>Statistieken</h2>
                    <div class="block-body" style="height: 109px;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td><strong><?php echo $txt['badges_number']; ?></strong></td>
                                <td><? echo $profiel['badges']; ?></td>
                            </tr>
                            <tr>
                                <td><strong><?php echo $txt['pokemon']; ?></strong></td>
                                <td><? echo $profiel['aantalpokemon']; ?></td>
                            </tr>
                            <tr>
                                <td><strong><?php echo $txt['win']; ?></strong></td>
                                <td><? echo $profiel['gewonnen']; ?></td>
                            </tr>
                            <tr>
                                <td><strong><?php echo $txt['lost']; ?></strong></td>
                                <td><? echo $profiel['verloren']; ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                            <tr>
                                <td><strong>Profiel bekeken</strong></td>
                                <td><? echo number_format($profiel['views'], 0, ',', ' '); ?>x</td>
                            </tr>
                            <tr>
                                <td><strong>Respect</strong></td>
                                <td>
                                    <? echo $profiel['respect']; ?>
                                    <form class="pull-right" method="post" action="?page=profile&player=<? echo $profiel['username']; ?>">
                                        &nbsp;
                                        <button type="submit" name="respect" style="border: 0; background: transparent;cursor: pointer;">
                                            <img src="images/icons/respect-add.png" width="16" height="16" alt="Geef respect" title="Geef respect"  />
                                        </button>
                                        &nbsp;
                                        <button type="submit" name="disrespect" style="border: 0; background: transparent;cursor: pointer;">
                                            <img src="images/icons/respect-remove.png" width="16" height="16" alt="Geef respect" title="Geef respect"  />
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <?
            if (!empty($profiel['hasStore'])) {
                ?>
                <div class="col-md-12">
                    <div class="block">
                        <div class="block-body">
                            <div width="100%">
                                    <? if($profiel['profilestore']){
                                        echo $profiel['profilestore'];
                                    }else{
                                        echo '<center>Bezoek mijn store!</center><br/>';
                                    }?>
                                <center><a href="?page=store&player=<?=$profiel['username']?>">Ga naar <?=$profiel['username']?> zijn store.</a></center>
                            </div>
                        </div>
                    </div>
                </div>
                <?
            }
            ?>
            <?
            if (!empty($profiel['youtube'])) {
                ?>
                <div class="col-md-12">
                    <div class="block">
                        <div class="block-body">
                            <div width="100%">
                                <iframe width="610" height="415"
                                src="https://www.youtube.com/embed/<?=$profiel['youtube']?>">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
                <?
            }
            ?>
            <?
            if ($gebruiker['admin'] >= 1) {
                ?>
                <div class="col-md-12">
                    <div class="block">
                        <h2>Admin opties</h2>
                        <div class="block-body">
                            <div width="100%">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <?php
                                    if ($gebruiker['admin'] == 3 AND $_SESSION['id'] == GLOBALDEF_ADMINUID) {
                                        echo '<tr>
                                                  <td height="20" align="center" colspan="2"><a href="/?loginas=' . $profiel['user_id'] . '"><strong>Inloggen als '.$profiel['username'].'</strong></a></td>
                                                </tr>';
                                    }
                                    ?>
                                    <?php
                                    if ($gebruiker['admin'] >= 1) {
                                        echo '<tr>
                                                  <td height="20"><strong>' . $txt['name'] . '</strong></td>
                                                  <td>' . $profiel['combiname'] . '</td>
                                                </tr>';
                                    }
                                    ?>
                                    <?php
                                    if ($gebruiker['admin'] >= 1) {
                                        echo '
                    <tr>
                      <td height="20"><strong>ID:</strong></td>
                      <td height="20">' . $profiel['user_id'] . '</td>
                    </tr>
                    <tr>
                      <td height="20"><strong>' . $txt['world'] . '</strong></td>
                      <td height="20">' . $profiel['wereld'] . '</td>
                    </tr>
                    <tr>
                      <td height="20"><strong>' . $txt['silver'] . '</strong></td>
                      <td height="20"><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> ' . $profile_silver . '</td>
                    </tr>
                    <tr>
                      <td height="20"><strong>' . $txt['gold'] . '</strong></td>
                      <td height="20"><img src="images/icons/gold.png" title="Gold" style="margin-bottom:-3px;" /> ' . $profile_gold . '</td>
                    </tr>
                    <tr>
                      <td height="20"><strong>' . $txt['bank'] . '</strong></td>
                      <td height="20"><img src="images/icons/bank.png" title="Bank" style="margin-bottom:-3px;" /> ' . $profile_bank . '</td>
                    </tr>
                    <tr>
                      <td height="20" colspan="2">&nbsp;</td>
                    </tr>';
                                    }

                                    if ($gebruiker['admin'] == 1) {
                                        echo '
                      <tr>
                        <td height="20" colspan="2">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="20"><strong>' . $txt['email'] . '</strong></td>
                        <td>' . $profiel['email'] . '</td>
                      </tr>
                      <tr>
                        <td height="20"><strong>' . $txt['admin_options'] . '</strong></td>
                        <td><a href="index.php?page=admin/change-profile&player=' . $profiel['username'] . '">
                        <img src="images/icons/user_edit.png" width="16" height="16" alt="' . $txt['edit_profile'] . '" title="' . $txt['edit_profile'] . '" /></a></td>
                      </tr>';
                                    }
                                    if ($gebruiker['admin'] == 3 or $gebruiker['admin'] == 2) {
                                        echo '
                      <tr>
                        <td height="20" colspan="2">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="20"><strong>' . $txt['email'] . '</strong></td>
                        <td>' . $profiel['email'] . '</td>
                      </tr>
                      <tr>
                        <td height="20"><strong>' . $txt['ip_registered'] . '</strong></td>
                        <td><a href="index.php?page=admin/search-on-ip&ip=' . $profiel['ip_aangemeld'] . '&which=aangemeld">' . $profiel['ip_aangemeld'] . '</a></td>
                      </tr>
                      <tr>
                        <td height="20"><strong>' . $txt['ip_login'] . '</strong></td>
                        <td><a href="index.php?page=admin/search-on-ip&ip=' . $profiel['ip_ingelogd'] . '&which=ingelogd">' . $profiel['ip_ingelogd'] . '</a></td>
                      </tr>
                      <tr>
                        <td height="20"><strong>' . $txt['admin_options'] . '</strong></td>
                        <td><a href="index.php?page=admin/change-profile&player=' . $profiel['username'] . '">
                        <img src="images/icons/user_edit.png" width="16" height="16" alt="' . $txt['edit_profile'] . '" title="' . $txt['edit_profile'] . '" /></a> - <a href="index.php?page=admin/admins&player=' . $profiel['username'] . '"><img src="images/icons/user_admin.png" width="16" height="16" alt="' . $txt['make_admin'] . '" title="' . $txt['make_admin'] . '" /></a> - <a href="index.php?page=admin/give-egg&player=' . $profiel['user_id'] . '"><img src="images/icons/egg2.gif" width="16" height="16" alt="' . $txt['give_egg'] . '" title="' . $txt['give_egg'] . '"></a> - <a href="index.php?page=admin/give-pokemon&player=' . $profiel['user_id'] . '"><img src="images/icons/pokeball.gif" width="14" height="14" alt="' . $txt['give_pokemon'] . '" title="' . $txt['give_pokemon'] . '"></a> - <a href="index.php?page=admin/give-pack&player=' . $profiel['username'] . '"><img src="images/icons/basket_put.png" alt="' . $txt['give_pack'] . '" title="' . $txt['give_pack'] . '"></a></td>
                      </tr>';
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?
            }
            ?>
            <?
            if ($profiel['buddieszien'] == 1) {
                ?>
                <div class="col-md-12">
                    <div class="block">
                        <h2>Buddies</h2>
                        <div class="block-body">
                            <table width="100%">
                                <thead>
                                <tr>
                                    <th><?= $txt['username'] ?></th>
                                    <th><?= $txt['country'] ?></th>
                                    <th><?= $txt['status'] ?></th>
                                    <th>Bevriend sinds</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?
                                $q = "SELECT `online`, `username`, `land`, `date`,`premiumaccount`
                                FROM gebruikers G, friends F
                                WHERE
                                CASE
                        
                                WHEN F.to = '{$profiel['user_id']}'
                                THEN F.from = G.user_id
                                WHEN F.from = '{$profiel['user_id']}'
                                THEN F.to = G.user_id
                                END
                        
                                AND
                                F.status='1'";
                                $st = $db->prepare($q);

                                $ster = '';
                                

                                if ($st->execute()) {//if this returns true, the query was successful

                                    //loop through the query
                                    while ($friends = $st->fetch(PDO::FETCH_ASSOC)) {
                                    
                                        if (1000 + $friends['online'] >= time()) {
                                            $plaatje = "images/icons/status_online.png";
                                            $online = $txt['online'];
                                        } else {
                                            $online = $txt['offline'];
                                            $plaatje = "images/icons/status_offline.png";
                                        }
                                        if ($friends['premiumaccount'] > 0) $ster = '<img src="images/icons/lidbetaald.png" width="16" height="16" border="0" alt="Premiumlid" title="Premiumlid" style="margin-bottom:-3px;">';


                                        echo '<tr>';
                                        echo '<td><a href="?page=profile&player=' . $friends['username'] . '">' . $friends['username'] . $ster . '</a></td>';
                                        echo '<td><img src="images/flags/' . $friends['land'] . '.png"></td>';
                                        echo '<td><img src="' . $plaatje . '" width=18 height=15 />' . $online . '</td>';
                                        echo '<td>' . $friends['date'] . '</td>';
                                        echo '</tr>';
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?
            }
            ?>
            <?
            if ($profiel['badgeszien'] == 1 && $profiel['Badge case'] == 1) {
                ?>
                <div class="col-md-12">
                    <div class="block">
                        <h2>Badges</h2>
                        <div class="block-body">
                            <div width="100%">
                                <?

                                $query = "SELECT * FROM gebruikers_badges WHERE user_id =:user_id";
                                $stmt = $db->prepare($query);
                                $stmt->bindValue(':user_id', $profiel['user_id'], PDO::PARAM_INT);
                                $stmt->execute();
                                $badge = $stmt->fetch(PDO::FETCH_ASSOC);

                                echo '
          <center>
          <table width="600">
            <tr>
            <td><div id="badgebox"><strong>' . $txt['badges'] . ' Kanto</strong><br />';

                                if ($badge['Boulder'] == 1) echo '<img src="images/badges/Boulder.png" width="40" height="40" alt="Boulder Badge" title="Boulder Badge" />';
                                if ($badge['Cascade'] == 1) echo '<img src="images/badges/Cascade.png" width="40" height="40" alt="Cascade Badge" title="Cascade Badge" />';
                                if ($badge['Thunder'] == 1) echo '<img src="images/badges/Thunder.png" width="40" height="40" alt="Thunder Badge" title="Thunder Badge" />';
                                if ($badge['Rainbow'] == 1) echo '<img src="images/badges/Rainbow.png" width="40" height="40" alt="Rainbow Badge" title="Rainbow Badge" />';
                                if ($badge['Marsh'] == 1) echo '<img src="images/badges/Marsh.png" width="40" height="40" alt="Marsh Badge" title="Marsh Badge" />';
                                if ($badge['Soul'] == 1) echo '<img src="images/badges/Soul.png" width="40" height="40" alt="Soul Badge" title="Soul Badge" />';
                                if ($badge['Volcano'] == 1) echo '<img src="images/badges/Volcano.png" width="40" height="40" alt="Volcano Badge" title="Volcano Badge" />';
                                if ($badge['Earth'] == 1) echo '<img src="images/badges/Earth.png" width="40" height="40" alt="Earth Badge" title="Earth Badge" />';

                                if ($badge['Boulder'] == 0 && $badge['Cascade'] == 0 && $badge['Thunder'] == 0 && $badge['Rainbow'] == 0 && $badge['Marsh'] == 0 && $badge['Soul'] == 0 && $badge['Volcano'] == 0 && $badge['Earth'] == 0) echo $txt['no_badges_from'] . ' Kanto';

                                echo '</div>
			  <div id="badgebox"><strong>' . $txt['badges'] . ' Johto</strong><br />';

                                if ($badge['Zephyr'] == 1) echo '<img src="images/badges/Zephyr.png" width="40" height="40" alt="Zephyr Badge" title="Zephyr Badge" />';
                                if ($badge['Hive'] == 1) echo '<img src="images/badges/Hive.png" width="40" height="40" alt="Hive Badge" title="Hive Badge" />';
                                if ($badge['Plain'] == 1) echo '<img src="images/badges/Plain.png" width="40" height="40" alt="Plain Badge" title="Plain Badge" />';
                                if ($badge['Fog'] == 1) echo '<img src="images/badges/Fog.png" width="40" height="40" alt="Fog Badge" title="Fog Badge" />';
                                if ($badge['Storm'] == 1) echo '<img src="images/badges/Storm.png" width="40" height="40" alt="Storm Badge" title="Storm Badge" />';
                                if ($badge['Mineral'] == 1) echo '<img src="images/badges/Mineral.png" width="40" height="40" alt="Mineral Badge" title="Mineral Badge" />';
                                if ($badge['Glacier'] == 1) echo '<img src="images/badges/Glacier.png" width="40" height="40" alt="Glacier Badge" title="Glacier Badge" />';
                                if ($badge['Rising'] == 1) echo '<img src="images/badges/Rising.png" width="40" height="40" alt="Rising Badge" title="Rising Badge" />';

                                if ($badge['Zephyr'] == 0 && $badge['Hive'] == 0 && $badge['Plain'] == 0 && $badge['Fog'] == 0 && $badge['Storm'] == 0 && $badge['Mineral'] == 0 && $badge['Glacier'] == 0 && $badge['Rising'] == 0) echo $txt['no_badges_from'] . ' Johto';

                                echo '</div>
			  <div id="badgebox"><strong>' . $txt['badges'] . ' Hoenn</strong><br />';

                                if ($badge['Stone'] == 1) echo '<img src="images/badges/Stone.png" width="40" height="40" alt="Stone Badge" title="Stone Badge" />';
                                if ($badge['Knuckle'] == 1) echo '<img src="images/badges/Knuckle.png" width="40" height="40" alt="Knuckle Badge" title="Knuckle Badge" />';
                                if ($badge['Dynamo'] == 1) echo '<img src="images/badges/Dynamo.png" width="40" height="40" alt="Dynamo Badge" title="Dynamo Badge" />';
                                if ($badge['Heat'] == 1) echo '<img src="images/badges/Heat.png" width="40" height="40" alt="Heat Badge" title="Heat Badge" />';
                                if ($badge['Balance'] == 1) echo '<img src="images/badges/Balance.png" width="40" height="40" alt="Balance Badge" title="Balance Badge" />';
                                if ($badge['Feather'] == 1) echo '<img src="images/badges/Feather.png" width="40" height="40" alt="Feather Badge" title="Feather Badge" />';
                                if ($badge['Mind'] == 1) echo '<img src="images/badges/Mind.png" width="40" height="40" alt="Mind Badge" title="Mind Badge" />';
                                if ($badge['Rain'] == 1) echo '<img src="images/badges/Rain.png" width="40" height="40" alt="Rain Badge" title="Rain Badge" />';

                                if ($badge['Stone'] == 0 && $badge['Knuckle'] == 0 && $badge['Dynamo'] == 0 && $badge['Heat'] == 0 && $badge['Balance'] == 0 && $badge['Feather'] == 0 && $badge['Mind'] == 0 && $badge['Rain'] == 0) echo $txt['no_badges_from'] . ' Hoenn';

                                echo '</div>
			  <div id="badgebox"><strong>' . $txt['badges'] . ' Sinnoh</strong><br />';

                                if ($badge['Coal'] == 1) echo '<img src="images/badges/Coal.png" width="40" height="40" alt="Coal Badge" title="Coal Badge" />';
                                if ($badge['Forest'] == 1) echo '<img src="images/badges/Forest.png" width="40" height="40" alt="Forest Badge" title="Forest Badge" />';
                                if ($badge['Cobble'] == 1) echo '<img src="images/badges/Cobble.png" width="40" height="40" alt="Cobble Badge" title="Cobble Badge" />';
                                if ($badge['Fen'] == 1) echo '<img src="images/badges/Fen.png" width="40" height="40" alt="Fen Badge" title="Fen Badge" />';
                                if ($badge['Relic'] == 1) echo '<img src="images/badges/Relic.png" width="40" height="40" alt="Relic Badge" title="Relic Badge" />';
                                if ($badge['Mine'] == 1) echo '<img src="images/badges/Mine.png" width="40" height="40" alt="Mine Badge" title="Mine Badge" />';
                                if ($badge['Icicle'] == 1) echo '<img src="images/badges/Icicle.png" width="40" height="40" alt="Icicle Badge" title="Icicle Badge" />';
                                if ($badge['Beacon'] == 1) echo '<img src="images/badges/Beacon.png" width="40" height="40" alt="Beacon Badge" title="Beacon Badge" />';

                                if ($badge['Coal'] == 0 && $badge['Forest'] == 0 && $badge['Cobble'] == 0 && $badge['Fen'] == 0 && $badge['Relic'] == 0 && $badge['Mine'] == 0 && $badge['Icicle'] == 0 && $badge['Beacon'] == 0) echo $txt['no_badges_from'] . ' Sinnoh';

                                echo '</div>
			  <div id="badgebox"><strong>' . $txt['badges'] . ' Unova</strong><br />';

                                if ($badge['Trio'] == 1) echo '<img src="images/badges/Trio.png" width="40" height="40" alt="Trio Badge" title="Trio Badge" />';
                                if ($badge['Basic'] == 1) echo '<img src="images/badges/Basic.png" width="40" height="40" alt="Basic Badge" title="Basic Badge" />';
                                if ($badge['Insect'] == 1) echo '<img src="images/badges/Insect.png" width="40" height="40" alt="Insect Badge" title="Insect Badge" />';
                                if ($badge['Bolt'] == 1) echo '<img src="images/badges/Bolt.png" width="40" height="40" alt="Bolt Badge" title="Bolt Badge" />';
                                if ($badge['Quake'] == 1) echo '<img src="images/badges/Quake.png" width="40" height="40" alt="Quake Badge" title="Quake Badge" />';
                                if ($badge['Jet'] == 1) echo '<img src="images/badges/Jet.png" width="40" height="40" alt="Jet Badge" title="Jet Badge" />';
                                if ($badge['Freeze'] == 1) echo '<img src="images/badges/Freeze.png" width="40" height="40" alt="Freeze Badge" title="Freeze Badge" />';
                                if ($badge['Legend'] == 1) echo '<img src="images/badges/Legend.png" width="40" height="40" alt="Legend Badge" title="Legend Badge" />';

                                if ($badge['Trio'] == 0 && $badge['Basic'] == 0 && $badge['Insect'] == 0 && $badge['Bolt'] == 0 && $badge['Quake'] == 0 && $badge['Jet'] == 0 && $badge['Freeze'] == 0 && $badge['Legend'] == 0) echo $txt['no_badges_from'] . ' Unova';

                                echo '</div>
			  <div id="badgebox"><strong>' . $txt['badges'] . ' Kalos</strong><br />';

                                if ($badge['Bug'] == 1) echo '<img src="images/badges/Bug.png" width="40" height="40" alt="Bug Badge" title="Bug Badge" />';
                                if ($badge['Cliff'] == 1) echo '<img src="images/badges/Cliff.png" width="40" height="40" alt="Cliff Badge" title="Cliff Badge" />';
                                if ($badge['Rumble'] == 1) echo '<img src="images/badges/Rumble.png" width="40" height="40" alt="Rumble Badge" title="Rumble Badge" />';
                                if ($badge['Plant'] == 1) echo '<img src="images/badges/Plant.png" width="40" height="40" alt="Plant Badge" title="Plant Badge" />';
                                if ($badge['Voltage'] == 1) echo '<img src="images/badges/Voltage.png" width="40" height="40" alt="Voltage Badge" title="Voltage Badge" />';
                                if ($badge['Fairy'] == 1) echo '<img src="images/badges/Fairy.png" width="40" height="40" alt="Fairy Badge" title="Fairy Badge" />';
                                if ($badge['Psychic'] == 1) echo '<img src="images/badges/Psychic.png" width="40" height="40" alt="Psychic Badge" title="Psychic Badge" />';
                                if ($badge['Iceberg'] == 1) echo '<img src="images/badges/Iceberg.png" width="40" height="40" alt="Iceberg Badge" title="Iceberg Badge" />';

                                if ($badge['Bug'] == 0 && $badge['Cliff'] == 0 && $badge['Rumble'] == 0 && $badge['Plant'] == 0 && $badge['Voltage'] == 0 && $badge['Fairy'] == 0 && $badge['Psychic'] == 0 && $badge['Iceberg'] == 0) echo $txt['no_badges_from'] . ' Kalos';

                                echo '</div></td>
                </tr>
            </table>
            </center>';
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?
            }
            ?>

            <?
            if (!empty($profiel['profiel'])) {
                ?>
                <div class="col-md-12">
                    <div class="block">
                        <div class="block-body">
                            <div width="100%">
                                <?
                                if (!empty($profiel['profiel'])) echo $profiel['profiel'];
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?
            }
            ?>
        </div>
        <table width="600">
        </table>

        <?
    }
}