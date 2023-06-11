<?
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");
if($gebruiker['premiumaccount'] >= 1) {
    if (($gebruiker['rank'] < 5) && ($_GET['shopitem'] == 'attacks')) header('Location: index.php?page=pokemarket');

    $page = 'premiummarket';
#Goeie taal erbij laden voor de page
    include_once('language/language-pages.php');

    $sql = mysql_query("SELECT `id`, `naam`, `silver`, `gold`, `omschrijving_" . GLOBALDEF_LANGUAGE . "` FROM `markt` WHERE `soort`='premium'");
#Als er op de knop gedrukt word
    if (isset($_POST['balls'])) {
        $gebruiker_silver = $gebruiker['silver'];
        $gebruiker_gold = $gebruiker['gold'];
        #Laden voor de verwerking van de informatie
        for ($i = 1; $i <= $_POST['teller']; $i++) {
            #Item id opvragen
            $itemid = $_POST['id' . $i];
            #Aantal opvragen van het itemid
            $aantal = $_POST['aantal' . $itemid];
            #Als er geen aantal is
            if (empty($aantal)) $niksingevoerd = True;
            elseif (!is_numeric($aantal)) $niksingevoerd = True;
            #Als er wel een aantal is
            elseif (!empty($aantal)) {
                #Item gegevens laden
                $itemgegevens = mysql_fetch_assoc(mysql_query("SELECT `naam`, `silver`, `gold` FROM `markt` WHERE `id`='" . $itemid . "'"));
                #silver of gold berekenen voor de balls
                if ($itemgegevens['naam'] == 'silver') {
                    $goldd = $aantal * ($itemgegevens['gold'] / 1);
                }
                elseif ($itemgegevens['naam'] == 'Legend kans') {
                    $goldd = $aantal * ($itemgegevens['gold'] / 1);
                }
                else {
                    $silverr = $aantal * ($itemgegevens['silver'] / 1);
                }
                #itemruimte over berekenen
                $ruimteover = $ruimte['max'] - $gebruiker['items'];
                #Kijken als het silver er wel voor is
                if (($gebruiker_silver < $silverr) OR ($gebruiker_gold < $goldd)) {
                    echo '<div class="red">' . $txt['alert_not_enough_money'] . '</div>';
                } elseif ($aantal < 0)
                    echo '<div class="red">' . $txt['alert_not_enough_money'] . '</div>';
                elseif ($itemgegevens['naam'] == 'Legend kans' and $aantal != 1)
                    echo '<div class="red">' . $txt['alert_not_incorrect_amount'] . '</div>';
                elseif (!ctype_digit($aantal))
                    echo '<div class="red">' . $txt['alert_not_enough_money'] . '</div>';
                elseif ((3 * 3600) + $gebruiker['legendkans'] >= time())
                    echo '<div class="red">Legend kans vergroter is al actief.</div>';
                else {
                    #Opslaan
                    $totalesilver += $silverr;
                    $gebruiker_silver -= $silverr;
                    $totalegold += $goldd;
                    $gebruiker_gold -= $goldd;
                    $feedbackmessage = '<div class="green">' . $txt['success_market'] . ' ' . $itemgegevens['naam'] . ' ' . $aantal . 'x gekocht.</div>'; refresh(3,'?page=premiummarket');
                    if($itemgegevens['naam'] == "gold") {
                        $aantal =  $aantal*1;
                        mysql_query("UPDATE `gebruikers` SET `gold`=`gold`+'" . $aantal . "' WHERE `user_id`='" . $_SESSION['id'] . "'");
                    }else if($itemgegevens['naam'] == "geluksrad") {
                        $aantal =  $aantal*1;
                        mysql_query("UPDATE `gebruikers`  SET `geluksrad`=`geluksrad`+'" . $aantal . "' WHERE `user_id`='" . $_SESSION['id'] . "'");
                    }else if($itemgegevens['naam'] == "vissen") {
                        $aantal =  $aantal*3;
                        mysql_query("UPDATE `gebruikers`  SET `fish`=`fish`+'" . $aantal . "' WHERE `user_id`='" . $_SESSION['id'] . "'");
                    }else if($itemgegevens['naam'] == "silver") {
                        $aantal =  $aantal*1500;
                        mysql_query("UPDATE `gebruikers`  SET `silver`=`silver`+'" . $aantal . "' WHERE `user_id`='" . $_SESSION['id'] . "'");
                    }else if($itemgegevens['naam'] == "Legend kans") {
                        mysql_query("UPDATE `gebruikers`  SET `legendkans`=NOW() WHERE `user_id`='" . $_SESSION['id'] . "'");
                    }else{
                        $feedbackmessage = '<div class="red">Er is iets fout gegaan.</div>';
                    }

                    echo $feedbackmessage;
                }
                $welingevoerd = True;
            }
        }
        #silver opslaan
        mysql_query("UPDATE `gebruikers` SET `silver`=`silver`-'" . $totalesilver . "', gold=gold-'" . $totalegold . "' WHERE `user_id`='" . $_SESSION['id'] . "'");
        if (!$welingevoerd) {
            if ($niksingevoerd) {
                echo '<div class="red">' . $txt['alert_nothing_selected'] . '</div>';
            }
        }
    }
    ?>
    <script>
       function checkValue(){
           var input = document.getElementById('legend').value;
           console.log(input);
            if(input<0 || input>1)
                document.getElementById("warning").style.display = "";
                setTimeout(displayNone, 3000);
            return;
        }
        function displayNone() {
            document.getElementById("warning").style.display = "none";
            document.getElementById("legend").value = "";
        }
    </script>
    <div class="red" id="warning" style="display: none;">Legend kans vergroten kan je maximaal een van kopen</div>
    <table width="660" cellpadding="0" cellmargin="0">
        <tr>
            <td width="120" rowspan="54" valign="top"><img src="images/market.gif"/></td>
        </tr>
        <?php #Form starten
        echo '<form method="POST" name="balls">
	  <tr>
	  	<td>';

        for ($j = 1; $select = mysql_fetch_assoc($sql); $j++) {
            if ($select['naam'] == "silver" or $select['naam'] == "Legend kans") {
                $icon = 'gold';
                $prijs = number_format(round($select['gold']), 0, ",", ".");

            } else {
                $icon = 'silver';
                $prijs = number_format(round($select['silver']), 0, ",", ".");
            }
            ?>
            <div style="padding:10px; float:left;">
		  <table width="80" class="greyborder">
		  	<tr>
				<td><center><input type="hidden" name="teller" value="<?=$j?>">
				<input type="hidden" name="id<?=$j?>" value="<?=$select['id']?>">
				<img src="images/items/<?=$select['naam']?>.png" width=24 height=24 /></center></td>
			</tr>
			<tr>
				<td><span class="smalltext"><center><?=$select['naam']?></center></span></td>
			</tr>
			<tr>
				<td><span class="smalltext"><center><img src="images/icons/<?=$icon?>.png" alt="<?=$icon?>" title="<?=$icon?>" style="margin-bottom:-3px;"> <?=$prijs?></center></span></td>
			</tr>
			<tr>
				<td><div class="smalltext" style="padding-top:5px;"><center><a href="#" class="tooltip" onMouseover="showhint('<?=$select['omschrijving_' . $_COOKIE['pa_language']]?>', this)">[?]</a></center></div></td>
			</tr>
			<tr>
				<td>
                    <div style="padding-top:5px;">
                        <center>
                            <?if($select['naam'] == "Legend kans"){?>
                                <?if((3 * 3600) + $gebruiker['legendkans'] >= time()){?>
                                    <input type="text" class="text_short" id="legend" placeholder="actief" style="float:none;width: 35px;" disabled/>
                                <?}else{?>
                                    <input type="text" class="text_short" id="legend" onchange="checkValue();" name="aantal<?=$select['id']?>" placeholder="0" style="float:none;" />
                                <?}?>
                            <?}else{?>
                                <input type="text" maxlength="2" class="text_short" name="aantal<?=$select['id']?>" placeholder="0" style="float:none;" />
                            <?}?>
                        </center>
                    </div>
                </td>
			</tr>
		  </table>
		  </div>
        <?
        }
        echo '</td></tr>';
        ?>
        <tr>
            <td>
                <div style="padding-left:10px;"><button type="submit" name="balls" class="button" >Kopen</button></div>
            </td>
        </tr>
        </form>

    </table>
    <?
} else {
    echo "<center>Alleen premium leden kunnen gebruik maken van de premium shop, koop <a href='?page=area-market'>hier</a> premium</center>";
}
?>