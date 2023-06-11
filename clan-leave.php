<?php
//Security laden
include('includes/security.php');

if($gebruiker['clan_id'] == '0') header('Location: index.php');

$page = 'clan-leave';
//Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

if(isset($_POST['submit'])){
	if($gebruiker['silver'] < 2000) echo '<div class="red"><img src="images/icons/red.png">	'.$txt['alert_not_enough_silver'].'</div>';
	elseif($gebruiker['clan_owner_id'] == $_SESSION['id']) echo '<div class="red"><img src="images/icons/red.png">	'.$txt['alert_owner'].'</div>';
	else{
		mysql_query("UPDATE clans SET clan_spelers_aantal = clan_spelers_aantal -'1' WHERE clan_id = '".$gebruiker['clan_id']."'");
		mysql_query("UPDATE gebruikers SET clan_id='0', silver=silver-'2000' WHERE user_id = '".$_SESSION['id']."'");
		echo '<div class="green"><img src="images/icons/green.png">	'.$txt['success_leave_clan'].'</div>';
	}
}

?>
<form method="post">
<center>
<strong><?php echo $txt['leave'].' '.$gebruiker['clan_naam']; ?>?</strong>
<p><?php echo $txt['title_text']; ?></p>
<button type="submit" name="submit" class="button"><?php echo $txt['button']; ?></button>
</center>
</form>