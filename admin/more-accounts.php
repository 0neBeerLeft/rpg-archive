<?		
//Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

//Admin controle
if($gebruiker['admin'] < 1) header('location: index.php?page=home');

?>

<form method="post">
  <center><a href="#"><input type="submit" name="aanmeld" value="Gebruikers" class="button"></a> - <input type="submit" name="login" value="Logins" class="button"><br /><br /></center>
</form>
<?
//Als er op 1 van de 2 word gedrukt
if((isset($_POST['aanmeld'])) OR (isset($_POST['login']))){

 //dubbel login
  if(isset($_POST['login'])){
	
		$select = mysql_query("SELECT * FROM `inlog_logs` ORDER BY `datum` LIMIT 0,10")or die(mysql_error());
	
	while ($sel = mysql_fetch_assoc($select)){
		
		
		if(!empty($sel['ip_aangemeld'])) {
			$sele = mysql_query("SELECT `ip` FROM `inlog_logs` WHERE ip='" . $sel['ip'] . "' ") or die(mysql_error());
			
			$tel = mysql_num_rows($sele);
			
			if ($tel > 1) {
				echo $sel['speler'] . " Heeft dubbel account ingelogt! <br />";
			}
		}
	}
}
 
 
 //dubbel aanmeld
  if(isset($_POST['aanmeld'])){
	
	$select = mysql_query("SELECT * FROM `gebruikers` ORDER BY `user_id` LIMIT 0,10")or die(mysql_error());
	
	while ($sel = mysql_fetch_assoc($select)){
		
		if(!empty($sel['ip_aangemeld'])) {
			$sele = mysql_query("SELECT `ip_aangemeld` FROM `gebruikers` WHERE ip_aangemeld='" . $sel['ip_aangemeld'] . "' ") or die(mysql_error());
			
			$tel = mysql_num_rows($sele);
			
			if($tel > 1){
			echo $sel['username'] ." Heeft een dubbelaccount ! <br />";
			}
		}
	}
  
  }

 }

?>
</table>

