<?php
include("includes/security.php");

if (isset($_GET['player'])) $spelernaam = $_GET['player'];
else $spelernaam = $_POST['gebruiker'];

if (isset($_POST['respect'])) {
    if ($gebruiker['respect_add'] == 0) echo '<div class="red">Je hebt geen respect punten - kom morgen terug.</div>';
    elseif (empty($_POST['gebruiker'])) echo '<div class="red">Vul een gebruikersnaam in.</div>';
    elseif (mysql_num_rows(mysql_query("SELECT `user_id` FROM `gebruikers` WHERE `username`='" . $_POST['gebruiker'] . "'")) == 0) echo '<div class="red">De speler is niet gevonden.</div>';
    elseif ($gebruiker['rank'] < 2) echo '<div class="""red">Je kan pas respect punten geven vanaf rank twee.</div>';
    elseif (strtolower($_POST['gebruiker']) == strtolower($gebruiker['username'])) echo '<div class="red">Je kan jezelf geen respect punten geven.</div>';

    else {
        echo '<div class="green"><img src="images/icons/level_up.png" class="imglower"> Je hebt ' . $_POST['gebruiker'] . ' een respect punt gegeven.</div>';
        mysql_query("UPDATE `gebruikers` SET `respect`=`respect`+'1' WHERE `username`='" . $_POST['gebruiker'] . "'");
        mysql_query("UPDATE `gebruikers` SET `respect_add`=`respect_add`-'1' WHERE `username`='" . $gebruiker['username'] . "'");
        mysql_query("INSERT INTO respect_log (id, who, reciever, date, what)
		VALUES (NULL, '" . $gebruiker['username'] . "', '" . $_POST['gebruiker'] . "', NOW(), '1')");
    }
}

if (isset($_POST['disrespect'])) {
    if ($gebruiker['respect_add'] == 0) echo '<div class="red">Du hast keine Ehrenpunkte - Bitte schaue morgen wieder vorbei.</div>';
    elseif (mysql_num_rows(mysql_query("SELECT `user_id` FROM `gebruikers` WHERE `username`='" . $_POST['gebruiker'] . "'")) == 0) echo '<div class="red">Der Spieler ist nicht mehr verfügbar.</div>';
    elseif ($gebruiker['rank'] < 2) echo '<div class"red">Du kannst das Bewertungssystem erst ab Rang 2 nutzen.</div>';
    elseif (strtolower($_POST['gebruiker']) == strtolower($gebruiker['username'])) echo '<div class="red">Du kannst Dich nicht selbst bewerten.</div>';

    else {
        echo '<div class="green"><img src="images/icons/level_down.png" class="imglower">Du hast dem Spieler ' . $_POST['username'] . ' erfolgreich eine negative Bewertung gegeben..</div>';
        mysql_query("UPDATE `gebruikers` SET `respect`=`respect`-'1' WHERE `username`='" . $_POST['gebruiker'] . "'");
        mysql_query("UPDATE `gebruikers` SET `respect_add`=`respect_add`-'1' WHERE `username`='" . $gebruiker['username'] . "'");
        mysql_query("INSERT INTO respect_log (id, who, reciever, date, what)
		VALUES (NULL, '" . $gebruiker['username'] . "', '" . $_POST['gebruiker'] . "', NOW(), '0')");
    }
}
?>
<center>
    <div style="background: url(img/ui/poke-info.png) center; height:46px; width:500px;">
        <h2 style="color:#fff; font-size:16px; text-transform:uppercase; font-weight:bold; text-shadow:1px 1px 0px #000; padding-top:12px;">
            <img src="images/icons/friend.png"> Geef spelers respect <img src="images/icons/friend.png">
        </h2>
    </div>
    <style>.info {
            color: #000;
            background-color: #eee;
            padding: 5px;
            border: 1px solid #ccc;
            margin: 3px;
            text-align: center;
            width: 450px;
            font-weight: bold
        }</style>
    <div class="info">
        Een speler heeft je geholpen in het spel? <br/>
        Hier kunt u uw respect te uiten en behulpzaam coaches wijzen respect points<br/>
        Spelers met een hoge rating zijn meestal vriendelijk en behulpzame mensen.<br/>
        <div style="border-top:1px solid #ccc;margin:5px;"></div>
        <font color="red"><b>Opmerking: De handel en het kopen en verkopen van eer punten is ten strengste verboden!</b></font><br/>
        <div style="border-top:1px solid #ccc;margin:5px;"></div>
        U kunt nog steeds <u><?php echo $gebruiker['respect_add']; ?></u> Verdeel Respect points.
    </div>
</center>
<form method="post">
    <center>
        <table width="300" cellpadding="0" cellspacing="0">
            <td><img src="images/icons/user.png" alt="User" title="user"></td>
            <td><strong>Spelersnaam:</strong></td>
            <td><input type="text" name="gebruiker" value="<?php if ($_GET['player'] != '') echo $_GET['player'];
                else echo $spelernaam; ?>" id="player" class="text_long" maxlength="10"/></td>
            </tr>
            <tr>
                <td></td>
                <td><img src="images/icons/level_up.png" alt="friends" title="friends"></td>
                <td>
                    <div style="padding-top:2px;"><input name="respect" value="Abschicken" class="button" type="submit">
                </td>
            </tr>
        </table>
        <div class="sep"></div>
        <div class="info"> Als alternatief kunt u de eer punten aftrekken van een speler.</div>
        <table width="300" cellpadding="0" cellspacing="0">
            <td><img src="images/icons/user.png" alt="User" title="user"></td>
            <td><strong>Sperlersnaam:</strong></td>
            <td><input type="text" name="gebruiker" value="<?php if ($_GET['player'] != '') echo $_GET['player'];
                else echo $spelernaam; ?>" id="player" class="text_long" maxlength="10"/></td>
            </tr>
            <tr>
                <td></td>
                <td><img src="images/icons/level_down.png" alt="friends" title="friends"></td>
                <td>
                    <div style="padding-top:2px;"><input name="disrespect" value="Abschicken" class="button"
                                                         type="submit">
                </td>
            </tr>
        </table>
    </center>
</form>
<div class="sep"></div>
<center>
    <table width="500" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="4" style="background: url(img/ui/poke-infog.png) center; height:46px; width:500px;">
                <center>
                    <h3 style="color:#fff; font-size:16px; text-transform:uppercase; font-weight:bold; text-shadow:1px 1px 0px #000;">
                        <img src="images/icons/friend.png"> Top 10 de meest gerespecteerde spelers <img
                            src="images/icons/friend.png">
                    </h3>
                </center>
            </td>
        </tr>
        <tr>
            <td width="15" class="top_first_td">#</td>
            <td width="20" class="top_td">&nbsp;</td>
            <td width="200" class="top_td">Spelersnaam</td>
            <td width="50" class="top_td">Rating</td>
        </tr>
        <?php
        $top5aantalpokemonsql = mysql_query("SELECT username, premiumaccount, aantalpokemon, rating, respect FROM gebruikers WHERE admin = '0' AND account_code='1' ORDER BY respect DESC LIMIT 10");

        while ($top5aantalpokemon = mysql_fetch_array($top5aantalpokemonsql)) {

            if ($top5aantalpokemon['premiumaccount'] == 0) $star = '';
            else $star = '<img src="images/icons/vip.gif" width="16" height="16" border="0" alt="Premiumlid" title="Premiumlid" style="margin-bottom:-3px;">';
            $numberap2++;
            if ($numberap2 == 1) $medaille = "<img src='images/icons/plaatsnummereen.png'>";
            elseif ($numberap2 == 2) $medaille = "<img src='images/icons/plaatsnummertwee.png'>";
            elseif ($numberap2 == 3) $medaille = "<img src='images/icons/plaatsnummerdrie.png'>";
            else $medaille = "";
            if ($numberap2 == 1) {
                $style = 'style="background-color:gold;font-weight:bold;color:brown;padding:5px;"';
                $style2 = 'style="color:brown;"';
            } elseif ($numberap2 == 2) {
                $style = 'style="background-color:silver;font-weight:bold;color:#333;padding:5px;"';
                $style2 = 'style="color:#333;"';
            } elseif ($numberap2 == 3) {
                $style = 'style="background-color:#8C7853;font-weight:bold;color:#fff;padding:5px;"';
                $style2 = 'style="color:#fff;"';
            } else {
                $style = 'style="padding-right:5px;padding-top:2px;padding-bottom:2px;"';
                $style2 = '';
            }
            echo '<tr>
  			<td ' . $style . '> ' . $numberap2 . '.</td>
  			<td ' . $style . '> ' . $medaille . '</td>
			<td ' . $style . '><a href="index.php?page=profile&player=' . $top5aantalpokemon['username'] . '"  ' . $style2 . '>' . $top5aantalpokemon['username'] . '</a></td>
			<td ' . $style . '><img src="images/icons/friend.png" style="margin-bottom:-3px;" /> ' . number_format($top5aantalpokemon['respect']) . '</td>
		</tr>';
        }
        ?>
    </table>
</center>

