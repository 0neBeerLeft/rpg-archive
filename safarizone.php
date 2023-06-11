<?php 
	#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

$page = 'safarizone';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');
	$nub = mysql_num_rows(mysql_query("SELECT * FROM safarizone WHERE username = '".$_SESSION['user']."'"));
?>
     					<table>

						<tr><td>
						<p>Welkom in de Safarizone <?php echo $_SESSION['naam']; ?></p>
						<p>Hier kan je je aanmelden voor een Safari waar je gratis Pokemon kan vangen.</p>
						<p>Je krijgt 1000 Safariballs om een week lang Pokemon mee te vangen.</p>
						<p>Elke Pokemon die je vangt is een aantal punten waard, aan het einde van de week wordt er gekeken wie de meeste punten heeft.</p>
						<p>De winnaar ontvangt een willekeurige Legend!</p>
						<p><small><b>PS:</b>Gevangen pokemon worden weer vrij gelaten nadat je punten zijn geteld.</small></p><br/><br/>
						<?php
							if(isset($_POST['Sign_up'])) {
								if($nub != 1) {
									$insert = mysql_query("INSERT INTO safarizone (username, points, balls) VALUES ('".$_SESSION['naam']."', '0', '1000')")or die(mysql_error());
									echo "Je hebt je succesvol aangemeld, je wordt automatisch doorgestuurd.";
									echo '<meta http-equiv="Refresh" content="3; url=?page=safari">';
								} else {
									echo "Je hebt je al aangemeld!";
								}
							}
						if($nub == 0) {
						?>
						<form action="?page=safarizone" method="post">
						<input type="submit" class="button" name="Sign_up" value="Aanmelden voor een safari!">
						</form>
						<?php
						} else {
						?>
						<?php
						if(isset($_POST['enter'])) {
							$balls = mysql_fetch_array(mysql_query("SELECT * FROM safarizone WHERE username = '".$_SESSION['user']."'"));
							$_SESSION['safari'] = $balls['balls'];
							echo $balls['balls'];
							echo "<p>Je wordt nu doorgestuurd.</p>";
							echo '<meta http-equiv="Refresh" content="3; url=?page=safari">';
						}
						?>
							
						Klik op de knop om op safari te gaan!
						<form action="?page=safarizone" method="post">
						<input type="submit" class="button" name="enter" value="Ga op Safari!">
						</form>
						<?php
						}
							echo "<table>";
							echo "<tr>";
							echo "<th colspan=3>Safarizone Ranking</th>";
							echo "</tr>";
							echo "<tr>";
							echo "<th>Rank</th>";
							echo "<th>Gebruikersnaam</th>";
							echo "<th>Punten</th>";
							echo "</tr>";
							$rank = 0;
							$zone = mysql_query("SELECT * FROM safarizone ORDER by points DESC LIMIT 10")or die(mysql_error());
							while($safari = mysql_fetch_array($zone)) {
								$rank++;
								$get_num = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE username = '".$safari['username']."'"));
								echo "<tr>";
								echo "<td>";
								echo $rank;
								echo "</td>";
								echo "<td>";
								echo '<a href="?page=profile?id='.$get_num['id'].'">'.$safari['username'].'</a>';
								echo "</td>";
								echo "<td>";
								echo number_format($safari['points']);
								echo "</td>";
								echo "</tr>";
							}
							echo "</table>";
						?>


 </td></tr></table>