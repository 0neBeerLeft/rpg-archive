<?
include('includes/security.php');

$page = 'bank';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');
?>

<table width="100%">
    <tr>
        <td align="left">
            <center><a href="index.php?page=bank&x=pinstort"><img src="images/pin.png"></center>
            <br>
            <center><?=$txt['pagetitle']?></a><center>
        </td>
        <?php
        if ($gebruiker['clan'] != "") {

            $clanQuery = "SELECT * FROM clans WHERE clan_naam=:clan";
            $clanQuery = $db->prepare($clanQuery);
            $clanQuery->bindParam(':clan', $gebruiker['clan'], PDO::PARAM_STR);
            $clanQuery->execute();
            $clanQuery = $clanQuery->fetch(PDO::FETCH_ASSOC);

            ?>
            <td align="center">
                <center>
                    <a href="index.php?page=bank&x=stortennaarclan"><img src="images/type/<? echo $clanQuery['clan_type']; ?>.png" width="72px">
                </center>
                <br>
                <center><?=$txt['deposit_to_clan']?></a><center>
            </td>
            <?php
        }
        ?>
        <td align="right">
            <center><a href="index.php?page=bank&x=overschrijven"><img src="images/overschrijven.png"></center>
            <br>
            <center><?=$txt['bank_transfer']?></a></center>
        </td>
    </tr>
</table>
<br/><br/>
<?php 
$bankmax = "9999999999";
$silver = $gebruiker['silver'];
$gold = $gebruiker['gold'];
$bank = $gebruiker['bank'];
$data['bankleft'] = $gebruiker['storten'];

