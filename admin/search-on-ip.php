<?		
//Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

//Admin controle
if($gebruiker['admin'] < 1) header('location: index.php?page=home');
?>

<center>
<form method="post">
    <table width="300" border="0">
        <tr>
          <td><strong>Ip Address:</strong></td>
          <td><input name="ip" type="text" value="<?php if($_POST['ip'] != '') echo $_GET['ip']; else echo $_POST['ip']; ?>" class="text_long" maxlength="15"></td>
          <td><input name="submit1" type="submit" value="Search" class="button_mini"></td>
        </tr>
     </table>
</form>
</center>

<?php
//Als er een ip opgegeven is
if($_GET['ip'] != ""){
  $_POST['ip'] = $_GET['ip'];
}

if(isset($_POST['ip'])){
  //Is er wel een ip opgegeven zo ja dan verder
  if($_POST['ip'] != ""){
    //Gegevens laden van het ingevoerde ip
	if($_GET['which'] == 'aangemeld'){
  		$dbres = mysql_query("SELECT `user_id`, `username`, `ip_aangemeld`, `ip_ingelogd`, `email` FROM `gebruikers` WHERE `account_code`='1' AND `ip_aangemeld`='".$_POST['ip']."' ORDER BY `username`");
	}
	else{
		$dbres = mysql_query("SELECT `user_id`, `username`, `ip_aangemeld`, `ip_ingelogd`, `email` FROM `gebruikers` WHERE `account_code`='1' AND `ip_ingelogd`='".$_POST['ip']."' ORDER BY `username`");
	}
  	//Beeldweergave
  	echo '<center><br /><table width="500">
    		<tr>
    			<td width="50">#</td>
   				<td width="120"><strong>Username:</strong></td>
    			<td width="120"><strong>Ip Logged:</strong></td>
    			<td width="120"><strong>Ip Login:</strong></td>
    			<td width="90"><strong>Ban:</strong></td>
    		</tr>';
  	
  	//Lijst opbouwen per speler gaat vanzelf
    for($j=$pagina+1; $gegevens = mysql_fetch_array($dbres); $j++)
    {
      echo '<tr>
      				<td height="30">'.$j.'.</td>
      				<td><a href="index.php?page=profile&player='.$gegevens['username'].'">'.$gegevens['username'].'</a></td>
      				<td>'.$gegevens['ip_aangemeld'].'</td>
      				<td>'.$gegevens['ip_ingelogd'].'</td>';
					if($_GET['which'] == 'aangemeld'){
  						echo '<td><a href="index.php?page=admin/ban-ip&ip=true&gebruiker='.$gegevens['username'].'"><img src="../images/icons/user_ban.png" alt="Ban" title="Ban op aangemelde ip adres."></a></td>';
					}
					else{
						echo '<td><a href="index.php?page=admin/ban-ip&ip=true&gebruiker='.$gegevens['username'].'"><img src="../images/icons/user_ban.png" alt="Ban" title="Ban op laatst ingelogde ip adres"></a></td>';
					}
      			echo '</tr>';
    }
  }
}
?>
</table></center>