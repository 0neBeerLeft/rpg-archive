<?php 
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");
?>


<?php 
    
$query = mysql_query("SELECT * FROM `casino`"); 
$casino = mysql_fetch_assoc($query);
?> 
<center><h3>Kraak de kluis</h3></center>
<img src="images/safe.png" width="120" height="120" alt="" style="float:right;"> 
<p>Probeer de kluis te kraken en win de Jackpot! <br />
Elke poging voegt <img src="images/icons/silver.png" alt="Silver"/> 200 silver toe aan de jackpot.<br /><br />
Een poging kost <img src="images/icons/silver.png" alt="Silver"/> 200 silver </p> <br />
<center><h2>Jackpot: &nbsp;<em><img src="images/icons/silver.png" alt="Silver"/> <?php echo number_format($casino['kluis_4'], 0, ',', '.'); ?></em></h2></center> <br />
<table class="lijst"> 
<tr><td><strong>Code 1</td> 
<td><strong>Code 2</td> 
<td><strong>Code 3</td></tr> 
<tr><td><form method="post">  
<select name="code1" class="text_select"> 
<option value="0">0</option> 
<option value="1">1</option> 
<option value="2">2</option> 
<option value="3">3</option> 
<option value="4">4</option> 
<option value="5">5</option> 
<option value="6">6</option>  
<option value="7">7</option> 
<option value="8">8</option> 
<option value="9">9</option> 
</select> 
</td><td> 
<select name="code2" class="text_select"> 
<option value="0">0</option> 
<option value="1">1</option> 
<option value="2">2</option> 
<option value="3">3</option> 
<option value="4">4</option> 
<option value="5">5</option> 
<option value="6">6</option>  
<option value="7">7</option> 
<option value="8">8</option> 
<option value="9">9</option> 
</select> 
</td><td> 
<select name="code3" class="text_select"<div class="green">
<option value="0">0</option> 
<option value="1">1</option> 
<option value="2">2</option> 
<option value="3">3</option> 
<option value="4">4</option> 
<option value="5">5</option> 
<option value="6">6</option> 
<option value="7">7</option> 
<option value="8">8</option> 
<option value="9">9</option> 
</select> 
</td></tr><tr><td> <td>
<br />
<td><button type="submit" name="post" class="button">Kraak de kluis</button></td>
</form>
</td> 
</td> 
</tr> 
</table> 
<?php 

if ($_POST["post"]) { 
if ($gebruiker['silver'] <= 200) { echo "You do not have enough silver."; } else { 
if ($_POST["code1"] == $casino['kluis_1'] && $_POST["code2"] == $casino['kluis_2'] && $_POST["code3"] == $casino['kluis_3']) { 
echo "<div class='green'> Yes! The code was accepted! <b>You win the pot! &euro; ".$casino['kluis_4'].",-!<i>The code has now been reset, the jackpot will now start at 1000. ;)</i></div>"; 
$r1 = rand(0,9); 
$r2 = rand(0,9); 
$r3 = rand(0,9); 
mysql_query("UPDATE `gebruikers` SET `silver`=`silver`+'" . $casino['kluis_4'] . "' WHERE user_id='".$_SESSION['id']."'");  
mysql_query("UPDATE `casino` SET `kluis_1`=$r1, `kluis_2`=$r2, `kluis_3`=$r3, `kluis_4`=1000"); 
mysql_query("TRUNCATE TABLE kluis_kraken"); 
return; 
} else { 
echo "<div class='red'> Too bad, the error code was.<a href='?page=kluis'> Try again!</a></div>."; 
    
mysql_query("UPDATE `gebruikers` SET `silver`=`silver`-200 WHERE user_id='".$_SESSION['id']."'"); 
mysql_query("UPDATE `casino` SET `kluis_4`=`kluis_4`+200"); 
mysql_query("INSERT INTO 
                `kluis_kraken` 
            (`1`, 
            `2`, 
            `3`) 
                VALUES 
            ('" . $_POST['code1'] . "', 
            '" . $_POST['code2'] . "', 
            '" . $_POST['code3'] . "')"); 
return; 
} 
} 
} 

?> 