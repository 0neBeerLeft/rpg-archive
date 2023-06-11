<?
if (($gebruiker['rank'] < 2) || ($gebruiker['in_hand'] == 0)) header('Location: index.php');

#Load language
$page = 'attack/duel/invite';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

$button = '';
/*#Kijken of je nog wel een itemplek overhebt
if($gebruiker['premiumaccount'] < 1){
	echo '<div class="blue"><img src="images/icons/blue.png"> '.$txt['alert_youre_not_premium'].'</div>';
	$button = 'disabled';
}*/

$getname = $_GET['player'];

if ((isset($_POST['duel']))) {
    if (!empty($_POST['naam'])) $getname = $_POST['naam'];
    if ($_SESSION['naam'] == $_POST['naam'])
        echo '<div class="red">' . $txt['alert_not_yourself'] . '</div>';
    /*elseif($gebruiker['premiumaccount'] == 0)
      echo '<div class="red">'.$txt['alert_youre_not_premium'].'</div>';*/
    elseif ($_POST['bedrag'] < 0)
        echo '<div class="red">' . $txt['alert_unknown_amount'] . '</div>';
    elseif (!ctype_digit($_POST['bedrag']))
        echo '<div class="red">' . $txt['alert_unknown_amount'] . '</div>';
    elseif ($gebruiker['silver'] < $_POST['bedrag'])
        echo '<div class="red">' . $txt['alert_not_enough_silver'] . '</div>';
    elseif (mysql_num_rows(mysql_query("SELECT `id` FROM `pokemon_speler` WHERE `leven`>'0' AND `user_id`='" . $_SESSION['id'] . "' AND opzak='ja'")) == 0)
        echo '<div class="red">' . $txt['alert_all_pokemon_ko'] . '</div>';
    else {
        $sql = mysql_query("SELECT user_id, username, wereld, rank, premiumaccount, `profielfoto`, dueluitnodiging, pagina FROM gebruikers WHERE username='" . $_POST['naam'] . "'");
        if (mysql_num_rows($sql) == 1) {
            $select = mysql_fetch_assoc($sql);
            /*if($select['premiumaccount'] <= 0)
              echo '<div class="red">'.$_POST['naam'].' '.$txt['alert_opponent_not_premium'].'</div>';*/
            if ($select['wereld'] != $gebruiker['wereld'])
                echo '<div class="red">' . $_POST['naam'] . ' ' . $txt['alert_opponent_not_in'] . ' ' . $gebruiker['wereld'] . '.</div>';
            elseif ($select['rank'] < 2)
                echo '<div class="red">' . $_POST['naam'] . ' ' . $txt['alert_opponent_not_traveller'] . '</div>';
            elseif ($select['dueluitnodiging'] == 0)
                echo '<div class="red">' . $_POST['naam'] . ' ' . $txt['alert_opponent_duelevent_off'] . '</div>';
            elseif (($select['pagina'] == "attack") OR ($select['pagina'] == "attack-trainer") OR ($select['pagina'] == "duel"))
                echo '<div class="red">' . $_POST['naam'] . ' ' . $txt['alert_opponent_already_fighting'] . '</div>';
            else {
                $date = date("Y-m-d H:i:s");
                $time = strtotime($date);
                mysql_query("INSERT INTO duel (datum, uitdager, tegenstander, u_character, t_character, bedrag, status, laatste_beurt_tijd, laatste_beurt)
          VALUES ('" . $date . "', '" . $_SESSION['naam'] . "', '" . $select['username'] . "', '" . $gebruiker['profielfoto'] . "', '" . $select['profielfoto'] . "', '" . $_POST['bedrag'] . "', 'wait', '" . $time . "', '" . $_SESSION['naam'] . "')");

                $duel_id = mysql_insert_id();
                $_SESSION['duel']['duel_id'] = $duel_id;

                #Include Duel Functions
                include_once('duel-start.php');
                #Start Duel
                start_duel($duel_id, 'uitdager');
                echo '<div class="blue">' . $_POST['naam'] . ' ' . $txt['waiting_for_accept'] . '<br /><br />
        Status: <span id="status">Wachten</span></div>';
                ?>

                <script type="text/javascript">
                    var t
                    function status_check() {
                        $.get("attack/duel/status_check.php?duel_id="+<? echo $duel_id; ?>+
                        "&sid=" + Math.random(), function (data) {
                            if (data == 0) {
                                $("#status").html("Wachten op acceptatie")
                                t = setTimeout('status_check()', 1000)
                            }
                            else if (data == 1) {
                                $("#status").html("Je duel is verlopen")
                                clearTimeout(t)
                            }
                            else if (data == 2) {
                                $("#status").html("Je duel is afgewezen")
                                clearTimeout(t)
                            }
                            else if (data == 3) {
                                $("#status").html("Geaccepteerd")
                                clearTimeout(t)
                                setTimeout("location.href='index.php?page=attack/duel/duel-attack'", 0)
                            }
                            else if (data == 4) {
                                $("#status").html("<?php echo $txt['alert_opponent_no_silver']; ?>")
                                clearTimeout(t)
                            }
                            else if (data == 5) {
                                $("#status").html("<?php echo $txt['alert_opponent_no_health']; ?>")
                                clearTimeout(t)
                            }
                        }
                    )
                        ;
                    }
                    status_check()
                </script>
                <?
            }
        } else  echo '<div class="red">' . $txt['alert_user_unknown'] . '</div>';
    }
}
?>
<form method="post">
    <center>
        <?php echo $txt['title_text']; ?>

        <table width="300">
            <tr>
                <td><img src="images/icons/user.png" style="margin-bottom:-3px;"/> <?php echo $txt['player']; ?></td>
                <td><input type="text" name="naam" class="text_long" value="<?php echo $getname; ?>"/></td>
            </tr>
            <tr>
                <td>
                    <img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;"/> <?php echo $txt['money']; ?>
                </td>
                <td>
                    <input type="text" name="bedrag" value="<?php if (!empty($_POST['bedrag'])) echo $_POST['bedrag']; else echo 0; ?>" class="text_long">
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <button type="submit" name="duel" class="button" <?php echo $button; ?>><?php echo $txt['button_duel']; ?></button>
                </td>
            </tr>
        </table>
    </center>
</form>