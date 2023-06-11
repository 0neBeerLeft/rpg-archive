<?php
include("includes/security.php");

if($gebruiker['admin'] < 3) header("Location: index.php?page=home");

if(isset($_POST['delete'])){
	$user_id = $_POST['id'];
	if($icon == "none"){
		$msg = '<div class="red"><img src="https://forum.ragezone.com/images/icons/red.png"> Niet gelukt.</div>';
    }else{
		mysql_query("DELETE FROM `gebruikers` WHERE `user_id`='".$user_id."'");
		mysql_query("DELETE FROM `gebruikers_item` WHERE `user_id`='".$user_id."'");
		mysql_query("DELETE FROM `gebruikers_badges` WHERE `user_id`='".$user_id."'");
		mysql_query("DELETE FROM `pokemon_speler` WHERE `user_id`='".$user_id."'");
		mysql_query("DELETE FROM `pokemon_gezien` WHERE `user_id`='".$user_id."'");
		mysql_query("DELETE FROM `transferlijst` WHERE `userid`='".$user_id."'");
		$msg = '<div class="green">Gelukt</div>';
	}
}
?>
<center><img src="<?=GLOBALDEF_SITELOGO?>" width="350px"><br/><br/><h2>Gebruiker verwijderen</h2><br/><hr></center>
<?php echo $msg; ?>
<form method="post">
<table width="350">
			<td><select name="id" class="text_select">
				<option value="none">Delete gebruiker</option>
				<?php 
                  $usersql = mysql_query("SELECT user_id, username FROM gebruikers WHERE `account_code`='1' ORDER BY username ASC");
                  while($user = mysql_fetch_array($usersql)){
                      echo '<option value="'.$user['user_id'].'">'.$user['username'].'</option>';
                  }
                ?>
			</select></td>
			<td><input type="submit" name="delete" value="verwijder" class="button" /></td>
</table>
</form>