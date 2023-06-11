<?php 
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

$page = 'safarizone';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');
	$page = "SafariZone";
	
?>
					<li class="header"> SafariZone </li>
					<li class="main">
						<style>
							.map_bg{width:126px; height:96px; background:url(images/battle/base.png);  margin: auto; -webkit-text-size-adjust:none}
						</style>
					<?php
						if($_SESSION['safari'] >= 1) {
							if($_SESSION['catch'] >= 2) {
								echo "De pagina kan niet vernieuwd worden!";
								die();
							}
							$s = mysql_query("SELECT * FROM safarizone WHERE username = '".$_SESSION['naam']."'")or die(mysql_error());

							$count = mysql_fetch_array($s);
							$mapid = 9;
							$fetch = mysql_num_rows(mysql_query("SELECT * FROM maps WHERE id = $mapid"));
							$cap = mt_rand(50,80);
							if($_SESSION['safari_cap'] >= $cap) {
								if(isset($_POST['nub'])) {
									include("captcha/securimage.php");
									$img = new Securimage();
									$valid = $img->check($_POST['code']);
									if($valid == true) {
								  		echo "<p>You entered the correct code. Please continiue searching!</p>";
										echo ('<form method="POST" action="maps.php?id='.$mapid.'">');
										echo ('<input type="image" src="images/maps/'.$mapid.'.png">');
									  	unset($_SESSION['safari_cap']);
									  } else {
								  		echo "<p>Sorry, the code you entered was invalid.</p>";
								  		echo '
											<form method="POST">
											<img src="captcha/securimage_show.php?sid=<?php echo md5(uniqid(time())); ?>">	
											<p><input type="text" name="code" /></p>
											<input name="nub" type="submit" value="Submit"  class="button">
											</form>
										';
									}
								} else {
									echo '<p> Please enter the code below to search again! </p>';
									echo '
										<form method="POST">
										<img src="captcha/securimage_show.php?sid=<?php echo md5(uniqid(time())); ?>">
										<p><input type="text" name="code" /></p>
										<input name="nub" type="submit" value="Submit" class="button">
										</form>
									';
								}
							} else {
								if (isset($_POST['catch'])) {
									$catch_rate = mt_rand(1,3);
										$sub = mysql_query("UPDATED safarizone SET balls = balls - 1 WHERE username = '".$_SESSION['user']."'");
										$_SESSION['safari'] -= 1;
										if($catch_rate == 1) {
											$poke = $_SESSION['poke'];
											$get = mysql_fetch_array(mysql_query("SELECT * FROM maps WHERE id = $mapid"));	
											$dexID = mysql_fetch_array(mysql_query("SELECT * FROM pokedex WHERE name = '$poke'"));			
											$pokedex = $dexID['id'];
											$level = mt_rand(3,10);
											$Type = $_SESSION['type'];
											if($_SESSION['type'] != 1) {
												$t1 = 'Shiny';
											} else {
												$t1 = 'Normal';
											}
											$exp = pow($level,3);
											$_SESSION['catch'] += 1;
											include 'include/stats.php';
											$points = floor( ($hp*4) + ($attack + $defence + $speed + $SpAttack + $SpDefence) + ($hp/8)+1);
											echo ('<div class="map_bg"><img src="images/Pokemon/'.$t1.'/'.$poke.'.png"></div>');		
											echo "You have caught a ";
											if($t1 == Normal){echo '';} else {echo $t1;}
											echo "".$_SESSION['poke']." and released it back into the wild.";
											echo "<p>".$_SESSION['poke']." (Level: ".$level.")</p>";
											echo "<p>You got ".number_format($points)." points</p>";
											$pon = mysql_query("UPDATE safarizone SET points = points + $points WHERE username = '".$_SESSION['user']."'");
											echo "You now have ".$_SESSION['safari']." safariballs";
											echo ('<form method="POST" action="safari.php">');
											echo ('<input type="image" src="images/maps/'.$mapid.'.png" name="search">');
											echo ('</form>');
										} else {
											echo "The pokemon fled!";
											echo "<p>You now have ".$_SESSION['safari']." safariballs</p>";
											echo ('<form method="POST" action="safari.php">');
											echo ('<input type="image" src="images/maps/'.$mapid.'.png" name="search">');
											echo ('</form>');
										}
								} else {
									if($fetch == 1) {
										$get = mysql_fetch_array(mysql_query("SELECT * FROM maps WHERE id = $mapid"));
										if(isset($_POST['search_x'])) {
											$_SESSION['safari_cap'] += 1;
											unset($_SESSION['catch']);
											$normal = mt_rand(1,5);
											if ($normal == 1) {
												$num_id = mt_rand(1,10);
												if($num_id == 1) {
													$poke_id = mt_rand(7,8);
												} else {
													$poke_id = mt_rand(1,6);
												}
												$poke1 = $get['poke'.$poke_id.''];
												$name = mysql_fetch_array(mysql_query("SELECT * FROM pokedex WHERE id = $poke1"));
												$type = mt_rand(1,100);
												if($type == 1) {
													$_SESSION['type'] = 2;
												} else {
													$_SESSION['type'] = 1;
												}
												$_SESSION['poke1'] = $poke1;
												$_SESSION['poke'] = $name['name'];
												if($type == 1) {
													$type = Shiny;
												} else {
													$type = Normal;
												}											
												if($type == Normal) {$t = '';} else {$t = $type;}
												echo "A Wild ".$t."".$_SESSION['poke']." Appeared!!";			
												echo ('<div class="map_bg"><img src="images/Pokemon/'.$type.'/'.$_SESSION['poke'].'.png"></div>');
												echo ('<form action="safari.php" method="POST">');

												echo ('<input type="submit" class="button" name="catch" value="Throw Safariball">');
												echo ('</form>');
												echo ('<form method="POST" action="safari.php">');
												echo ('<input type="image" src="images/maps/'.$mapid.'.png" name="search">');
												echo ('</form>');
											} elseif ($normal >= 2) {
												echo ("No Pokemon Appeared!");
												echo ('<form method="POST" action="safari.php">');
												echo ('<input type="image" src="images/maps/'.$mapid.'.png" name="search">');
												echo ('</form>');
											}
										} else {
											echo ("Where are these pokemon?!");
											echo ('<form method="POST" action="safari.php">');
											echo ('<input type="image" src="images/maps/'.$mapid.'.png" name="search">');
											echo ('</form>');
										}
									}
								}
							}
						} else {
							echo "You have no access here!";
						}
					?>
		</li>
<?php 
include 'include/footer.php'; 
?>
