<?php
$page = 'activate';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

$playerinsert = $_GET['player'];
$codeinsert = $_GET['code'];

if (isset($_POST['submit'])) {
    $inlognaam = $_POST['inlognaam'];
    $activatie = $_POST['activatie'];
    $captcha = $_POST['captcha'];

    $playerinsert = $_POST['inlognaam'];
    $codeinsert = $_POST['activatie'];
    #Gegevens laden van uit de database

    $activatiegegevensQuery = "SELECT `user_id`, `username`, `account_code` FROM `gebruikers` WHERE `username`=:inlognaam AND `account_code`=:activatie";
    $activatiegegevens = $db->prepare($activatiegegevensQuery);
    $activatiegegevens->bindParam(':inlognaam', $inlognaam, PDO::PARAM_STR);
    $activatiegegevens->bindParam(':activatie', $activatie, PDO::PARAM_STR);
    $activatiegegevens->execute();
    $activatiegegevens = $activatiegegevens->fetch(PDO::FETCH_ASSOC);

    $spelerid = $activatiegegevens['user_id'];

    #inlognaam
    if (empty($inlognaam)) {
        echo '<div class="red">' . $txt['alert_no_username'] . '</div>';
    } #Is de inlognaam korter dan drie karakters
    elseif (strlen($inlognaam) < 3) {
        echo '<div class="red">' . $txt['alert_username_too_short'] . '</div>';
    } #Is er een activatie code?
    elseif (empty($activatie)) {
        echo '<div class="red">' . $txt['alert_no_activatecode'] . '</div>';
    } #Is de activatie code te kort?
    elseif (strlen($activatie) < 1) {
        echo '<div class="red">' . $txt['alert_activatecode_too_short'] . '</div>';
    } #Bestaat de gebruiker niet
    elseif ($activatiegegevens['username'] != $inlognaam) {
        echo '<div class="red">' . $txt['alert_username_dont_exist'] . '</div>';
    } #Klopt de beveilings code wel?
    elseif (($captcha) != $_SESSION['captcha_code']) {
        echo '<div class="red">' . $txt['alert_guardcore_invalid'] . '</div>';
    } #Check als dit account al geactiveerd is
    elseif ($activatiegegevens['account_code'] == 1) {
        echo '<div class="blue">' . $txt['alert_already_activated'] . '</div>';
    } #Als alles goed is ingevoerd
    else {
        #Activate player
        $activatieQuery = "UPDATE `gebruikers` SET `account_code`='1' WHERE `user_id`=:spelerid";
        $activatie = $db->prepare($activatieQuery);
        $activatie->bindParam(':spelerid', $spelerid, PDO::PARAM_STR);
        $activatie->execute();

        #Check if the player is a refer
        $referQuery = "SELECT * FROM `referer_logs` WHERE `nieuwe_gebruiker`=:playerinsert AND awarded = 'no'";
        $refer = $db->prepare($referQuery);
        $refer->bindParam(':playerinsert', $playerinsert, PDO::PARAM_STR);
        $refer->execute();
        $refer = $refer->fetch(PDO::FETCH_ASSOC);

        #als er een refer is een beloning toekenen
        if ($refer) {
            $activatieQuery = "UPDATE gebruikers SET gold = gold +150 WHERE username =:gebruiker;
                                UPDATE `referer_logs` SET `awarded`= 'yes', datum = NOW() WHERE `id`=:uid";
            $activatie = $db->prepare($activatieQuery);
            $activatie->bindParam(':gebruiker', $refer['gebruiker'], PDO::PARAM_STR);
            $activatie->bindParam(':uid', $refer['id'], PDO::PARAM_STR);
            $activatie->execute();
        }

        echo '<div class="green">' . $txt['success_activate'] . '</div>';
    }
}
?>
<form method="post">
    <center><p><?php echo $txt['title_text']; ?></p></center>
    <center>
        <table width="600" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="280" style="background: url('images/toxicroak.png') no-repeat left top;"></td>
                <td width="320" align="left" valign="top"><br/><br/>
                    <table width="300" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td height="37" valign="middle"><? echo $txt['username']; ?></td>
                            <td>
                                <input name="inlognaam" type="text" class="text_long" value="<?php echo $playerinsert; ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td width="150" height="37" valign="middle"><?php echo $txt['activatecode']; ?></td>
                            <td width="150">
                                <input name="activatie" type="text" class="text_long" id="activatie" value="<?php echo $codeinsert; ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td height="37">&nbsp;</td>
                            <td>
                                <img src="includes/captcha.php" alt="<?php echo $txt['captcha']; ?>" title="<?php echo $txt['captcha']; ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td height="37"><?php echo $txt['guardcode']; ?></td>
                            <td><input type="text" name="captcha" class="text_long"/></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><input type="submit" name="submit" value="<?php echo $txt['button']; ?>" class="button">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</form>
