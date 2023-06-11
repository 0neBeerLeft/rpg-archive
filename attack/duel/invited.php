<?
$page = 'attack/duel/invited';
//Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

$duel = mysql_fetch_array($duel_sql);

if (isset($_POST['accept'])) {
    if ($gebruiker['silver'] < $duel['bedrag']) {
        echo '<div class="red"><img src="images/icons/red.png"> ' . $txt['alert_not_enough_silver'] . '</div>';
        mysql_query("UPDATE duel SET status='no_money' WHERE id='" . $duel['id'] . "'");
    } elseif (mysql_num_rows(mysql_query("SELECT id FROM pokemon_speler WHERE leven>'0' AND user_id='" . $_SESSION['id'] . "' AND opzak='ja'")) <= 0) {
        echo '<div class="red"><img src="images/icons/red.png"> ' . $txt['alert_all_pokemon_ko'] . '</div>';
        mysql_query("UPDATE duel SET status='all_dead' WHERE id='" . $duel['id'] . "'");
    } else {
        //Include Duel Functions
        include('attack/duel/duel-start.php');
        mysql_query("UPDATE duel SET status='accept', laatste_beurt_tijd='" . strtotime(date("Y-m-d H:i:s")) . "' WHERE id='" . $duel['id'] . "'");
        // Start Duel
        start_duel($duel['id'], 'tegenstander');
        $_SESSION['duel']['duel_id'] = $duel['id'];
        $_SESSION['duel']['begin_zien'] = true;
        header("Location: index.php?page=attack/duel/duel-attack");
    }
} elseif (isset($_POST['cancel'])) {
    echo '<div class="blue"><img src="images/icons/blue.png"> ' . $txt['success_cancelled'] . '</div>';
    mysql_query("DELETE FROM duel WHERE id='" . $duel['id'] . "'");
} else {
    if ($duel['status'] == "expired") {
        echo $duel['uitdager'] . ' ' . $txt['alert_too_late'];
        mysql_query("DELETE FROM duel WHERE id='" . $duel['id'] . "'");
    } else {
        if ($duel['bedrag'] > 0) $text = $txt['dueltext_1'] . ' <img src="images/icons/silver.png" title="Silver"> ' . $duel['bedrag'];
        echo '<center><img src="images/icons/duel.png"> ' . $duel['uitdager'] . ' ' . $txt['dueltext_2'] . '
      ' . $text . ' <img src="images/icons/duel.png"><br /><br />

      <form method="post" action="?page=attack/duel/invited">
      <input type="submit" name="accept" value="' . $txt['accept'] . '" class="button"> | <input type="submit" name="cancel" value="' . $txt['cancel'] . '" class="button">
      </form></center>
    ';
    }
}
?>