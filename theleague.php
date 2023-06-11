<?php
//Define
$leagueid = 1;
?>
<style>
    td {
        text-align: center;
    }
</style>
<center>
    <h1>
        <div class="h1text">Toernooien</div>
    </h1>
    <font size="2" color="red"><b>
        </b></font>
    <?php
    $getleagues = mysql_query('SELECT * FROM `leagues` WHERE `mode` != "closed"');
    if (mysql_num_rows($getleagues) == 1) {
        $league = mysql_fetch_assoc($getleagues);
        // How many are registered?
        $registered = explode(",", $league['registered']);
        if ($league['mode'] == "reg") {
            $num = sizeof($registered);
            $placeleft = 16 - $num;
            if ($num == $league['capacity']) {
                echo '<div class="error"> Sorry, er zijn geen plaatsen meer voor de beschikbare toernooien.</div>';
                $updater = mysql_query('UPDATE `leagues` SET `mode` = "battle" WHERE `mode` != "closed"');
            } else {
                if ($_GET['act'] == "register") {
                    for ($i = 0; $i <= sizeof($registered) - 1; $i++) {
                        if ($registered[$i] == $_SESSION['id']) $regon = 1;
                    }
                    if ($regon) echo '<div class="error">Je bent al ingeschreven voor het toernooi!</div>'; else {
                        $checkwild = mysql_query('SELECT `wild_id` FROM `pokemon_speler` WHERE `user_id` = "' . $_SESSION['id'] . '" && `level` >=50');
                        if ($gebruiker['rank'] >= 8 && mysql_num_rows($checkwild) >= 6) {
                            ;
                            if ($league['registered'] == "") {
                                $newregistered = $_SESSION['id'];
                            } else {
                                $newregistered = '' . $league['registered'] . ',' . $_SESSION['id'] . '';
                            }
                            $addhim = mysql_query('UPDATE `leagues` SET `registered` = "' . $newregistered . '"');
                            if ($addhim) {
                                echo ' <div class="ok">U neemt deel aan het toernooi!</div>';
                            }
                        }
                    }
                }
            }
            ?>
            <div
                style="background-image:url('images/box.png');background-repeat:no-repeat;width:238px;height:152px;font-weight:bold;color:white;font-size:16px;font-family:Tahoma">
                <div style="padding-top:10px">
                    <strong><?= $league['lname'] ?></strong><br/>
                    <font style="font-size:14px"><b>Toernooistatus: <i>registratie</i></b></font><br/><br/>
                    <font
                        style="color:green;font-size:20px;background-color:black;padding:3px;margin:3px"><?= $placeleft ?></font><br/>
                    plaatsen beschikbaar<br/>
                    <a href="?page=theleague&act=register">Klik hier om deel te nemen in de competitie</a>
                </div>
            </div>
            <hr class="home">
            vergt:<br/>
           tenminste rank<b>8</b>
            <?php
            if ($gebruiker['rank'] >= 8) {
                echo '<img src="images/icons/tick.png" width="14" height="14">';
            } else {
                echo '<img src="images/icons/verwijder.png">';
                $cancel = 1;
            }
            ?><br/>
            6 Pokémon met Level 50+
            <?php
            $checkwild = mysql_query('SELECT `wild_id` FROM `pokemon_speler` WHERE `user_id` = "' . $_SESSION['id'] . '" && `level` >=50');
            if (mysql_num_rows($checkwild) >= 6) {
                echo '<img src="images/icons/tick.png" width="14" height="14">';
            } else {
                echo '<img src="images/icons/verwijder.png">';
                $cancel = 1;
            }
            ?><br/>
            <?php if ($cancel) {
                echo '<div class="error">Het is je niet toegestaan om deel te nemen aan deze competitie.</div>';
            } else { ?>
            <?php }
        } else if ($league['mode'] == "battle") {
            if ($league['round'] > 1) {
                $lastround = $league['round'] - 1;
                ?>
                <div style="background-color:#e1e1e1;padding:5px;border:1px solid #eee;width:500px;margin-bottom:5px">
                    <b>laatste ronde (<?= $lastround ?>)</b></div>
                <?php
                $getround = mysql_query('SELECT * FROM `league_battles` WHERE `round` = "' . $lastround . '" && `leagueid` = "' . $leagueid . '"');
                while ($round = mysql_fetch_assoc($getround)) {
                    $player1 = $round['player1'];
                    $player2 = $round['player2'];
                    $s++;
//Get user
                    $user1 = mysql_fetch_assoc(mysql_query('SELECT `username` FROM `gebruikers` WHERE `user_id` = "' . $player1 . '"'));
                    $user2 = mysql_fetch_assoc(mysql_query('SELECT `username` FROM `gebruikers` WHERE `user_id` = "' . $player2 . '"'));
//Get winner
                    if ($round['winner'] == 0) {
                        $winner = "<i>Keine Siege</i>";
                    } else {
                        $getwinner = mysql_query('SELECT username FROM gebruikers WHERE user_id = "' . $round['winner'] . '"');
                        $winner = mysql_fetch_assoc($getwinner);
                        $winner = '<a href="?page=profile&player=' . $winner['username'] . '"><font color="green"><b>' . $winner['username'] . '</b></font></a>';
                    }
                    ?>
                    <div
                        style="background-color:#E4E4E4;padding:2px;border:1px solid #eee;width:335px;margin:1px;float:right;margin-right:85px">
                        <?= $s ?>. <a
                            href="?page=profile&player=<?= $user1['username'] ?>"><b><?= $user1['username'] ?></b></a>
                        tegen <a href="?page=profile&player=<?= $user2['username'] ?>"><b><?= $user2['username'] ?></b></a><br/>
                    </div>
                    <div
                        style="background-color:#E4E4E4;padding:2px;border:1px solid #eee;width:150px;margin:1px;float:left;margin-left:85px">
                        <?= $winner ?>
                    </div>
                    <?php
                }
            }
            $s = 0;
            ?>
            <div style="Clear:both"></div>
            <hr class="home">
            <table style="background-color:white;padding:10px;width:600px;">
                <tr>
                    <th>#</th>
                    <th>Speler</th>
                    <th>overwinningen</th>
                    <th>gelijk</th>
                    <th>verliezen</th>
                    <th>punten</th>
                </tr>
                <?php
                $getplayers = mysql_query('SELECT * FROM `league_players` WHERE `leagueid` = "' . $leagueid . '" ORDER BY `win` DESC, `draw` DESC');
                while ($player = mysql_fetch_assoc($getplayers)) {
                    $x++;
// Get username
                    $getuser = mysql_fetch_assoc(mysql_query('SELECT `username` FROM `gebruikers` WHERE `user_id` = "' . $player['player'] . '"'));
//Calculate point
                    $wins = $player['win'];
                    $lose = $player['lose'];
                    $draw = $player['draw'];
                    $points = ($wins * 3 + $draw);
                    $query = mysql_query('SELECT player1 FROM league_battles WHERE (player1 = "' . $registered[$i] . '" || player2 = "' . $registered[$i] . '") && winner != "' . $registered[$i] . '"');
                    while ($xs = mysql_fetch_assoc($query)) {
                        if ($xs['winner'] != 0) {
                            $lose++;
                        }
                    }
                    if ($x == 1) {
                        $x = "<img src='images/icons/cup.png'>";
                        $p1 = true;
                    }
                    ?>
                    <tr style="<?php if ($p1 == 1) {
                        echo ';background-color:#BEFFB9;';
                    } ?>
                    <?php if ($x >= 10) {
                        echo ';background-color:#FFE1E1;';
                    } ?>
                    <?php if ($x == 2 || $x == 3) {
                        echo ';background-color:#FFF4E7;';
                    } ?>
                    <?php if ($x >= 4 && $x <= 9) {
                        echo ';background-color:#E1E1E1;';
                    } ?>
                        ">
                        <td style="border-bottom:1px dotted #C3C3C3"><?= $x ?></td>
                        <td style="border-bottom:1px dotted #C3C3C3;margin:1px"><a
                                href="?page=profile&player=<?= $getuser['username'] ?>"><?= $getuser['username'] ?></a>
                        </td>
                        <td style="border-bottom:1px dotted #C3C3C3;margin:1px"><font color="green"><b><?= $wins ?></b></font>
                        </td>
                        <td style="border-bottom:1px dotted #C3C3C3;margin:1px"><font
                                color="grey"><b><?= $draw ?></b></font></td>
                        <td style="border-bottom:1px dotted #C3C3C3;margin:1px"><font
                                color="red"><b><?= $lose ?></b></font></td>
                        <td style="border-bottom:1px dotted #C3C3C3;margin:1px"><b><?= $points ?></b></td>
                    </tr>
                    <?php
                    if ($p1) {
                        $x = 1;
                        $p1 = false;
                    }
                    $lose = 0;
                } ?>
            </table>
            <div style="Clear:both"></div>
            <hr class="home">
            <div style="background-color:#E4E4E4;padding:5px;border:1px solid #eee;width:500px;margin-bottom:5px"><b>De
                    volgende ronde <?= $league['round'] ?>loopt tot en met donderdag 18 uur!</b></div>
            <?php
            $getround = mysql_query('SELECT * FROM league_battles WHERE round = "' . $league['round'] . '" && leagueid = "' . $leagueid . '"');
            while ($round = mysql_fetch_assoc($getround)) {
                $player1 = $round['player1'];
                $player2 = $round['player2'];
                $s++;
                //Get user
                $user1 = mysql_fetch_assoc(mysql_query('SELECT `username` FROM `gebruikers` WHERE `user_id` = "' . $player1 . '"'));
                $user2 = mysql_fetch_assoc(mysql_query('SELECT `username` FROM `gebruikers` WHERE `user_id` = "' . $player2 . '"'));
                //Get winner
                if ($round['winner'] == 0) {
                    $winner = "<i>Keine Siege</i>";
                } else {
                    $getwinner = mysql_query('SELECT `username` FROM `gebruikers` WHERE `user_id` = "' . $round['winner'] . '"');
                    $winner = mysql_fetch_assoc($getwinner);
                    $winner = '<a href="?page=profile&player=' . $winner['username'] . '"><font color="green"><b>' . $winner['username'] . '</b></font></a>';
                }
                ?>
                <div
                    style="background-color:#E4E4E4;padding:2px;border:1px solid #eee;width:335px;margin:1px;float:right;margin-right:85px">
                    <?= $s ?>. <a href="?page=profile&player=<?= $user1['username'] ?>"><b><?= $user1['username'] ?></b></a>
                    tegen <a
                        href="?page=profile&player=<?= $user2['username'] ?>"><b><?= $user2['username'] ?></b></a><br/>
                </div>
                <div
                    style="background-color:#E4E4E4;padding:2px;border:1px solid #eee;width:150px;margin:1px;float:left;margin-left:85px">
                    <?= $winner ?>
                </div>
                <?php
            }
        }
    } else {
        echo '<div class="error">Er zijn geen toernooien beschikbaar.</div>';
    }
    ?>
</center>
