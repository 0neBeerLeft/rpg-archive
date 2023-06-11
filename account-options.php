<?php
#alleen toegankelijk als je bent ingelogd
include('includes/security.php');

$page = 'account-options';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

#Pagina's opbouwen.
switch ($_GET['category']) {

    #Persoonlijk openen
    case "personal":

        $persoonlijkerror = '&nbsp;';
        $username = $_POST['username'] == '' ? $gebruiker['username'] : $_POST['username'];
        $voornaam = $_POST['voornaam'] == '' ? $gebruiker['voornaam'] : $_POST['voornaam'];
        $achternaam = $_POST['achternaam'] == '' ? $gebruiker['achternaam'] : $_POST['achternaam'];
        $land = $_POST['land'] == '' ? $gebruiker['land'] : $_POST['land'];
        $teamzien = $_POST['teamzien'] == '' ? $gebruiker['teamzien'] : $_POST['teamzien'];
        $buddieszien = $_POST['buddieszien'] == '' ? $gebruiker['buddieszien'] : $_POST['buddieszien'];
        $muziekaan = $_POST['muziekaan'] == '' ? $gebruiker['muziekaan'] : $_POST['muziekaan'];
        $sneeuwaan = $_POST['sneeuwaan'] == '' ? $gebruiker['sneeuwaan'] : $_POST['sneeuwaan'];
        $badgeszien = $_POST['badgeszien'] == '' ? $gebruiker['badgeszien'] : $_POST['badgeszien'];
        $reclame = $_POST['reclame'] == '' ? $gebruiker['reclame'] : $_POST['reclame'];
        $battleScreen = $_POST['battleScreen'] == '' ? $gebruiker['battleScreen'] : $_POST['battleScreen'];
        $youtube = $_POST['youtube'];
        $dueluitnodiging = $_POST['dueluitnodiging'] == '' ? $gebruiker['dueluitnodiging'] : $_POST['dueluitnodiging'];
        $premiumtekst = '<a href="?page=area-market">' . $txt['buy_premium_here'] . '</a>';

        #Als er op de Verander knop gedrukt word.
        if (isset($_POST['persoonlijk'])) {

            if (strlen($voornaam > 12)) {
                $persoonlijkerror = '<div class="red">' . $txt['alert_firstname_too_long'] . '</div>';
            } elseif (strlen($achternaam > 12)) {
                $persoonlijkerror = '<div class="red">' . $txt['alert_lastname_too_long'] . '</div>';
            } elseif ($teamzien != '1' && $teamzien != '0') {
                $persoonlijkerror = '<div class="red">' . $txt['alert_seeteam_invalid'] . '</div>';
            } elseif ($badgeszien != '1' && $badgeszien != '0') {
                $persoonlijkerror = '<div class="red">' . $txt['alert_seebadges_invalid'] . '</div>';
            } elseif ($dueluitnodiging != '1' && $dueluitnodiging != '0') {
                $persoonlijkerror = '<div class="red">' . $txt['alert_duel_invalid'] . '</div>';
            } elseif ($buddieszien != '1' && $buddieszien != '0') {
                $persoonlijkerror = '<div class="red">' . $txt['alert_seebuddies_invalid'] . '</div>';
            } elseif ($sneeuwaan != '1' && $sneeuwaan != '0') {
                $persoonlijkerror = '<div class="red">' . $txt['alert_seesnow_invalid'] . '</div>';
            } elseif ($reclame != '1' && $reclame != '0') {
                $persoonlijkerror = '<div class="red">' . $txt['alert_advertisement_invalid'] . '</div>';
            } else {
                if ($youtube) {
                    $youtubeURL = getYoutubeID($youtube);
                } else {
                    $youtubeURL = '';
                }
                if ($reclame != 1) {
                    $reclame = 0;
                }

                $query = "UPDATE `gebruikers`
                        SET `voornaam`=:voornaam,
                        `achternaam`=:achternaam,
                        `youtube`=:youtubeURL,
                        `land`=:land,
                        `buddieszien`=:buddieszien,
                        `teamzien`=:teamzien,
                        `muziekaan`=:muziekaan,
                        `badgeszien`=:badgeszien,
                        `dueluitnodiging`=:dueluitnodiging,
                        `battleScreen`=:battleScreen,
                        `reclame`=:reclame,
                        `reclameAanSinds`= NOW(),
                        `sneeuwaan`=:sneeuwaan
                        WHERE `user_id`=:user_id";
                $stmt = $db->prepare($query);
                $stmt->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_INT);
                $stmt->bindValue(':sneeuwaan', $sneeuwaan, PDO::PARAM_STR);
                $stmt->bindValue(':reclame', $reclame, PDO::PARAM_STR);
                $stmt->bindValue(':battleScreen', $battleScreen, PDO::PARAM_STR);
                $stmt->bindValue(':dueluitnodiging', $dueluitnodiging, PDO::PARAM_STR);
                $stmt->bindValue(':badgeszien', $badgeszien, PDO::PARAM_STR);
                $stmt->bindValue(':muziekaan', $muziekaan, PDO::PARAM_STR);
                $stmt->bindValue(':teamzien', $teamzien, PDO::PARAM_STR);
                $stmt->bindValue(':buddieszien', $buddieszien, PDO::PARAM_STR);
                $stmt->bindValue(':land', $land, PDO::PARAM_STR);
                $stmt->bindValue(':youtubeURL', $youtubeURL, PDO::PARAM_STR);
                $stmt->bindValue(':achternaam', $achternaam, PDO::PARAM_STR);
                $stmt->bindValue(':voornaam', $voornaam, PDO::PARAM_STR);
                $stmt->execute();

                #Melding op het scherm weergeven dat het gelukt is
                $persoonlijkerror = '<div class="green">' . $txt['success_modified'] . '</div>';
                refresh(3, "?page=account-options&category=personal");
            }

            #Username ff fixen
            if ($username != $gebruiker['username']) {

                $checkUsername = $db->prepare("SELECT `username` FROM `gebruikers` WHERE `username`=:username");
                $checkUsername->bindParam(':username', $inlognaam, PDO::PARAM_STR);
                $checkUsername->execute();
                $checkUsername = $checkUsername->rowCount();

                if ($gebruiker['gold'] < 15) {
                    $persoonlijkerror = '<div class="red">' . $txt['alert_not_enough_gold'] . '</div>';
                } elseif (empty($username)) {
                    $persoonlijkerror = '<div class="red">' . $txt['alert_no_username'] . '</div>';
                } elseif (strlen($username) < 3) {
                    $persoonlijkerror = '<div class="red">' . $txt['alert_username_too_short'] . '</div>';
                } elseif (strlen($username > 10)) {
                    $persoonlijkerror = '<div class="red">' . $txt['alert_username_too_long'] . '</div>';
                } elseif ($checkUsername) {
                    $persoonlijkerror = '<div class="red">' . $txt['alert_username_already_taken'] . '</div>';
                } else {
                    #Gegevens opslaan
                    unset($checkUsername);

                    $query = "UPDATE `gebruikers` SET `username`=:username, `gold`=`gold`-'15' WHERE `user_id`=:user_id;
                                UPDATE `ban` SET `gebruiker`=:username WHERE `gebruiker`=:gusername;
                                UPDATE `ban` SET `banned`=:username WHERE `banned`=:gusername";
                    $stmt = $db->prepare($query);
                    $stmt->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_INT);
                    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
                    $stmt->bindValue(':gusername', $gebruiker['username'], PDO::PARAM_STR);
                    $stmt->execute();

                    $persoonlijkerror = '<div class="green">' . $txt['success_modified'] . '</div>';
                }
            }
        }

        #Kijken als speler premium account heeft
        if ($gebruiker['premiumaccount'] >= 1) $premiumtekst = '' . $gebruiker['premiumaccount'] . ' ' . $txt['days_left'];
        ?>

        <form method="post">
            <?php if (isset($_POST['persoonlijk'])) echo $persoonlijkerror; ?>
            <center>
                <table width="400" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="200" height="25"><?php echo $txt['premium_days']; ?></td>
                        <td width="200"><? echo $premiumtekst; ?></td>
                    </tr>
                    <tr>
                        <td height="25"><?php echo $txt['username']; ?></td>
                        <td><input type="text" name="username" class="text_long" value="<? echo $username; ?>"
                                   maxlength="10"/><img src="images/icons/gold.png"
                                                        title="<?php echo $txt['cost_15_gold']; ?>"
                                                        style="margin:0px 0px -3px 5px;"/> 15
                        </td>
                    </tr>
                    <tr>
                        <td height="25"><?php echo $txt['firstname']; ?></td>
                        <td><input type="text" name="voornaam" class="text_long" value="<? echo $voornaam; ?>"
                                   maxlength="12"/></td>
                    </tr>
                    <tr>
                        <td height="25"><?php echo $txt['lastname']; ?></td>
                        <td><input type="text" name="achternaam" class="text_long" value="<? echo $achternaam; ?>"
                                   maxlength="12"/></td>
                    </tr>
                    <tr>
                        <td height="25"><?php echo $txt['country']; ?></td>
                        <td><select name="land" class="text_select">
                                <?
                                $query = "SELECT `en`, `nl` FROM `landen`";
                                $stmt = $db->prepare($query);
                                $stmt->execute();
                                $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                foreach($countries as $country){
                                    if($land == $country[GLOBALDEF_LANGUAGE]){
                                        echo '<option value="'.$country[GLOBALDEF_LANGUAGE].'" selected>'.$country[GLOBALDEF_LANGUAGE].'</option>';
                                    }
                                    echo '<option value="'.$country[GLOBALDEF_LANGUAGE].'">'.$country[GLOBALDEF_LANGUAGE].'</option>';
                                }
                                ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td height="25"><?php echo $txt['youtube']; ?></td>
                        <td><input type="text" name="youtube" class="text_long" value="<? if ($gebruiker['youtube']) {
                                echo 'https://www.youtube.com/watch?v=' . $gebruiker['youtube'];
                            }; ?>"/></td>
                    </tr>
                    <tr>
                        <td height="25"><?php echo $txt['team_on_profile']; ?></td>
                        <td><?php
                            if ($teamzien == 1) {
                                echo '<input type="radio" name="teamzien" value="1" id="ja" checked /><label style="padding-right:17px"> ' . $txt['yes'] . '</label>
                                        <input type="radio" name="teamzien" value="0" id="nee" /><label> ' . $txt['no'] . '</label>';
                            } elseif ($teamzien == 0) {
                                echo '<input type="radio" name="teamzien" value="1" id="ja" /><label style="padding-right:17px"> ' . $txt['yes'] . '</label>
                                        <input type="radio" name="teamzien" value="0" id="nee" checked /><label> ' . $txt['no'] . '</label>';
                            } #Als er nog geen teamzien is
                            else {
                                echo '<input type="radio" name="teamzien" value="1" id="ja" /><label style="padding-right:17px"> ' . $txt['yes'] . '</label>
                                        <input type="radio" name="teamzien" value="0" id="nee" /><label> ' . $txt['no'] . '</label>';
                            } ?></td>
                    </tr>
                    <tr>
                        <td height="25"><?php echo $txt['buddies_on_profile']; ?></td>
                        <td><?php
                            if ($buddieszien == 1) {
                                echo '<input type="radio" name="buddieszien" value="1" id="ja" checked /><label style="padding-right:17px"> ' . $txt['yes'] . '</label>
                                        <input type="radio" name="buddieszien" value="0" id="nee" /><label> ' . $txt['no'] . '</label>';
                            } elseif ($buddieszien == 0) {
                                echo '<input type="radio" name="buddieszien" value="1" id="ja" /><label style="padding-right:17px"> ' . $txt['yes'] . '</label>
                                        <input type="radio" name="buddieszien" value="0" id="nee" checked /><label> ' . $txt['no'] . '</label>';
                            } #Als er nog geen buddieszien is
                            else {
                                echo '<input type="radio" name="buddieszien" value="1" id="ja" /><label style="padding-right:17px"> ' . $txt['yes'] . '</label>
                                        <input type="radio" name="buddieszien" value="0" id="nee" /><label> ' . $txt['no'] . '</label>';
                            } ?></td>
                    </tr>
                    <tr>
                        <td height="25"><?php echo $txt['snow_on']; ?></td>
                        <td><?php
                            if ($sneeuwaan == 1) {
                                echo '<input type="radio" name="sneeuwaan" value="1" id="ja" checked /><label style="padding-right:17px"> ' . $txt['yes'] . '</label>
                                        <input type="radio" name="sneeuwaan" value="0" id="nee" /><label> ' . $txt['no'] . '</label>';
                            } elseif ($sneeuwaan == 0) {
                                echo '<input type="radio" name="sneeuwaan" value="1" id="ja" /><label style="padding-right:17px"> ' . $txt['yes'] . '</label>
                                        <input type="radio" name="sneeuwaan" value="0" id="nee" checked /><label> ' . $txt['no'] . '</label>';
                            } #Als er nog geen sneeuwaan is
                            else {
                                echo '<input type="radio" name="sneeuwaan" value="1" id="ja" /><label style="padding-right:17px"> ' . $txt['yes'] . '</label>
                                        <input type="radio" name="sneeuwaan" value="0" id="nee" /><label> ' . $txt['no'] . '</label>';
                            } ?></td>
                    </tr>
                    <tr>
                        <td height="25"><?php echo $txt['badges_on_profile']; ?></td>
                        <td><?php
                            if ($gebruiker['Badge case'] == 0) {
                                echo $txt['alert_dont_have_badgebox'];
                            } else {

                                if ($badgeszien == 1) {
                                    echo '<input type="radio" name="badgeszien" value="1" id="badges1" checked /><label for="badges1" style="padding-right:17px"> ' . $txt['yes'] . '</label>
                                            <input type="radio" name="badgeszien" value="0" id="badges2" /><label for="badges2"> ' . $txt['no'] . '</label>';
                                } elseif ($badgeszien == 0) {
                                    echo '<input type="radio" name="badgeszien" value="1" id="badges1" /><label for="badges1" style="padding-right:17px"> ' . $txt['yes'] . '</label>
                                            <input type="radio" name="badgeszien" value="0" id="badges2" checked /><label for="badges2"> ' . $txt['no'] . '</label>';
                                } #Als er nog geen teamzien is
                                else {
                                    echo '<input type="radio" name="badgeszien" value="1" id="badges1" /><label for="badges1" style="padding-right:17px"> ' . $txt['yes'] . '</label>
                                            <input type="radio" name="badgeszien" value="0" id="badges2" /><label for="badges2"> ' . $txt['no'] . '</label>';
                                }
                            } ?></td>
                    </tr>
                    <tr>
                        <td height="25"><?php echo $txt['music_on']; ?></td>
                        <td><?php
                            if ($muziekaan == 1) {
                                echo '<input type="radio" name="muziekaan" value="1" id="ja" checked /><label style="padding-right:8px"> ' . $txt['on'] . '</label>
                                        <input type="radio" name="muziekaan" value="0" id="nee" /><label> ' . $txt['off'] . '</label>';
                            } elseif ($muziekaan == 0) {
                                echo '<input type="radio" name="muziekaan" value="1" id="ja" /><label style="padding-right:8px"> ' . $txt['on'] . '</label>
                                        <input type="radio" name="muziekaan" value="0" id="nee" checked /><label> ' . $txt['off'] . '</label>';
                            } #Als er nog geen muziekaan is
                            else {
                                echo '<input type="radio" name="muziekaan" value="1" id="ja" /><label style="padding-right:8px"> ' . $txt['on'] . '</label>
                                        <input type="radio" name="muziekaan" value="0" id="nee" /><label> ' . $txt['off'] . '</label>';
                            } ?></td>
                    </tr>
                    <tr>
                        <td height="25"><?php echo $txt['duel_invitation']; ?></td>
                        <td><?php

                            if ($dueluitnodiging == 1) {
                                echo '<input type="radio" name="dueluitnodiging" value="1" id="duel1" checked /><label for="duel1" style="padding-right:8px"> ' . $txt['on'] . '</label>
                                        <input type="radio" name="dueluitnodiging" value="0" id="duel2" /><label for="duel2"> ' . $txt['off'] . '</label>';
                            } elseif ($dueluitnodiging == 0) {
                                echo '<input type="radio" name="dueluitnodiging" value="1" id="duel1" /><label for="duel1" style="padding-right:8px"> ' . $txt['on'] . '</label>
                                        <input type="radio" name="dueluitnodiging" value="0" id="duel2" checked /><label for="duel2"> ' . $txt['off'] . '</label>';
                            } #Als er nog geen dueluitnodiging is
                            else {
                                echo '<input type="radio" name="dueluitnodiging" value="1" id="duel1" /><label for="duel1" style="padding-right:8px"> ' . $txt['on'] . '</label>
                                        <input type="radio" name="dueluitnodiging" value="0" id="duel2" /><label for="duel2"> ' . $txt['off'] . '</label>';
                            }
                            ?></td>
                    </tr>
                    <tr>
                        <td height="25"><?php echo $txt['advertisement']; ?></td>
                        <td><?php

                            if ($reclame == 1) {
                                echo '<input type="radio" name="reclame" value="1" id="reclame1" checked /><label for="reclame1" style="padding-right:8px"> ' . $txt['on'] . '</label>
                                    <input type="radio" name="reclame" value="0" id="reclame2" /><label for="reclame2"> ' . $txt['off'] . '</label>';
                            } elseif ($reclame == 0) {
                                echo '<input type="radio" name="reclame" value="1" id="reclame1" /><label for="reclame1" style="padding-right:8px"> ' . $txt['on'] . '</label>
                                        <input type="radio" name="reclame" value="0" id="reclame2" checked /><label for="reclame2"> ' . $txt['off'] . '</label>';
                            } #Als er nog geen reclame is
                            else {
                                echo '<input type="radio" name="reclame" value="1" id="reclame1" /><label for="reclame1" style="padding-right:8px"> ' . $txt['on'] . '</label>
                                        <input type="radio" name="reclame" value="0" id="reclame2" /><label for="reclame2"> ' . $txt['off'] . '</label>';
                            }
                            ?><? if ($reclame == 1 AND (24 * 3600) + $gebruiker['reclameAanSinds'] >= time()) {
                                echo '<span style="color:orangered;"><b><small>Actief over 24h</small></b></span>';
                            } elseif ($reclame == 1) {
                                echo '<span style="color:green;"><b><small>Actief sinds ' . date("d-m-Y", $gebruiker[reclameAanSinds]) . '</small></b></span>';
                            } ?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><b><?php echo $txt['advertisement_info']; ?></b><br/><br/></td>
                    </tr>
                    <tr>
                        <td height="25"><?php echo $txt['battleScreen']; ?></td>
                        <td><?php

                            if ($battleScreen == 1) {
                                echo '<input type="radio" name="battleScreen" value="1" id="battleScreen1" checked /><label for="battleScreen1" style="padding-right:8px"> ' . $txt['on'] . '</label>
                                    <input type="radio" name="battleScreen" value="0" id="battleScreen2" /><label for="battleScreen2"> ' . $txt['off'] . '</label>';
                            } elseif ($battleScreen == 0) {
                                echo '<input type="radio" name="battleScreen" value="1" id="battleScreen1" /><label for="battleScreen1" style="padding-right:8px"> ' . $txt['on'] . '</label>
                                    <input type="radio" name="battleScreen" value="0" id="battleScreen2" checked /><label for="battleScreen2"> ' . $txt['off'] . '</label>';
                            } #Als er nog geen battleScreen is
                            else {
                                echo '<input type="radio" name="battleScreen" value="1" id="battleScreen1" /><label for="battleScreen1" style="padding-right:8px"> ' . $txt['on'] . '</label>
                                    <input type="radio" name="battleScreen" value="0" id="battleScreen2" /><label for="battleScreen2"> ' . $txt['off'] . '</label>';
                            }
                            ?></td>
                    </tr>
                    <tr>
                        <td height="25">&nbsp;</td>
                        <td>
                            <button type="submit" name="persoonlijk"
                                    class="button"><?php echo $txt['button_personal']; ?></button>
                        </td>
                    </tr>
                </table>
            </center>
        </form>

        <?
        #Persoonlijk sluiten
        break;

    #Wachtwoord openen
    case "password":

        #als er op de verander knop gedrukt word
        if (isset($_POST['veranderww'])) {

            #Controleren als er wel wat ingevuld is
            if (empty($_POST['wachtwoordwachtwoordaanmeld']) && empty($_POST['huidig']) && empty($_POST['wachtwoordcontrole']))
                $wachtwoordtekst = '<div class="red">' . $txt['alert_all_fields_required'] . '</div>';
            #is het huidige wachtwoord niet hetzelfde als het ingevoerde nieuwe wachtwoord
            elseif ($_POST['huidig'] == $_POST['wachtwoordwachtwoordaanmeld'])
                $wachtwoordtekst = '<div class="red">' . $txt['alert_old_new_password_thesame'] . '</div>';
            #Klopt het huidige wachtwoord wel met elkaar
            elseif (MD5($_POST['huidig']) <> $gebruiker['wachtwoord'])
                $wachtwoordtekst = '<div class="red">' . $txt['alert_old_password_wrong'] . '</div>';
            #Is het wachtwoord wel langer dan 5 tekens
            elseif (strlen($_POST['wachtwoordwachtwoordaanmeld']) < 5)
                $wachtwoordtekst = '<div class="red">' . $txt['alert_password_too_short'] . '</div>';
            #Komen beide wachtwoorden wel overeen
            elseif ($_POST['wachtwoordwachtwoordaanmeld'] <> $_POST['wachtwoordcontrole'])
                $wachtwoordtekst = '<div class="red">' . $txt['alert_new_controle_password_wrong'] . '</div>';
            else {

                $wachtwoordmd5 = md5($_POST['wachtwoordcontrole']);
                $query = "UPDATE `gebruikers` SET `wachtwoord`=:wachtwoordmd5 WHERE `user_id`=:user_id";
                $stmt = $db->prepare($query);
                $stmt->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_INT);
                $stmt->bindValue(':wachtwoordmd5', $wachtwoordmd5, PDO::PARAM_STR);
                $stmt->execute();

                $wachtwoordtekst = '<div class="green">' . $txt['success_password'] . '</div>';
            }
        }

        ?>
        <form method="post">
            <?php if (isset($_POST['veranderww'])) echo $wachtwoordtekst; ?>
            <center>
                <table width="350" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="200" height="25"><?php echo $txt['new_password']; ?></td>
                        <td width="150"><input type="password" name="wachtwoordwachtwoordaanmeld" class="text_long"/>
                        </td>
                    </tr>
                    <tr>
                        <td height="25"><?php echo $txt['new_password_again']; ?></td>
                        <td><input type="password" name="wachtwoordcontrole" class="text_long"/></td>
                    </tr>
                    <tr>
                        <td height="25"><?php echo $txt['password_now']; ?></td>
                        <td><input type="password" name="huidig" class="text_long"/></td>
                    </tr>
                    <tr>
                        <td height="25">&nbsp;</td>
                        <td>
                            <button type="submit" name="veranderww"
                                    class="button"><?php echo $txt['button_password']; ?></button>
                        </td>
                    </tr>
                </table>
            </center>
        </form>
        <?

        #wachtwoord sluiten
        break;
    #Profiel openen
    case "profile":
        #Als er op de Knop gedrukt word
        if (isset($_POST['profiel'])) {
            #Tekst formateren

            $dirty_html_tekst = $_POST['tekst'];
            require_once 'includes/htmlpurifier/library/HTMLPurifier.auto.php';

            $config = HTMLPurifier_Config::createDefault();
            $purifier = new HTMLPurifier($config);
            $tekst = $purifier->purify($dirty_html_tekst);

            $query = "UPDATE `gebruikers` SET `profiel`=:tekst WHERE `user_id`=:user_id";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_INT);
            $stmt->bindValue(':tekst', $tekst, PDO::PARAM_STR);
            $stmt->execute();

            $profieltekst = '<div class="green">' . $txt['success_profile'] . '</div>';
        }

        $query = "SELECT `profiel` FROM `gebruikers` WHERE `user_id`=:user_id";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_INT);
        $stmt->execute();
        $tekst = $stmt->fetch(PDO::FETCH_ASSOC);

        ?>

        <link href="includes/summernote/bootstrap.css" rel="stylesheet">
        <link href="includes/summernote/summernote.css" rel="stylesheet">
        <script>
            $(document).ready(function () {
                $('#summernote').summernote({
                    theme: 'yeti',
                    lang: "<?=GLOBALDEF_EDITORLANGUAGE?>",
                    callbacks: {
                        onImageUpload: function (image) {
                            uploadImage(image[0]);
                        }
                    },
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video', 'hr']],
                        ['view', ['fullscreen']]
                    ]
                });

                function uploadImage(image) {
                    var data = new FormData();
                    data.append("image", image);
                    $.ajax({
                        data: data,
                        type: "POST",
                        url: "upload-image.php",
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (url) {
                            /* $('.summernote').summernote('insertImage', url);*/
                            $('#summernote').summernote('insertImage', url, function ($image) {
                                $image.css('width', $image.width() / 3);
                                $image.attr('data-filename', 'retriever');
                            });
                            //console.log(url);
                        },
                        error: function (data) {
                            console.log(data);
                        }
                    });
                }

            });
        </script>

        <form method="post" enctype="multipart/form-data">
            <?php if (isset($_POST['profiel'])) echo $profieltekst; ?>
            <center>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td><textarea id="summernote" rows="12" name="tekst"
                                      style="width: 100%;"><? echo $tekst['profiel']; ?></textarea></td>
                    </tr>
                    <tr>
                        <td height="25"><br/>
                            <button type="submit" name="profiel"
                                    class="button"><?php echo $txt['button_profile']; ?></button>
                        </td>
                    </tr>
                </table>
            </center>
        </form>

        <?
        #wijzig profiel sluiten
        break;

    #Nieuw openen
    case "restart":

        if (isset($_POST['opnieuw'])) {
            if (empty($_POST['wachtwoord']))
                $opnieuwtekst = '<div class="red">' . $txt['alert_no_password'] . '</div>';
            elseif (md5($_POST['wachtwoord']) != $gebruiker['wachtwoord'])
                $opnieuwtekst = '<div class="red">' . $txt['alert_password_wrong'] . '</div>';
            elseif ($_POST['wereld'] == "")
                $opnieuwtekst = '<div class="red">' . $txt['alert_no_beginworld'] . '</div>';

            elseif ($_POST['wereld'] != 'Kanto' && $_POST['wereld'] != 'Johto' && $_POST['wereld'] != 'Hoenn' && $_POST['wereld'] != 'Sinnoh' && $_POST['wereld'] != 'Unova' && $_POST['wereld'] != 'Kalos')
                $opnieuwtekst = '<div class="red">' . $txt['alert_world_invalid'] . '</div>';
            else {
                $datum = date('Y-m-d H:i:s');

                $removeUser = $db->prepare("DELETE FROM `gebruikers_tmhm` WHERE `user_id`=:userId;
                                              DELETE FROM `gebruikers_badges` WHERE `user_id`=:userId;
                                              DELETE FROM `gebruikers_item` WHERE `user_id`=:userId;
                                              DELETE FROM `pokemon_speler` WHERE `user_id`=:userId;
                                              DELETE FROM `transferlijst` WHERE `user_id`=:userId;
                                              DELETE FROM `daycare` WHERE `user_id`=:userId;
                                              DELETE FROM `gebeurtenissen` WHERE `ontvanger_id`=:userId");
                $removeUser->bindParam(':userId', $_SESSION['id'], PDO::PARAM_STR);
                $removeUser->execute();

                unset($removeUser);

                $addUser = $db->prepare("INSERT INTO `gebruikers_tmhm` (`user_id`) VALUES (:userId);
                                              INSERT INTO `gebruikers_badges` (`user_id`) VALUES (:userId);
                                              INSERT INTO `gebruikers_item` (`user_id`) VALUES (:userId)");
                $addUser->bindParam(':userId', $_SESSION['id'], PDO::PARAM_STR);
                $addUser->execute();

                unset($addUser);

                #Veel gebruikers dingen weg!
                $query = "UPDATE `gebruikers` SET `datum`=:datum, `wereld`=:wereld, `silver`=75, `bank`=0, 
                `storten`='3', `huis`='doos', `geluksrad`='1', `rank`='1', `rankexp`='0', `rankexpnodig`='245', 
                `aantalpokemon`='0', `badges`='0', `captcha_tevaak_fout`='0', `werkervaring`='0', `gewonnen`='0', 
                `verloren`='0', `eigekregen`='0', `lvl_choose`='', `wiequiz`='0000-00-00 00:00:00', `werktijd`='0', 
                `pokecentertijd`='0', `gevangenistijd`='0', `geluksrad`='3', `races_winst`='3', `races_verlies`='3', 
                `pok_gezien`='', `pok_bezit`='', `pok_gehad`='' 
                WHERE `user_id`=:user_id";
                $stmt = $db->prepare($query);
                $stmt->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_INT);
                $stmt->bindValue(':wereld', $_POST['wereld'], PDO::PARAM_STR);
                $stmt->bindValue(':datum', $datum, PDO::PARAM_STR);
                $stmt->execute();

                $opnieuwtekst = '<div class="green">' . $txt['success_restart'] . '</div>';
            }
        }

        if (isset($_POST['opnieuw'])) echo $opnieuwtekst; ?>
        <center>
            <table width="660" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td><?php echo $txt['restart_title_text']; ?></td>
                </tr>
            </table>

            <form method="post">
                <table width="350" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="200" height="25"><?php echo $txt['password_security']; ?></td>
                        <td width="150"><input type="password" name="wachtwoord" class="text_long" value=""/></td>
                    </tr>
                    <tr>
                        <td height="25"><?php echo $txt['beginworld']; ?></td>
                        <td><input type="radio" name="wereld" id="kanto"
                                   value="Kanto" <?php if (isset($_POST['wereld']) && $_POST['wereld'] == "Kanto") {
                                echo " checked='checked'";
                            } ?>> <label for="kanto">Kanto</label></td>
                    </tr>
                    <tr>
                        <td rowspan="5"></td>
                        <td height="25"><input type="radio" name="wereld" id="johto"
                                               value="Johto" <?php if (isset($_POST['wereld']) && $_POST['wereld'] == "Johto") {
                                echo " checked='checked'";
                            } ?>> <label for="johto">Johto</label></td>
                    </tr>
                    <tr>
                        <td height="25"><input type="radio" name="wereld" id="hoenn"
                                               value="Hoenn" <?php if (isset($_POST['wereld']) && $_POST['wereld'] == "Hoenn") {
                                echo " checked='checked'";
                            } ?>> <label for="hoenn">Hoenn</label></td>
                    </tr>
                    <tr>
                        <td height="25"><input type="radio" name="wereld" id="sinnoh"
                                               value="Sinnoh" <?php if (isset($_POST['wereld']) && $_POST['wereld'] == "Sinnoh") {
                                echo " checked='checked'";
                            } ?>> <label for="sinnoh">Sinnoh</label></td>
                    </tr>
                    <tr>
                        <td height="25"><input type="radio" name="wereld" id="unova"
                                               value="Unova" <?php if (isset($_POST['wereld']) && $_POST['wereld'] == "Unova") {
                                echo " checked='checked'";
                            } ?>> <label for="unova">Unova</label></td>
                    </tr>
                    <tr>
                        <td height="25"><input type="radio" name="wereld" id="kalos"
                                               value="Kalos" <?php if (isset($_POST['wereld']) && $_POST['wereld'] == "Kalos") {
                                echo " checked='checked'";
                            } ?>> <label for="kalos">Kalos</label></td>
                    </tr>
                    <tr>
                        <td>
                            <button type="submit" name="opnieuw"
                                    class="button"><?php echo $txt['button_restart']; ?></button>
                        </td>
                    </tr>
                </table>
            </form>
        </center>
        <?
        #Profiel sluiten
        break;

    #Nieuw openen
    case "picture":

        ?>

        <h2>Nieuwe profielfoto uploaden</h2>
        <br/>
        <img src="<?= $gebruiker['profielfoto'] ?>" alt="" title="" style="max-width: 130px;"><br/><br/>
        <form action="?page=upload-profile&do=upload" method="post" enctype="multipart/form-data">
            <input name="image_max_width" type="hidden" id="hiddenField" value="400"/>
            Profielfoto:
            <input type="file" name="image" size="20"/><br/>
            Max size: 2 MB.<br/>Extensions that are allowed: jpg, jpeg, gif, png.<br/>
            <button type="submit" name="submit" class="button pull-right">Uploaden</button>
            <br/><br/>
        </form>

        <br><br>

        <h2>Nieuwe cover uploaden</h2>
        <br/>
        <img src="<?= $gebruiker['cover'] ?>" alt="" title="" style="max-width: 330px;"><br/><br/>
        <form action="?page=upload-cover&do=upload" method="post" enctype="multipart/form-data">
            <input name="image_max_width" type="hidden" id="hiddenField" value="400"/>
            Cover:
            <input type="file" name="image" size="20"/><br/>
            Max size: 2 MB.<br/>Extensions that are allowed: jpg, jpeg, gif, png.<br/>
            <button type="submit" name="submit" class="button pull-right">Uploaden</button>
            <br/><br/>
        </form>

        <?
        #Picture sluiten
        break;

    #standaard 
    default:
        #Doorsturen naar persoonlijk
        header("Location: ?page=account-options&category=personal");
        break;
}
?>