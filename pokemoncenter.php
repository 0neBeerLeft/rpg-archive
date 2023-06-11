<?php
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

#Als je geen pokemon bij je hebt, terug naar index.
if ($gebruiker['in_hand'] == 0) header('Location: index.php');

$page = 'pokemoncenter';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

#Als er op de heal knop gedrukt word
if (isset($_POST['heal'])) {
    #Teller op 0 zetten voor het tellen van de pokemon
    $count = 0;
    #Voor alle enabelde checkboxen tellen welke aangevinkt zijn
    $missieAlert = 1;
    for ($i = 0; $i <= $_POST['teller']; $i++) {
        #Makkelijke naam toewijzen
        $pokeid = $_POST['pokemonid' . $i];
        #Kijken als $pokeid numeriek is voor het tellen van de pokemon.
        if (is_numeric($pokeid)) {
            #Is het nummeriek er 1 bij optellen
            if ($gebruiker['admin'] == 1) $count = 1;
            elseif ($gebruiker['premiumaccount'] >= 1) $count = 10;
            else $count = 10;
        }
        #Voor alle pokemon leven op 100% zetten en effect weghalen
        $healAll = $db->prepare("UPDATE `pokemon_speler` SET `leven`=`levenmax`, `effect`='' WHERE `id`=:pokeId");
        $healAll->bindValue(':pokeId', $pokeid, PDO::PARAM_INT);
        $healAll->execute();
        //complete mission 1
        if ($gebruiker['missie_1'] == 0) {
            $completeMission = $db->prepare("UPDATE `gebruikers` SET `missie_1`=1, `silver`=`silver`+50,`rankexp`=rankexp+50 WHERE `user_id`=:uid");
            $completeMission->bindValue(':uid', $gebruiker['user_id'], PDO::PARAM_INT);
            $completeMission->execute();

            if ($missieAlert == 1) {
                echo showToastr("info", "Je hebt een missie behaald!");
            }
            $missieAlert++;
        }
    }

    #Pokemon center tijden opstellen

    if ($gebruiker['premiumaccount'] > 0) {
        $tijdnu = date('Y-m-d H:i:s', strtotime('-1 hour'));
    } else {
        $tijdnu = date('Y-m-d H:i:s');
    };
    #Tijden opslaan
    $saveTimes = $db->prepare("UPDATE `gebruikers` SET `pokecentertijdbegin`=:timeNow, `pokecentertijd`=:countTime WHERE `user_id`=:uid");
    $saveTimes->bindValue(':timeNow', $tijdnu);
    $saveTimes->bindValue(':countTime', $count);
    $saveTimes->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
    $saveTimes->execute();
    #Is er geen tijd, dus ook geen pokemon opgegeven, dan bericht niet laten zien

    if (($count == 10) OR ($count == 20) OR ($count == 30) OR ($count == 40) OR ($count == 50) OR ($count == 60)) {
        $wat = "seconden";
    }


    if ($gebruiker['premiumaccount'] > 0) {
        $error = '<div class="green">' . $txt['success_pokecenter_premium'] . '</div><br/><br/>';
    } else {
        $error = '<div class="green">' . $txt['success_pokecenter'] . ' ' . $count . ' ' . $wat . '.</div><br/><br/>';
    }

}
?>
<script language="javascript">
    var checked = 0;

    function checkAll() {
        checked = !checked;
        for (i = 0; i < document.pokecenter.elements.length; i++) {
            if (document.pokecenter.elements[i].name != 'niet') {
                document.pokecenter.elements[i].checked = checked;
            }
        }
    }
</script>
<?php if (isset($_POST['heal'])) echo $error; ?>
<center>
    <table width="520" cellpadding="0" cellspacing="0">
        <form method="post" name="pokecenter">
            <tr>
                <td colspan="5">
                    <center>
                        <?php
                        if ($gebruiker['premiumaccount'] > 0) echo $txt['title_text_premium'];
                        else echo $txt['title_text_normal'];
                        ?>

                    </center>
                    <br/><br/>
                </td>
            </tr>
            <tr>
                <td width="140" rowspan="8" valign="top"><img src="images/pokemoncenter.gif"></td>
                <td width="60" class="top_first_td" align="center"><input type="checkbox" onClick="checkAll()"></td>
                <td width="100" class="top_td">
                    <center><?php echo $txt['who']; ?></center>
                </td>
                <td width="260" class="top_td"><?php echo $txt['health']; ?></td>
            </tr>
            <?
            #Pokemon query ophalen
            for ($teller = 1; $pokemon = $pokemon_sql->fetch(PDO::FETCH_ASSOC); $teller++) {
                #Gegevens juist laden voor de pokemon
                $pokemon = pokemonei($pokemon);
                $pokemon['naam'] = pokemon_naam($pokemon['naam'], $pokemon['roepnaam']);

                echo "<tr>";
                #Als pokemon geen baby is
                if ($pokemonei['ei'] != 1)
                    echo '<td class="normal_first_td"><center><input type="checkbox" state="enabled" name="pokemonid' . $teller . '" value="' . $pokemon['id'] . '"/></center></td>
          		<input type="hidden" name="teller" value="' . $teller . '">';
                else
                    echo '<td class="normal_first_td"><center><input type="checkbox" id="niet' . $i . '" name="niet" disabled/></center></td>';

                echo '<td class="normal_td"><center><img src="' . $pokemon['animatie'] . '" width="32" height="32"></center></td>';
                #Als pokemon geen baby is
                if ($pokemonei['ei'] != 1)
                    echo '<td class="normal_td"><div class="bar_red">
				  		<div class="progress" style="width: ' . $pokemon['levenprocent'] . '%"></div>
			  		</div></td>';

                else echo "<td class='normal_td'>" . $txt['nvt'] . "</td>";

                echo "</tr>";

            }
            ?>
            <tr>
                <td colspan="4">
                    <button type="submit" name="heal" class="button"><?php echo $txt['button']; ?></button>
                </td>
                </td>
            </tr>
        </form>
    </table>
</center>