if (isset($_POST['out']) && preg_match('/^[0-9]+$/', $_POST['amount'])) {
    if ($_POST['amount'] <= $bank) {

        $retrieveQuery = "UPDATE `gebruikers` SET `bank`=`bank`-:amount,`silver`=`silver`+:amount WHERE `user_id`=:user_id;
                            INSERT INTO bank_logs (date, sender, receiver, amount, what,type)
                            VALUES (NOW(),:username,:username,:amount,'withdraw','silver')";
        $retrieve = $db->prepare($retrieveQuery);
        $retrieve->bindParam(':amount', $_POST['amount'], PDO::PARAM_STR);
        $retrieve->bindParam(':username', $gebruiker['username'], PDO::PARAM_STR);
        $retrieve->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_INT);
        $retrieve->execute();

        print "<div class=\"green\">".$txt["alert_success_silver_withdraw"]."</div>";
    } else {
        print "<div class=\"red\">".$txt["alert_failed_silver_withdraw"]."</div>";
    }
} else if (isset($_POST['in']) && preg_match('/^[0-9]+$/', $_POST['amount'])) {
    if ($_POST['amount'] <= $silver) {
        if ($_POST['amount'] <= $bankmax) {
            if ($data['bankleft'] > 0) {

                $depositQuery = "UPDATE `gebruikers` SET `bank`=`bank`+:amount,`silver`=`silver`-:amount,`storten`=`storten`-1 WHERE `user_id`=:user_id;
                            INSERT INTO bank_logs (date, sender, receiver, amount, what,type)
                            VALUES (NOW(),:username,:username,:amount,'deposit','silver')";
                $deposit = $db->prepare($depositQuery);
                $deposit->bindParam(':amount', $_POST['amount'], PDO::PARAM_STR);
                $deposit->bindParam(':username', $gebruiker['username'], PDO::PARAM_STR);
                $deposit->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_INT);
                $deposit->execute();

                print "<div class=\"green\">".$txt['alert_success_silver_deposit']."</div>";
            } else
                print "<div class=\"blue\">".$txt['alert_failed_silver_deposit']."</div>";
        } else
            print "<div class=\"red\">".$txt['alert_failed_max_silver_deposit']."</div>";
    } else
        print "<div class=\"red\">".$txt['alert_failed_funds_silver_deposit']."</div>";
}
if ($_GET['x'] == "pinstort") {
?>
    <br/><?=$txt['bank_deposit_amount_max']?><br/><br/>
    <table width="60%">
        <tr>
            <td width=100><?=$txt['bank_deposit_cash']?></td>
            <td align="right"><img src="images/icons/silver.png"/> <?= highamount($gebruiker['silver']) ?></td>
        </tr>
        <tr>
            <td width=100><?=$txt['bank_deposit_bank']?></td>
            <td align="right"><img src="images/icons/silver.png"/> <?= highamount($gebruiker['bank']) ?></td>
        </tr>
    </table>
    <form method="post">
        <table width="60%" align="center">
            <tr>
                <td align="left">
                    &euro; <input type="text" class="bar curved5" name="amount" maxlength="10"> ,- <br/><br/>
                    <button type="submit" name="out" class="button"><?=$txt['bank_deposit_pin']?></button>
                    <button type="submit" name="in" class="button"><?=$txt['bank_deposit_deposit']?></button>
                </td>
            </tr>
        </table>
    </form>
<?
  
} else if($_GET['x'] == "overschrijven") {

    if (isset($_POST['to'])) {
        if ($_POST['silver']) {

            $ontvangerQuery = "SELECT * FROM `gebruikers` WHERE `username`=:toUser";
            $ontvanger = $db->prepare($ontvangerQuery);
            $ontvanger->bindParam(':toUser', $_POST['to'], PDO::PARAM_STR);
            $ontvanger->execute();
            $ontvanger = $ontvanger->fetch(PDO::FETCH_ASSOC);

            if ($ontvanger < 1) {
                print "<div class=\"red\">".$txt['alert_bank_transfer_name_incorrect']."</div>";
            } else if ($_POST['silver'] <= $silver) {

                $transferQuery = "UPDATE `gebruikers` SET `silver`=`silver`-:silver WHERE `user_id`=:user_id;
                                UPDATE `gebruikers` SET `silver`=`silver`+:silver WHERE `user_id`=:toUser_id;
                                INSERT INTO bank_logs (date, sender, receiver, amount, what,type)
                                    VALUES (NOW(),:username,:toUsername,:silver,'transfer','silver')";
                $transfer = $db->prepare($transferQuery);
                $transfer->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_INT);
                $transfer->bindParam(':toUser_id', $ontvanger['user_id'], PDO::PARAM_INT);
                $transfer->bindParam(':silver', $_POST['silver'], PDO::PARAM_INT);
                $transfer->bindParam(':toUsername', $ontvanger['username'], PDO::PARAM_STR);
                $transfer->bindParam(':username', $gebruiker['username'], PDO::PARAM_STR);
                $transfer->execute();

                print "<div class=\"green\">".$txt['alert_bank_transfer_success']."</div>";

            } else {
                print "<div class=\"red\">".$txt['alert_bank_transfer_funds']."</div>";
            }
        } else {
            print "<div class=\"red\">".$txt['alert_bank_transfer_no_amount']."</div>";
        }
    }
    ?>
    <br/><br/>
    <form method="post">
        <table width="60%">
            <tr>
                <td><img src="images/icons/user.png"/> <?=$txt['bank_transfer_to']?></td>
                <td><input type="text" class="bar curved5" name="to" value="<?=$_REQUEST['to']?>"></td>
            </tr>
            <tr>
                <td><img src="images/icons/silver.png"/> <?=$txt['bank_transfer_silver']?></td>
                <td><input type="text" class="bar curved5" name="silver" maxlength="7" value="<?=$_REQUEST['silver']?>">
                </td>
            </tr>
            <tr>
                <td>
                    <button type="submit" class="button"><?=$txt['bank_transfer_button']?></button>
                </td>
            </tr>
        </table>
    </form>
