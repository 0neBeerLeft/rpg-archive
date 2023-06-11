<?
//Security laden
include('includes/security.php');

#Load language
$page = 'clan-invite';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

$getname = $_GET['player'];

#gebruiker
$clanquery2 = mysql_query("SELECT clan FROM gebruikers WHERE username='".$_SESSION['naam']."'");
$clan = mysql_fetch_array($clanquery2);
#clan laden
$clanquery = mysql_query("SELECT * FROM clans WHERE clan_naam='".$clan['clan']."'");
$profiel = mysql_fetch_array($clanquery);
if(!empty($profiel)){
    $_SESSION['clan'] = $gebruiker['clan'];
?>
<script type="text/javascript">
    function insertSmiley(smiley)
    {
        var currentText = document.getElementById("shoutboxcontent");
        console.log(currentText);
        var smileyWithPadding = " " + smiley + " ";
        currentText.value += smileyWithPadding;
    }
</script>
<center><h2>Clanshout</h2><br>Welkom bij de clan shoutbox van <?echo$gebruiker['clan'];?>, alleen jij en jouw clanleden zien deze shout.</center>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="<? echo $layoutRoot; ?>js/clan-shoutbox.js"></script>
    
<ul id="messages" class="wordwrap">
    <li>Bezig met berichten ophalen…</li>
</ul>

<form action="/shoutbox/clan-sendmessage.php" method="post" id="shoutbox">
    <input id="shoutboxcontent" name="content" class="text_long" style="float:none; width:100%;" maxlength="200" type="text">
    <?
    foreach (insertableEmoticons() as $emoticon) {
        echo $emoticon." ";
    }
    ?>
    <br/><br/>
    <input value="Verstuur bericht" class="button_mini" style="margin-right:8px;" type="submit">
</form>
<?
} else {
    echo "<center>Je hebt geen clan, maak <a href='?page=clan-make'>hier</a> een clan aan.</center>";
}
?>