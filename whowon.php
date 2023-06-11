<?php
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

//Getall
$uid = mysql_real_escape_string($_GET['id']);
$sel = mysql_query('SELECT * FROM gebruikers WHERE user_id = "' . $uid . '"');
if (mysql_num_rows($sel) == 0) {
    echo '<div class="error">Gebruiker bestaat niet</div>';
} else {
    $inf = mysql_fetch_assoc($sel);

    ?>
    <table>
        <tr>
            <td valign="top">
                <div
                    style="background-color:#314263;padding:5px;border:1px solid #eee;width:300px;color:white;font-weight:bold;font-size:14px">
                    Overwinningen van <?= $inf['username'] ?></div>
                <div
                    style="background-color:#314263;padding:5px;border:1px solid #eee;width:300px;color:white;text-align:center">
                    <?php
                    $getwins = mysql_query('SELECT * FROM duel_logs WHERE win = "' . $uid . '"');
                    if (mysql_num_rows($getwins) == 0) {
                        echo 'Geen';
                    } else {
                        while ($win = mysql_fetch_assoc($getwins)) {
                            $x++;
                            //GetLoser
                            $getloser = mysql_fetch_assoc(mysql_query('SELECT * FROM gebruikers WHERE user_id = "' . $win['lose'] . '"'));
                            ?>
                            <?= $x ?>. <a href="?page=profile&player=<?= $getloser['username'] ?>"
                                          style="color:white"><?= $getloser['username'] ?></a><br/>
                        <?php }
                    } ?>
                </div>
            </td>
            <td valign="top">
                <div
                    style="background-color:#314263;padding:5px;border:1px solid #eee;width:300px;color:white;;font-weight:bold;font-size:14px">
                    Verliezen van <?= $inf['username'] ?></div>
                <div
                    style="background-color:#314263;padding:5px;border:1px solid #eee;width:300px;color:white;text-align:center">
                    <?php
                    $getwins = mysql_query('SELECT * FROM duel_logs WHERE lose = "' . $uid . '"');
                    if (mysql_num_rows($getwins) == 0) {
                        echo 'Geen';
                    } else {
                        while ($win = mysql_fetch_assoc($getwins)) {
                            //GetWinner
                            $getloser = mysql_fetch_assoc(mysql_query('SELECT * FROM gebruikers WHERE user_id = "' . $win['win'] . '"'));
                            $s++;
                            ?>
                            <?= $s ?>. <a href="?page=profile&player=<?= $getloser['username'] ?>"
                                          style="color:white"><?= $getloser['username'] ?></a><br/>
                        <?php }
                    } ?>
                </div>
            </td>
        </tr>
    </table>
<?php } ?>