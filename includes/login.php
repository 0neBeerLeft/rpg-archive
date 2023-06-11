<?php
if (isset($_POST['login'])) {
    $passwordMd5 = md5($_POST['password']);
    $passwordSubstr = substr($_POST['password'], -4);

    //Geen inlognaam ingevuld
    if ($_POST['username'] == '') {
        $inlog_error = $txt['alert_no_username'];

    } elseif ($_POST['password'] == '') {
        $inlog_error = $txt['alert_no_password'];

    } else {

        $query = "SELECT `datum`, `ip`, `spelernaam` FROM `inlog_fout` WHERE `ip`=:remoteAddr ORDER BY `id` DESC";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':remoteAddr', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $stmt->execute();
        $loginErrors = $stmt->rowCount();

        $query = "SELECT `username` FROM `gebruikers` WHERE `username`=:naam";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':naam', $_POST['username'], PDO::PARAM_STR);
        $stmt->execute();
        $checkUser = $stmt->fetch(PDO::FETCH_ASSOC);

        $query = "SELECT gebruiker FROM ban WHERE gebruiker = :username";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
        $stmt->execute();
        $isUserBanned = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($checkUser)) {
            $inlog_error = $txt['alert_unknown_username'];

        } elseif ($checkUser['username'] != $_POST['username']) {
            $inlog_error = $txt['alert_unknown_username'];

        } elseif (!empty($isUserBanned)) {
            $inlog_error = $txt['alert_account_banned'];

        } elseif (!empty($checkUser)) {

            $query = "SELECT `user_id`, `username`, `wachtwoord`, `account_code` FROM `gebruikers` WHERE `wachtwoord`=:wwmd5 AND `username`=:naam";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':wwmd5', $passwordMd5, PDO::PARAM_STR);
            $stmt->bindValue(':naam', $_POST['username'], PDO::PARAM_STR);
            $stmt->execute();
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            if($loginErrors > 2) {
                $query = "SELECT `datum`,`ip`
                FROM `inlog_fout` WHERE `datum` IN (
                SELECT MAX( `datum` )
                  FROM `inlog_fout` WHERE `ip` =:remoteAddr
                )
                ORDER BY `datum` ASC;";
                $stmt = $db->prepare($query);
                $stmt->bindValue(':remoteAddr', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
                $stmt->execute();
                $loginErrorCheck = $stmt->fetch(PDO::FETCH_ASSOC);

                $countDown = 1200 - (time() - strtotime($loginErrorCheck['datum']));
            }

            if ($userData['wachtwoord'] != $passwordMd5) {

                $query = "INSERT INTO `inlog_fout` (`datum`, `ip`, `spelernaam`, `wachtwoord`) VALUES (:datum, :remoteAddr, :naam, :passwordSubstr)";
                $stmt = $db->prepare($query);
                $stmt->bindValue(':naam', $_POST['username'], PDO::PARAM_STR);
                $stmt->bindValue(':datum', date("Y-m-d H:i:s"), PDO::PARAM_STR);
                $stmt->bindValue(':remoteAddr', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
                $stmt->bindValue(':passwordSubstr', $passwordSubstr, PDO::PARAM_STR);
                $stmt->execute();
            }

            if (isset($loginErrorCheck) && ($loginErrorCheck['ip'] === $_SERVER['REMOTE_ADDR']) && ($countDown > 0)) {

                $inlog_error = $txt['alert_time_sentence'] . ' <span><script type="text/javascript">writetimer("' . $countDown . '")</script></span>';

            } elseif (($loginErrors == 2) && ($userData['wachtwoord'] != $passwordMd5)) {
                $inlog_error = $txt['alert_timepenalty'];

            } elseif (($loginErrors == 1) && ($userData['wachtwoord'] != $passwordMd5)) {
                $inlog_error = $txt['alert_trys_left_1'];

            } elseif (($loginErrors == 0) && ($userData['wachtwoord'] != $passwordMd5)) {
                $inlog_error = $txt['alert_trys_left_2'];

            } elseif ($userData['account_code'] != 1) {
                $inlog_error = $txt['alert_account_not_activated'];

            } else {

                #set the current login time of the user and restore login errors to zero
                $query = "DELETE FROM `inlog_fout` WHERE `ip`=:remoteAddr;
                    UPDATE `gebruikers` SET `ip_ingelogd`=:remoteAddr, `online`=:currTime WHERE `username`=:username;
                    INSERT INTO `inlog_logs` (`ip`, `datum`, `speler`)  VALUES (:remoteAddr, :dateNow, :username) 
                    ON DUPLICATE KEY UPDATE datum=:dateNow;";
                $stmt = $db->prepare($query);
                $stmt->bindValue(':remoteAddr', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
                $stmt->bindValue(':currTime', time(), PDO::PARAM_STR);
                $stmt->bindValue(':dateNow', date("Y-m-d H:i:s"), PDO::PARAM_STR);
                $stmt->bindValue(':username', $userData['username'], PDO::PARAM_STR);
                $result = $stmt->execute();

                if ($result) {
                    #set session variables
                    $_SESSION['id'] = $userData['user_id'];
                    $_SESSION['naam'] = $userData['username'];
                    #location based hash for a login check
                    $_SESSION['hash'] = md5($_SERVER['REMOTE_ADDR'] . "," . $userData['username']);
                    #send to homepage

                    header('location: /index.php?page=home');
                    exit;

                }
            }
        }
    }
}