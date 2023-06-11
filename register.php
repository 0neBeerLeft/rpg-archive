<?php
$page = 'register';
include_once('language/language-pages.php');

if (isset($_POST['registreer'])) {
    $voornaam = $_POST['voornaam'];
    $achternaam = $_POST['achternaam'];
    $land = $_POST['land'];
    $inlognaam = $_POST['inlognaam'];
    $wachtwoord = $_POST['wachtwoord'];
    $wachtwoord_nogmaals = $_POST['wachtwoord_nogmaals'];
    $wachtwoordmd5 = md5($wachtwoord);
    $email = $_POST['email'];
    $wereld = $_POST['wereld'];
    $gotarefer = $_POST['gotarefer'];
    $captcha = $_POST['captcha'];
    $date = date("Y-m-d H:i:s");
    $character = $_POST['character'];
    $referer = $_POST['referer'];

    $checkQuery = "SELECT `ip_aangemeld`, `aanmeld_datum` FROM `gebruikers` WHERE `ip_aangemeld`=:remoteAddr ORDER BY `user_id` DESC";
    $stmt = $db->prepare($checkQuery);
    $stmt->bindParam(':remoteAddr', $_SERVER['REMOTE_ADDR'], PDO::PARAM_INT);
    $stmt->execute();
    $check = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $registerdate = strtotime($check['aanmeld_datum']);
    $current_time = strtotime(date('Y-m-d H:i:s'));
    $countdown_time = 604800 - ($current_time - $registerdate);

    if (isset($_POST['g-recaptcha-response'])) {
        $captcha = $_POST['g-recaptcha-response'];
    }
    if (!$captcha) {
        $foutje12 = '<span class="error_red">*</span>';
        $alert = '<div class="red">' . $txt['alert_guardcore_invalid'] . '</div>';
    }
    #define your secret key
    $secretKey = GLOBALDEF_GOOGLERECAPTCHASECRETKEY;
    $ip = $_SERVER['REMOTE_ADDR'];
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $captcha . "&remoteip=" . $ip);
    $responseKeys = json_decode($response, true);
    if (intval($responseKeys["success"]) !== 1) {
        $foutje12 = '<span class="error_red">*</span>';
        $alert = '<div class="red">' . $txt['alert_guardcore_invalid'] . '</div>';
    } else {

        $checkUsername = $db->prepare("SELECT `username` FROM `gebruikers` WHERE `username`=:username");
        $checkUsername->bindParam(':username', $inlognaam, PDO::PARAM_STR);
        $checkUsername->execute();
        $checkUsername = $checkUsername->rowCount();

        $checkEmail = $db->prepare("SELECT `username` FROM `gebruikers` WHERE `email`=:email");
        $checkEmail->bindParam(':email', $email, PDO::PARAM_STR);
        $checkEmail->execute();
        $checkEmail = $checkEmail->rowCount();

        if (empty($inlognaam)) {

            #$foutje6 a username
            $foutje5 = '<span class="error_red">*</span>';
            $alert = '<div class="red">' . $txt['alert_no_username'] . '</div>';

        } elseif (strlen($inlognaam) < 3) {

            #$foutje6 a username longer than three characters
            $foutje5 = '<span class="error_red">*</span>';
            $alert = '<div class="red">' . $txt['alert_username_too_short'] . '</div>';

        } elseif (strlen($inlognaam) > 10) {

            #is the username longer than 10 characters
            $foutje5 = '<span class="error_red">*</span>';
            $alert = '<div class="red">' . $txt['alert_username_too_long'] . '</div>';

        } elseif ($checkUsername) {

            #does the user exist?
            $foutje5 = '<span class="error_red">*</span>';
            $alert = '<div class="red">' . $txt['alert_username_exists'] . '</div>';

        } elseif (!preg_match('/^([a-zA-Z0-9]+)$/is', $inlognaam)) {

            #no special characters are allowed.
            $foutje5 = '<span class="error_red">*</span>';
            $alert = '<div class="red">' . $txt['alert_username_incorrect_signs'] . '</div>';

        } elseif (empty($wachtwoord)) {

            #provide a password
            $foutje6 = '<span class="error_red">*</span>';
            $alert = '<div class="red">' . $txt['alert_no_password'] . '</div>';

        } elseif ($wachtwoord <> $wachtwoord_nogmaals) {

            #do the passwords match?
            $foutje6 = '<span class="error_red">*</span>';
            $foutje7 = '<span class="error_red">*</span>';
            $alert = '<div class="red">' . $txt['alert_passwords_dont_match'] . '</div>';

        }  elseif (empty($email)) {

            #provide an email
            $foutje8 = '<span class="error_red">*</span>';
            $alert = '<div class="red">' . $txt['alert_no_email'] . '</div>';

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            #is the email valid?
            $foutje8 = '<span class="error_red">*</span>';
            $alert = '<div class="red">' . $txt['alert_email_incorrect_signs'] . '</div>';

        } elseif ($checkEmail) {

            #does the email already exist?
            $foutje8 = '<span class="error_red">*</span>';
            $alert = '<div class="red">' . $txt['alert_email_exists'] . '</div>';
        } elseif (empty($wereld)) {

            $foutje10 = '<span class="error_red">*</span>';
            $alert = '<div class="red">' . $txt['alert_no_beginworld'] . '</div>';

        } elseif ($wereld != 'Kanto' && $wereld != 'Johto' && $wereld != 'Hoenn' && $wereld != 'Sinnoh' && $wereld != 'Unova' && $wereld != 'Kalos') {

            #The user needs to select a world
            $foutje10 = '<span class="error_red">*</span>';
            $alert = '<div class="red">' . $txt['alert_world_invalid'] . '</div>';

        } else {
            #generate activationcode
            $desired_length = 10; //or whatever length you want
            $unique = uniqid();
            $activatiecode = substr($unique, 0, $desired_length);
            #$activatiecode = 1;

            $referaldata = 'Activeer je account met deze link of klik <a href="' . GLOBALDEF_SITEPROTOCOL . '://' . GLOBALDEF_SITEDOMAIN . 'index.php?page=activate&player=' . $inlognaam . '&code=' . $activatiecode . '">hier</a>.<br>
                                <small>' . GLOBALDEF_SITEPROTOCOL . '://' . GLOBALDEF_SITEDOMAIN . '/index.php?page=activate&player=' . $inlognaam . '&code=' . $activatiecode . '</small><br/><br/>';
            if (!isset($gotarefer)) {
                $referer = "";
                $activatiecode = 1;
                $referaldata = "";
            }

            $character = 'images/you/' . $character . '.png';

            #add the user to the database

            $addUser = $db->prepare("INSERT INTO `gebruikers` (`account_code`, `land`, `profielfoto`, `username`, `datum`, `aanmeld_datum`, `wachtwoord`, `email`, `ip_aangemeld`, `wereld`, `referer`)
          VALUES (:activatiecode, :land, :userCharacter, :inlognaam, :Udate, :Udate, :wachtwoordmd5, :email, :remoteAddr , :wereld, :referer)");
            $addUser->bindParam(':activatiecode', $activatiecode, PDO::PARAM_STR);
            $addUser->bindParam(':land', $land, PDO::PARAM_STR);
            $addUser->bindParam(':userCharacter', $character, PDO::PARAM_STR);
            $addUser->bindParam(':inlognaam', $inlognaam, PDO::PARAM_STR);
            $addUser->bindParam(':Udate', $date, PDO::PARAM_STR);
            $addUser->bindParam(':wachtwoordmd5', $wachtwoordmd5, PDO::PARAM_STR);
            $addUser->bindParam(':email', $email, PDO::PARAM_STR);
            $addUser->bindParam(':remoteAddr', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
            $addUser->bindParam(':wereld', $wereld, PDO::PARAM_STR);
            $addUser->bindParam(':referer', $referer, PDO::PARAM_STR);
            $addUser = $addUser->execute();

            $userId = $db->lastInsertId();

            #save the new user in all the other tables
            $addUser = $db->prepare("INSERT INTO `gebruikers_tmhm` (`user_id`) VALUES (:userId);
                                              INSERT INTO `gebruikers_badges` (`user_id`) VALUES (:userId);
                                              INSERT INTO `gebruikers_item` (`user_id`) VALUES (:userId)");
            $addUser->bindParam(':userId', $userId, PDO::PARAM_STR);
            $addUser->execute();

            unset($addUser);

            #check if the referer exists
            $checkRefer = $db->prepare("SELECT `username` FROM `gebruikers` WHERE `username`=:username");
            $checkRefer->bindParam(':username', $referer, PDO::PARAM_STR);
            $checkRefer->execute();
            $checkRefer = $checkRefer->rowCount();

            if ($checkRefer) {
                $addRefer = $db->prepare("INSERT INTO `referer_logs` (`gebruiker`,`nieuwe_gebruiker`)VALUES (:referer,:inlognaam)");
                $addRefer->bindParam(':referer', $referer, PDO::PARAM_STR);
                $addRefer->bindParam(':inlognaam', $inlognaam, PDO::PARAM_STR);
                $addRefer->execute();
            }

            ### Headers
            $headers = "From: " . GLOBALDEF_ADMINEMAIL . "\r\n";
            $headers .= "Return-pathSender: " . GLOBALDEF_ADMINEMAIL . "\r\n";
            $headers .= "X-Sender: \"" . GLOBALDEF_ADMINEMAIL . "\" \n";
            $headers .= "X-Mailer: PHP\n";
            $headers .= "Bcc: " . GLOBALDEF_ADMINEMAIL . "\r\n";
            $headers .= "Content-Type: text/html; charset=iso-8859-1\n";

            $page = 'register';
            #Goeie taal erbij laden voor de mail
            include_once('language/language-mail.php');

            mail($email, $txt['mail_register_title'],
                '<html dir="rtl">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <style>
    .flip { -moz-transform: scaleX(-1); -o-transform: scaleX(-1); -webkit-transform: scaleX(-1); transform: scaleX(-1); filter: FlipH; -ms-filter: "FlipH"; }
    </style>
    <body>
    <center>
      <table width="80%" border="0" cellspacing="0" cellpadding="0">
        <tr>
      <td background="'.GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/images/mail/linksboven.gif" width="11" height="11"></td>
      <td height="11" background="'.GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/images/mail/bovenbalk.gif" class="flip"></td>
      <td background="'.GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/images/mail/rechtsboven.gif" width="11" height="11"></td>
        </tr>

        <tr>
      <td width="11" rowspan="2" background="'.GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/images/mail/linksbalk.gif"></td>
      <td align="center" bgcolor="#D3E9F5"><img src="'.GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/images/mail/headermail.png" width="350" ></td>
      <td width="11" rowspan="2" background="'.GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/images/mail/rechtsbalk.gif"></td>
        </tr>
        <tr>
          <td align="center" valign="top" bgcolor="#D3E9F5">Welkom, ' . $inlognaam . ',<br/><br/>
                                Welkom bij het leukste online pokemon spel.<br/><br/>
                                Uw gebruikersnaam: <b>' . $inlognaam . '</b><br/>
                                Uw wachtwoord: <b>' . $wachtwoord . '</b><br/>
                                <small>*Let op: Hou je wachtwoord prive.</small><br/><br/>

                                ' . $referaldata . '
                                Veel plezier op '.GLOBALDEF_SITENAME.'!<br/><br/>
                                Met vriendelijke groet,<br/>
                                <b>'.GLOBALDEF_SITENAME.'</b>.</td>
        </tr>
        <tr>
      <td background="'.GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/images/mail/linksonder.gif" width="11" height="11"></td>
      <td background="'.GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/images/mail/onderbalk.gif" height="11" class="flip"></td>
      <td background="'.GLOBALDEF_SITEPROTOCOL.'://'.GLOBALDEF_SITEDOMAIN.'/images/mail/rechtsonder.gif" width="11" height="11"></td>

        </tr>
      </table>
      &copy; '.GLOBALDEF_SITENAME.' - '.date('Y').'
    </center>
    </body>
      </html>',
                $headers
            );

            #return success message
            if ($gotarefer) {
                $alert = '<div class="green">' . $txt['success_register'] . '</div>';
            } else {
                $alert = '<div class="green">' . $txt['success_register2'] . '</div>';

                $query = "SELECT `user_id`, `username`, `wachtwoord`, `account_code` FROM `gebruikers` WHERE `username`=:inlognaam";
                $stmt = $db->prepare($query);
                $stmt->bindValue(':inlognaam', $inlognaam, PDO::PARAM_STR);
                $stmt->execute();
                $userData = $stmt->fetch(PDO::FETCH_ASSOC);

                #set login variables
                $_SESSION['id'] = $userData['user_id'];
                $_SESSION['naam'] = $userData['username'];
                $_SESSION['userid'] = "";

                #set login check
                $_SESSION['hash'] = md5($_SERVER['REMOTE_ADDR'] . "," . $userData['username']);

                #redirect the user after a successfull signup
                header('location: ?page=home');
                exit;
            }

        }
    }
}
?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script>
    $(document).ready(function () {
        <?
        if(empty($_GET['referer'])){
        ?>
        $("#referer").toggle();
        $("#referer1").toggle();
        $("#referer2").toggle();
        <?
        }
        ?>
        $("#gotarefer").change(function () {
            $("#referer").toggle();
            $("#referer1").toggle();
            $("#referer2").toggle();
        });
    });
</script>

<form method="post" action="?page=register" name="register">
    <center><p> <?php echo $txt['title_text']; ?> </p></center>
    <?php if (isset($alert)) {
        echo $alert;
    }?>
    <table width="660" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="2" class="top_first_td"><? echo $txt['register_game_data']; ?></td>
        </tr>
        <tr>
            <td colspan="2" style="padding-bottom:10px;"></td>
        </tr>
        <tr>
            <td class="normal_first_td" width="150px"><? echo $txt['username'] . ' '; echo (isset($foutje5) ? $foutje5: ''); ?></td>
            <td class="normal_td"><input name="inlognaam" type="text" class="text_long"
                                         value="<?php if (isset($_POST ['inlognaam']) && !empty($_POST ['inlognaam'])) {
                                             echo $_POST ['inlognaam'];
                                         } ?>" maxlength="10"/></td>
        </tr>
        <tr>
            <td class="normal_first_td"><? echo $txt['password'] . ' '; echo (isset($foutje6) ? $foutje6: ''); ?></td>
            <td class="normal_td"><input type="password" name="wachtwoord"
                                         value="<?php if (isset($_POST ['wachtwoord']) && !empty($_POST ['wachtwoord'])) {
                                             echo $_POST ['wachtwoord'];
                                         } ?>" class="text_long"/></td>
        </tr>
        <tr>
            <td class="normal_first_td"><? echo $txt['password_again'] . ' '; echo (isset($foutje7) ? $foutje7: ''); ?></td>
            <td class="normal_td"><input type="password" name="wachtwoord_nogmaals"
                                         value="<?php if (isset($_POST ['wachtwoord_nogmaals']) && !empty($_POST ['wachtwoord_nogmaals'])) {
                                             echo $_POST ['wachtwoord_nogmaals'];
                                         } ?>" class="text_long"/></td>
        </tr>
        <tr>
            <td class="normal_first_td"><?php echo $txt['email'] . ' '; echo (isset($foutje8) ? $foutje8: ''); ?></td>
            <td class="normal_td"><input type="text" name="email"
                                         value="<?php if (isset($_POST ['email']) && !empty($_POST ['email'])) {
                                             echo $_POST ['email'];
                                         } ?>" class="text_long"/> <span id="referer2">*Activatie is vereist, voer een geldig email adres in.</span>
            </td>
        </tr>
        <tr>
            <td class="normal_first_td"><?php echo $txt['country']; ?></td>
            <td class="normal_td">


                <select name="land" class="text_select">
                    <?
                    $query = "SELECT `en`, `nl` FROM `landen`";
                    $stmt = $db->prepare($query);
                    $stmt->execute();
                    $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach($countries as $country){
                        echo '<option value="'.$country[GLOBALDEF_LANGUAGE].'">'.$country[GLOBALDEF_LANGUAGE].'</option>';
                    }
                    ?>
                </select>

            </td>
        </tr>
        <tr>
            <td class="normal_first_td"><?php echo $txt['character'] . ' '; echo (isset($foutje9) ? $foutje9: ''); ?></td>
            <td class="normal_td"><select name="character"
                                          value="<?php if (isset($_POST ['character']) && !empty($_POST ['character'])) {
                                              echo $_POST ['character'];
                                          } ?>" class="text_select">
                    <?
                    $query = "SELECT naam FROM characters ORDER BY id ASC";
                    $stmt = $db->prepare($query);
                    $stmt->execute();
                    $characters = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (isset($_POST['character'])) {
                        $characterr = $_POST['character'];
                    } else {
                        $characterr = 'Red';
                    }

                    foreach ($characters as $character) {
                        if ($character['naam'] == $characterr) {
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }
                        echo '<option value="' . $character['naam'] . '" ' . $selected . '>' . $character['naam'] . '</option>';
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="normal_first_td"><? echo $txt['beginworld'] . ' '; echo (isset($foutje10) ? $foutje10: ''); ?></td>
            <td class="normal_td"><select name="wereld" class="text_select">
                    <option <?php if (isset($_POST['wereld']) && $_POST['wereld'] == "Kanto") {
                        echo 'checked';
                    } ?> value="Kanto">Kanto
                    </option>
                    <option value="Johto"> <?php if (isset($_POST['wereld']) && $_POST['wereld'] == "Kanto") {
                            echo 'checked';
                        } ?>Johto
                    </option>
                    <option value="Hoenn" <?php if (isset($_POST['wereld']) && $_POST['wereld'] == "Kanto") {
                        echo 'checked';
                    } ?>>Hoenn
                    </option>
                    <option value="Sinnoh" <?php if (isset($_POST['wereld']) && $_POST['wereld'] == "Kanto") {
                        echo 'checked';
                    } ?>>Sinnoh
                    </option>
                    <option value="Unova" <?php if (isset($_POST['wereld']) && $_POST['wereld'] == "Unova") {
                        echo 'checked';
                    } ?>>Unova
                    </option>
                    <option value="Kalos" <?php if (isset($_POST['wereld']) && $_POST['wereld'] == "Kalos") {
                        echo 'checked';
                    } ?>>Kalos
                    </option>

                </select></td>
        </tr>
        <tr>
            <td class="normal_first_td"><label
                        for="gotarefer"><? echo $txt['1account_rule']; ?></label><?php echo (isset($gotarefer) ? $gotarefer: '');?></td>
            <td class="normal_td"><input name="gotarefer" id="gotarefer" class="gotarefer" value="yes"
                                         type="checkbox" <? if (!empty($_GET['referer'])) {
                    echo "checked";
                } ?>></td>
        </tr>
        <tr>
            <td class="normal_first_td">
                <div id="referer"><?php echo $txt['referer']; ?></div>
            </td>
            <td class="normal_td">
                <div id="referer1"><input type="text" name="referer" value="<?php echo (isset($_GET['referer']) ? $_GET['referer']: '');?>"
                                          class="text_long"/> <span
                            style="padding-left:5px;"><?php echo $txt['not_oblige']; ?></span></div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-bottom:10px;"></td>
        </tr>
        <tr>
            <td colspan="2" class="top_first_td"><? echo $txt['register_security']; ?></td>
        </tr>
        <tr>
            <td colspan="2" style="padding-bottom:10px;"></td>
        </tr>
        <tr>
            <td class="normal_first_td">&nbsp;</td>
            <td class="normal_td">
                <div class="g-recaptcha" data-sitekey="<?= GLOBALDEF_GOOGLERECAPTCHASITEKEY; ?>"></div>
            </td>
        </tr>
        <tr>
            <td class="normal_first_td">&nbsp;</td>
            <td class="normal_td">
                <button type="submit" name="registreer" class="button"><? echo $txt['button']; ?></button>
            </td>
        </tr>
    </table>
</form>