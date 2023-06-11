<?
#include dit script als je de pagina alleen kunt zien als je ingelogd bent.
include('includes/security.php');

$page = 'name-specialist';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

#Als je geen pokemon bij je hebt, terug naar index.
if ($gebruiker['in_hand'] == 0) {
    echo showAlert('red', $txt['alert_no_pokemon_in_hand']);
    return false;
}

if (isset($_POST['namenspecialist'])) {
    if (empty($_POST['nummer'])) {
        echo showAlert('red', $txt['alert_nothing_selected']);

    } else {
        foreach ($_POST['nummer'] as $nummer) {

            if ($gebruiker['silver'] < 40) {
                #heeft Speler wel genoeg silver?
                echo showAlert('red', $txt['alert_not_enough_silver']);
            } elseif (strlen($_POST['naam' . $nummer] > 12)) {
                #Naam lengte check?
                echo showAlert('red', $txt['alert_name_too_long']);
            } else {
                #Check of pokemon wel van de betreffende speler is
                $checkPrep = $db->prepare("SELECT user_id FROM pokemon_speler WHERE id =:pokemon_id");
                $checkPrep->bindParam(':pokemon_id', $_POST['pokemonid' . $nummer], PDO::PARAM_INT);
                $checkPrep->execute();
                $check = $checkPrep->fetch();

                if ($check['user_id'] != $_SESSION['id']) {
                    echo showAlert('red', '<img src="images/icons/red.png"> ' . $txt['alert_not_your_pokemon']);
                } else {
                    #Nieuwe naam opslaan
                    $update = $db->prepare("UPDATE pokemon_speler SET roepnaam=:name WHERE id=:pokemon_id;
                                          UPDATE gebruikers SET silver=silver-'40' WHERE user_id=:user_id");
                    $update->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_INT);
                    $update->bindValue(':name', $_POST['naam' . $nummer], PDO::PARAM_STR);
                    $update->bindParam(':pokemon_id', $_POST['pokemonid' . $nummer], PDO::PARAM_INT);
                    $update->execute();
                    echo showAlert('green', $txt['success_namespecialist'] . ' ' . $_POST['naam' . $nummer]);
                    refresh(3);
                }
            }
        }
    }
}

?>
<center>
    <p><?php echo $txt['title_text']; ?> <img src="images/icons/silver.png" title="Silver"/> 40.</p></center>

<center>
    <table width="260" cellpadding="0" cellspacing="0">
        <form method="post">
            <tr>
                <td width="50" class="top_first_td"><?php echo $txt['#']; ?></td>
                <td width="60" class="top_td">&nbsp;</td>
                <td width="150" class="top_td"><?php echo $txt['name_now']; ?></td>
            </tr>
            <?php

            //mysql_data_seek($pokemon_sql, 0);
            //for($teller=0; $pokemon = mysql_fetch_assoc($pokemon_sql); $teller++){
            foreach ($pokemon_sql as $key => $pokemon) {
                $pokemon = pokemonei($pokemon);
                $pokemon['naam'] = pokemon_naam($pokemon['naam'], $pokemon['roepnaam']);
                $popup = pokemon_popup($pokemon, $txt);

                echo "<tr>";
                #Als pokemon geen baby is
                if ($pokemon['ei'] != 1) {
                    echo '<td class="normal_first_td"><input type="radio" name="nummer[]" value="' . $key . '"/></td>';
                } else {
                    echo '<td class="normal_first_td"><input type="radio" name="pokemonid" disabled/></td>';
                }
                echo '<td class="normal_td"><a href="#" class="tooltip" onMouseover="showhint(\'' . $popup . '\', this)"><img src="' . $pokemon['animatie'] . '"></a></td>
                      <td class="normal_td">
                        <input type="text" name="naam' . $key . '" value="' . $pokemon['naam'] . '" class="text_long" maxlength="12" />
                        <input type="hidden" name="pokemonid' . $key . '" value="' . $pokemon['id'] . '" />
                      </td>
                    </tr>';
            }
            ?>
            <tr>
                <td colspan="2"></td>
                <td>
                    <br/>
                    <button type="submit" name="namenspecialist" class="button">
                        <?php echo $txt['button']; ?>shin
                    </button>
                </td>
            </tr>
        </form>
    </table>
</center>