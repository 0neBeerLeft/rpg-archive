<?
//Security laden
include('includes/security.php');

if ($_GET['id'] > 0){

if ($gebruiker['silver'] < 5){
echo "<div class='red'>Je hebt niet genoeg silver ...</div>";
}else{
$gebruiker['silver'] = $gebruiker['silver'] - 5;

$slot1 = rand(1,5);$slot2 = rand(1,5);$slot3 = rand(1,5);

if($slot1==1){$img1="Meowth";}
if($slot1==2){$img1="Ekans";}
if($slot1==3){$img1="Zubat";}
if($slot1==4){$img1="Bulbasaur";}
if($slot1==5){$img1="Charmander";}

if($slot2==1){$img2="Meowth";}
if($slot2==2){$img2="Ekans";}
if($slot2==3){$img2="Zubat";}
if($slot2==4){$img2="Bulbasaur";}
if($slot2==5){$img2="Charmander";}

if($slot3==1){$img3="Meowth";}
if($slot3==2){$img3="Ekans";}
if($slot3==3){$img3="Zubat";}
if($slot3==4){$img3="Bulbasaur";}
if($slot3==5){$img3="Charmander";}

?>
<script type='text/javascript'>
function img(){
var x = Math.floor(Math.random()*4);

if(x == 0){var img="Ekans";}
if(x == 1){var img="Meowth";}
if(x == 2){var img="Zubat";}
if(x == 3){var img="Bulbasaur";}
if(x == 4){var img="Charmander";}
var image = "images/slots/"+img+".png";
return image;
}

function slots(){
document.getElementById('loading').style.display = "";
document.getElementById('pull').style.display = "none";
slotss();
var number = Math.floor(Math.random()*1000000);
window.location = "?page=slots&pull=lever&id="+number;
}

function slotss(){
a = img();
b = img();
c = img();

document.getElementById('one').src= a;
document.getElementById('two').src= b;
document.getElementById('three').src= c;

copyright=setTimeout("slotss()",45)

return true;
}
</script>
<center>
<h2>Pokémon - Slots</h2><hr>

<?
if($slot1 == $slot2 && $slot2 == $slot3) {
    echo "<br/><br/><div class='green'>Gefeliciteerd je hebt 25 <img src='images/icons/silver.png' /> silver gewonnen!</div>";
    $gebruiker['silver'] = $gebruiker['silver'] + 25;
}elseif($slot1 == $slot2 OR $slot1 == $slot3 OR $slot2 == $slot3){
        echo "<br/><br/><div class='green'>Gefeliciteerd je hebt 10 <img src='images/icons/silver.png' /> silver gewonnen!</div>";
        $gebruiker['silver'] = $gebruiker['silver'] + 10;
    }else {
echo "<br/><br/><div class='red'>Helaas je hebt niets gewonnen. Wie weet heb je de volgende keer meer geluk.</div>";
}

echo "<td><img src='images/slots/".$img1.".png' id='one'><img src='images/slots/".$img2.".png' id='two'><img src='images/slots/".$img3.".png' id='three'></td>";
mysql_query("UPDATE `gebruikers` SET `silver` = '".$gebruiker['silver']."' WHERE `user_id`='".$_SESSION['id']."'");
}
}else{ //else of if GET ID
?>
<script type='text/javascript'>
function slots(){
var number = Math.floor(Math.random()*1000000);
window.location = "?page=slots&pull=lever&id="+number;
}
</script>
<center><h2>Pokémon - Slots</h2><hr>
<div><br>
Elke spin kost 5 silver. <br>Je wint 10 silver voor twee dezelfde en 25 voor drie!<br><br>
</div>
<?}?>


<div>
<table>

<tr id="pull">
<td>
<b>Silver:</b> <? echo number_format($gebruiker['silver']); ?> <img src="images/icons/silver.png" /><br><br>

<button type='submit' name='submit' onclick="slots();" class='button'>Spin!</button>
</td>
</tr>
<tr style="display: none" id="loading">
<th colspan="2">
<img src="images/loading.gif">
</th>
</tr>

</table>
</div></center>


