<?php
//Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

$page = 'tour';
//Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

#Nieuw toernooi
$new_tour_sql = mysql_query("SELECT toernooi, tijd, sluit FROM toernooi WHERE deelnemers='0'");

if (mysql_num_rows($new_tour_sql) > 0) {

    $new_tour = mysql_fetch_array($new_tour_sql);
    $join_sql = mysql_query("SELECT id FROM toernooi_inschrijving WHERE toernooi='" . $new_tour['toernooi'] . "' AND user_id='" . $_SESSION['id'] . "'");
    if (isset($_POST['inschrijven'])) {
        /*if($gebruiker['premiumaccount'] < 1)
            echo '<div class="red"><img src="images/icons/red.png"> You need Premium Member to participate in the tournament.</div>';
        else	  if($gebruiker['rank'] < 5)
            echo '<div class="red"><img src="images/icons/red.png"> You must have a low rank to participate.</div>';*/
        if (mysql_num_rows($join_sql) > 0)
            echo '<div class="red"><img src="images/icons/red.png"> You are already registered for this tournament.</div>';
        else {
            $test = mysql_query("INSERT INTO toernooi_inschrijving (toernooi, user_id)
  		  VALUES ('" . $new_tour['toernooi'] . "', '" . $_SESSION['id'] . "')");
            echo '<div class="green"><img src="images/icons/green.png"> You have successfully registered.</div>';
        }
    }

    if (mysql_num_rows($join_sql) == 0) {
        $count = strtotime($new_tour['sluit'] . $new_tour['tijd']) - strtotime(date("Y-m-d H:i:s"));

        echo '<center><p>
      		Je kan je nu aanmelden voor het volgende toernooi.<br />
      		Meld je alleen aan als je tijd hebt voor dit evenement.<br /><br />
      		<form method="post"><button type="submit" name="inschrijven" class="button">Aanmelden</button></form>
    		</center>';
    } else echo '<div class="blue"><img src="images/icons/blue.png"> Het programma wordt gemaakt op <br/><center>' . $new_tour['sluit'] . ' / ' . $new_tour['tijd'] . '.</center></div>';

    #Showen wie er nu allemaal deelnemen aan het toernooi
    $ingeschrevensql = mysql_query("SELECT gebruikers.user_id, gebruikers.username, gebruikers.premiumaccount, gebruikers.admin
								FROM toernooi_inschrijving
								INNER JOIN gebruikers
								ON toernooi_inschrijving.user_id = gebruikers.user_id
								WHERE toernooi_inschrijving.toernooi = '" . $new_tour['toernooi'] . "'
								ORDER BY gebruikers.rank DESC, gebruikers.rankexp DESC, gebruikers.username ASC");

    echo '<div style="padding-top:15px;"><strong>Aanmeldingen (' . mysql_num_rows($ingeschrevensql) . '):</strong><br />';
    while ($row = mysql_fetch_array($ingeschrevensql)) {
        $teller++;

        $fixt = ',' . $row['user_id'] . ',';

        $buddy_check = strpos($gebruiker['buddy'], $fixt);
        $block_check = strpos($gebruiker['blocklist'], $fixt);

        //Naam dik gedrukt maken als de online speler een admin is
        if ($row['admin'] == 1) $name = "<b>" . $row['username'] . "</b>";
        elseif ($row['username'] == $gebruiker['username']) $name = "<span class='selftext'>" . $row['username'] . "</span>";
        elseif ($buddy_check !== false) $name = "<span class='buddytext'>" . $row['username'] . "</span>";
        elseif ($block_check !== false) $name = "<span class='blocktext'>" . $row['username'] . "</span>";
        else $name = $row['username'];

        //Premiumaccount check, true = ster
        if (($row['premiumaccount'] > 0) && ($row['admin'] == 0))
            $name .= '<img src="images/icons/lidbetaald.png" width="16" height="16" border="0" alt="Premiumlid" title="Premiumlid" style="margin-bottom:-3px;">';
        if ($aantal == 1) echo '<a href="?page=profile&player=' . $row['username'] . '">' . $name . '</a> ';
        elseif (mysql_num_rows($ingeschrevensql) > $teller) echo '<a href="?page=profile&player=' . $row['username'] . '">' . $name . '</a> | ';
        else echo '<a href="?page=profile&player=' . $row['username'] . '">' . $name . '</a>';
    }
    echo '</div>';
} else {
    $tour_sql = mysql_query("SELECT * FROM toernooi WHERE ronde != '' ORDER BY toernooi DESC");
    echo "<pre>";
    var_dump($tour_sql);
    echo "</pre>";
    if (mysql_num_rows($tour_sql)) {
        $tour = mysql_fetch_array($tour_sql);
        if (empty($_GET['ronde'])) $ronde = $tour['huidige_ronde'];
        else $ronde = $_GET['ronde'];

        #Pagina systeem
        $links = false;
        $rechts = false;
        echo '<tr><td colspan=6><center><div class="sabrosus">';
        if ($ronde == 1)
            echo '<span class="disabled"> &lt; </span>';
        else {
            $back = $ronde - 1;
            echo '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . $_GET['page'] . '&ronde=' . $back . '"> &lt; </a>';
        }
        for ($i = 1; $i <= $tour['ronde']; $i++) {
            if ($i == 1) $itekst = 'Finale';
            elseif ($i == 2) $itekst = 'Halve Finale';
            elseif ($i == 3) $itekst = 'Kwart Finale';
            else $itekst = $i;
            if ((2 >= $i) && ($ronde == $i))
                echo '<span class="current">' . $itekst . '</span>';
            elseif ((2 >= $i) && ($ronde != $i))
                echo '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . $_GET['page'] . '&ronde=' . $i . '">' . $itekst . '</a>';
            elseif (($tour['ronde'] - 2 < $i) && ($ronde == $i))
                echo '<span class="current">' . $itekst . '</span>';
            elseif (($tour['ronde'] - 2 < $i) && ($ronde != $i))
                echo '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . $_GET['page'] . '&ronde=' . $i . '">' . $itekst . '</a>';
            else {
                $max = $ronde + 3;
                $min = $ronde - 3;
                if ($subpage == $i)
                    echo '<span class="current">' . $itekst . '</span>';
                elseif (($min < $i) && ($max > $i))
                    echo '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . $_GET['page'] . '&ronde=' . $i . '">' . $itekst . '</a>';
                else {
                    if ($i < $ronde) {
                        if (!$links) {
                            echo '...';
                            $links = True;
                        }
                    } else {
                        if (!$rechts) {
                            echo '...';
                            $rechts = True;
                        }
                    }
                }
            }
        }
        if ($tour['ronde'] == $ronde)
            echo '<span class="disabled"> &gt; </span>';
        else {
            $next = $ronde + 1;
            echo '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . $_GET['page'] . '&ronde=' . $next . '"> &gt; </a>';
        }
        echo "</div></center></td></tr>";
    } else echo '<div class="blue"><img src="images/icons/blue.png"> No Tournament being held at this time.</div>';

    #Einde pagina systeem


    $ronde_sql = mysql_query("SELECT * FROM toernooi_ronde WHERE toernooi='" . $tour['toernooi'] . "' AND ronde='" . $ronde . "'");
    echo '<center>
  		<table width="300" cellpadding="0" cellspacing="0" style="padding-top:8px;">
  			<tr>
  				<td class="top_first_td" width="100"><div align="right">Player 1</div></td>
  				<td class="top_td" width="100"><center>Position</center></td>
  				<td class="top_td" width="100">Player 2</td>
  			</tr>';

    while ($ronde = mysql_fetch_array($ronde_sql)) {
        if ($ronde['gereed'] == 2) {
            $speler1 = mysql_fetch_array(mysql_query("SELECT username, premiumaccount FROM gebruikers WHERE user_id='" . $ronde['user_id_1'] . "'"));
            $speler2 = mysql_fetch_array(mysql_query("SELECT username, premiumaccount FROM gebruikers WHERE user_id='" . $ronde['user_id_2'] . "'"));
            $dood_1 = "??";
            $dood_2 = "??";
            $star1 = "";
            $star2 = "";
            if ($speler1['premiumaccount'] >= 1) $star1 = '<img src="images/icons/lidbetaald.png" width="16" height="16" border="0" alt="Premiumlid" title="Premiumlid" style="margin-bottom:-3px;">';
            if ($speler2['premiumaccount'] >= 1) $star2 = '<img src="images/icons/lidbetaald.png" width="16" height="16" border="0" alt="Premiumlid" title="Premiumlid" style="margin-bottom:-3px;">';
            if ($ronde['winnaar_id'] > 0) {
                $dood_1 = $ronde['dood_1'];
                $dood_2 = $ronde['dood_2'];
                if ($dood_1 == 0 && $dood_2 == 0) {
                    if ($ronde['winnaar_id'] == $ronde['user_id_1']) $dood_2 = '0 *';
                    elseif ($ronde['winnaar_id'] == $ronde['user_id_2']) $dood_1 = '0 *';
                }
            }

            echo '<tr>
  			<td class="normal_first_td"><div align="right"><a href="?page=profile&player=' . $speler1['username'] . '">' . $speler1['username'] . $star1 . '</a></div></td>
  			<td class="normal_td"><center>' . $dood_2 . ' - ' . $dood_1 . '</center></td>
  			<td class="normal_td"><a href="?page=profile&player=' . $speler2['username'] . '">' . $speler2['username'] . $star2 . '</a></td>
  		</tr>';
        } else {
            echo '<tr>
  			<td colspan="3"><center>contest unknown.</center></td>
  		</tr>';
        }
    }

    echo '</table>
  	</center>';

}
?>