<?
} else if($_GET['x'] == "stortennaarclan") {

    if (isset($_POST['to'])) {
        if ($_POST['silver']) {
            $ontvangerQuery = "SELECT * FROM `clans` WHERE `clan_naam`=:toClan";
            $ontvanger = $db->prepare($ontvangerQuery);
            $ontvanger->bindParam(':toClan', $_POST['to'], PDO::PARAM_STR);
            $ontvanger->execute();
            $ontvanger = $ontvanger->fetch(PDO::FETCH_ASSOC);

            if ($ontvanger < 1) {
                print "<div class=\"red\">" . $txt['alert_bank_transfer_clan_incorrect'] . "</div>";
            } else if ($_POST['silver'] <= $silver) {

                $depositClanQuery = "UPDATE `gebruikers` SET `silver`=`silver`-:silver WHERE `user_id`=:user_id;
                                UPDATE `clans` SET `clan_silver`=`clan_silver`+:silver WHERE `clan_naam`=:clan_naam;
                                INSERT INTO bank_logs (date, sender, receiver, amount, what,type)
                                    VALUES (NOW(),:fromUsername,:clan_naam,:silver,'transfer to clan','silver')";
                $depositClan = $db->prepare($depositClanQuery);
                $depositClan->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_STR);
                $depositClan->bindParam(':silver', $_POST['silver'], PDO::PARAM_STR);
                $depositClan->bindParam(':fromUsername', $ontvanger['username'], PDO::PARAM_STR);
                $depositClan->bindParam(':clan_naam', $ontvanger['clan_naam'], PDO::PARAM_STR);
                $depositClan->execute();

                print "<div class=\"green\">" . $txt['alert_bank_transfer_clan_success'] . "</div>";

            } else {
                print "<div class=\"red\">" . $txt['alert_bank_transfer_clan_funds'] . "</div>";
            }
        } elseif ($_POST['gold']) {

            $ontvangerQuery = "SELECT * FROM `clans` WHERE `clan_naam`=:toClan";
            $ontvanger = $db->prepare($ontvangerQuery);
            $ontvanger->bindParam(':toClan', $_POST['to'], PDO::PARAM_STR);
            $ontvanger->execute();
            $ontvanger = $ontvanger->fetch(PDO::FETCH_ASSOC);

            if ($ontvanger < 1) {
                print "<div class=\"red\">".$txt['alert_bank_transfer_clan_incorrect']."</div>";
            } else if ($_POST['gold'] <= $gold) {

                $depositClanQuery = "UPDATE `gebruikers` SET `gold`=`gold`-:gold WHERE `user_id`=:user_id;
                                    UPDATE `clans` SET `clan_gold`=`clan_gold`+:gold WHERE `clan_naam`=:clan_naam;
                                    INSERT INTO bank_logs (date, sender, receiver, amount, what,type)
                                    VALUES (NOW(),:fromUsername,:clan_naam,:gold,'transfer to clan','gold')";
                $depositClan = $db->prepare($depositClanQuery);
                $depositClan->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_STR);
                $depositClan->bindParam(':gold', $_POST['gold'], PDO::PARAM_STR);
                $depositClan->bindParam(':fromUsername', $ontvanger['username'], PDO::PARAM_STR);
                $depositClan->bindParam(':clan_naam', $ontvanger['clan_naam'], PDO::PARAM_STR);
                $depositClan->execute();

                print "<div class=\"green\">".$txt['alert_bank_transfer_clan_gold_success']."</div>";

            } else {
                print "<div class=\"red\">".$txt['alert_bank_transfer_clan_gold_funds']."</div>";
            }
        } else {
            print "<div class=\"red\">".$txt['alert_bank_transfer_clan_no_ammount']."</div>";
        }
    }
?>
    <br/><br/>
    <form method="post">
        <table width="60%">
            <tr>
                <td><img src="images/icons/user.png"/> <?=$txt['bank_transfer_to']?></td>
                <td><input type="text" class="bar curved5" name="to" value="<?=$_REQUEST['to']?>"></td>
            </tr>
            <tr>
                <td><img src="images/icons/silver.png"/> <?=$txt['bank_transfer_silver']?></td>
                <td><input type="text" class="bar curved5" name="silver" maxlength="7" value="<?=$_REQUEST['silver']?>">
                </td>
            </tr>
            <tr>
                <td><img src="images/icons/gold.png"/> <?=$txt['bank_transfer_gold']?></td>
                <td><input type="text" class="bar curved5" name="gold" maxlength="7" value="<?=$_REQUEST['gold']?>"></td>
            </tr>
            <tr>
                <td>
                    <button type="submit" class="button"><?=$txt['bank_transfer_button']?></button>
                </td>
            </tr>
        </table>
    </form>
<?

}