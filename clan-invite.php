<?
//Security laden
include('includes/security.php');

if (($gebruiker['rank'] < 5)) header('Location: index.php');

#clan laden
$clanQuery = "SELECT * FROM `clans` WHERE `clan_naam`=:clan";
$clan = $db->prepare($clanQuery);
$clan->bindParam(':clan', $gebruiker['clan'], PDO::PARAM_INT);
$clan->execute();
$clan = $clan->fetch(PDO::FETCH_ASSOC);

if (!empty($clan)) {

    #total clan members
    $clanmembers = 10 * $clan['clan_level'];
    #invite left
    $claninvites = $clanmembers - $clan['clan_spelersaantal'];

    #Load language
    $page = 'clan-invite';
    #Goeie taal erbij laden voor de page
    include_once('language/language-pages.php');

    if ((isset($_POST['submit']))) {

        $getInviteQuery = "SELECT `user_id`,`clan` FROM `gebruikers` WHERE `username`=:username";
        $getInvite = $db->prepare($getInviteQuery);
        $getInvite->bindParam(':username', $_POST['naam'], PDO::PARAM_STR);
        $getInvite->execute();
        $getInvite = $getInvite->fetch(PDO::FETCH_ASSOC);

        $getname = $_POST['naam'];
        $time = time();
        $code = rand(100000, 999999);

        #check of clanleader.
        if ($_SESSION['naam'] != $clan['clan_owner']) {
            echo '<div class="red">' . $txt['alert_no_clan_leader'] . '</div>';
        } #check of ingevuld
        elseif (empty($getname)) {
            echo '<div class="red">' . $txt['alert_no_name'] . '</div>';
        } #check of de clan vol is.
        elseif ($claninvites == 0) {
            echo '<div class="red">' . $txt['alert_clan_full'] . '</div>';
        } #check of al in clan
        elseif ($getInvite['clan'] != "") {
            echo '<div class="red">' . $txt['alert_already_in_clan'] . '</div>';
        } #check of user bestaat
        elseif (empty($getInvite)) {
            echo '<div class="red">' . $txt['alert_player_does_not_exist'] . '</div>';
        } else {

            #opslaan in clan_invites
            $setInviteQuery = "INSERT INTO `clan_invites` (`invite_clannaam`, `invite_usernaam`, `time`, `code`)
                                VALUES (:clan, :getname, :time, :code)";
            $setInvite = $db->prepare($setInviteQuery);
            $setInvite->bindParam(':clan', $clan['clan_naam'], PDO::PARAM_STR);
            $setInvite->bindParam(':getname', $getname, PDO::PARAM_STR);
            $setInvite->bindParam(':time', $time, PDO::PARAM_STR);
            $setInvite->bindParam(':code', $code, PDO::PARAM_STR);
            $setInvite->execute();

            $claninputid = $db->lastInsertId();
            $event = $txt['invite_text'];

            $sendInviteQuery = "INSERT INTO gebeurtenis (id, datum, ontvanger_id, bericht, gelezen)
                                VALUES (NULL, NOW(), :user_id, :event, '0')";
            $sendInvite = $db->prepare($sendInviteQuery);
            $sendInvite->bindParam(':user_id', $getInvite['user_id'], PDO::PARAM_STR);
            $sendInvite->bindParam(':event', $event, PDO::PARAM_STR);
            $sendInvite->execute();

            echo '<div class="green">'.$txt['invite_sent'].'</div>';
        }
    }
    ?>
    <form method="post">
        <center>
            <p><strong><?=$txt['invite_a_player_for_clan']?></strong><br/><br/></p>
            <?=$txt['max_invite_text']?>
            <table width="300">
                <tr>
                    <td>
                        <label for="naam"><img src="images/icons/user.png" width="16" height="16" alt="Player" class="imglower"/> <?php echo $txt['player']; ?>
                        </label></td>
                    <td colspan="2">
                        <input type="text" name="naam" id="naam" class="text_long" value="<?php echo $getname; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="2">
                        <button type="submit" name="submit" class="button"><?=$txt['invite_button']?></button>
                    </td>
                </tr>
            </table>
        </center>
    </form>
    </br>
    <?php

    $clanInviteQuery = "SELECT * FROM clan_invites WHERE invite_clannaam =:clan ORDER BY invite_id DESC";
    $clanInvite = $db->prepare($clanInviteQuery);
    $clanInvite->bindParam(':clan', $clan['clan'], PDO::PARAM_STR);
    $clanInvite->execute();
    $clanInvite = $clanInvite->fetch(PDO::FETCH_ASSOC);


    if (empty($clanInvite)) {
        echo "<div class='red'>".$txt['outstanding_invites']."</div>";
    } else {
        ?>
        <p><?php echo $txt['title_text']; ?></p>

        <center>
        <table width="350" cellpadding="0" cellspacing="0">
            <tr>
                <td class="top_first_td" width="50"><?php echo '#'; ?></td>
                <td class="top_td" class="150"><?php echo 'Name'; ?></td>
                <td class="top_td" class="150"><?php echo 'Date'; ?></td>
            </tr>
            <?php

            $number = 0;
            foreach ($clanInvite as $invite) {

                $number++;
                if ($clan['premiumaccount'] > 0) $premiumimg = '<img src="images/icons/lidbetaald.png" width="16" height="16" border="0" alt="Premiumlid" title="Premiumlid" style="margin-bottom:-3px;">';
                echo '<tr>
				<td class="normal_first_td">' . $number . '.</td>
				<td class="normal_td"><a href="?page=profile&player=' . $invite['invite_usernaam'] . '">' . $invite['invite_usernaam'] . '</a></td>
				<td class="normal_td">' . date("d-m-Y H:i", $invite['time']) . '</td>
			  </tr>';
            }
            ?>
        </table>
        </center>
        <?
    }
} else {

    echo $txt['no_clan'];

}