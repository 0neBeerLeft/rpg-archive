<?php
mysql_query("set character_set_client='utf8'");
mysql_query("set character_set_results='utf8'");
mysql_query("set collation_connection='utf8'");
include("includes/security.php");
$page = 'clan-shop';
$claner = mysql_fetch_assoc(mysql_query('SELECT clan FROM gebruikers WHERE user_id = "'.$_SESSION['id'].'"'));
if ($claner['clan'] == "" ) {echo'<div class="red">Du bist in keinem Clan.</div>'; } else {
$clan = mysql_fetch_assoc(mysql_query('SELECT * FROM clans WHERE clan_naam = "'.$claner['clan'].'"'));
$ifitem = mysql_num_rows(mysql_query('SELECT id FROM clan_items WHERE expire+172800 > "'.time().'" && clanname = "'.$claner['clan'].'"'));
if ($ifitem >= 1) {echo '<div class="blue">Es ist bereits ein aktives Item im Clan. Bitte warte, bis das Item abgelaufen ist.</div>'; } else {
?>
<center><h2>Clan Speicher</h2><br />
<img src="images/icons/silver.png"> Silber: <?php echo number_format($clan['bank']) ?> | <img src="images/icons/gold.png"> Gold: <?php echo number_format($clan['clan_gold']) ?>
</center>
<div class="sep"></div>
Hier kann der Clan Gründer wertvolle Item für den Clan kaufen, welche allen Mitgliedern Vorteile verschaffen.<br/>

<?php
$getitems = mysql_query('SELECT * FROM clan_shop ORDER BY id');
while ($item = mysql_fetch_assoc($getitems)) {
?>
<table class="clan_item" width="650" height="100">
<tr>
<td rowspan="5" style="padding-right:10px"><img src="img/clan/<?=$item['image']?>" width="130" height="130"></td>

<td style="padding:5px;padding-top:10px" colspan="2"><b><font size="3"><?=$item['itemname']?></font></b></td></tr>
<tr><td valign="top"><b>Name:</b></td><td valign="top"> <?=$item['itemname']?></td></tr>
<tr><td valign="top"><b>Preis:</b></td><td valign="top"> <?=number_format($item['pricesilver'])?> <img src="images/icons/silver.png" alt="Silber"/> und <?=number_format($item['pricegold'])?> <img src="images/icons/gold.png" alt="Gold"/></td></tr>
<tr><td valign="top"><b>Beschreibung:</b></td><td valign="top" width="340"> <?=$item['descr']?></td></tr>
<tr><td valign="top"><b>Rang:</b></td><td valign="top"> <?=$item['rankneed']?></td></tr>
<tr><td colspan="2" style="padding-right:25px">
<form method="post" action="?page=verify&id=<?=$item['id']?>">
<button name="verify" type="submit" class="myButton">
    Kaufen
</button>
</form></td></tr>
</table>
<style>
.clan_item {
background:#eee;
margin:5px;
border:3px solid #fff;
box-shadow:0 0 5px #ccc;
}
.clan_item:hover {
border:3px solid #eee;
background:#ccc;
}
.clan_item img {
box-shadow:0 0 5px #ccc;
}
    .myButton {
        
        -moz-box-shadow:inset 0px 1px 0px 0px #54a3f7;
        -webkit-box-shadow:inset 0px 1px 0px 0px #54a3f7;
        box-shadow:inset 0px 1px 0px 0px #54a3f7;
        
        background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #007dc1), color-stop(1, #0061a7));
        background:-moz-linear-gradient(top, #007dc1 5%, #0061a7 100%);
        background:-webkit-linear-gradient(top, #007dc1 5%, #0061a7 100%);
        background:-o-linear-gradient(top, #007dc1 5%, #0061a7 100%);
        background:-ms-linear-gradient(top, #007dc1 5%, #0061a7 100%);
        background:linear-gradient(to bottom, #007dc1 5%, #0061a7 100%);
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#007dc1', endColorstr='#0061a7',GradientType=0);
        
        background-color:#007dc1;
        
        -moz-border-radius:3px;
        -webkit-border-radius:3px;
        border-radius:3px;
        
        border:1px solid #124d77;
        
        display:inline-block;
        color:#ffffff;
        font-family:arial;
        font-size:13px;
        font-weight:normal;
        padding:6px 24px;
        text-decoration:none;
        margin:2px;
        text-shadow:0px 1px 0px #154682;
        
    }
    .myButton:hover {
        
        background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #0061a7), color-stop(1, #007dc1));
        background:-moz-linear-gradient(top, #0061a7 5%, #007dc1 100%);
        background:-webkit-linear-gradient(top, #0061a7 5%, #007dc1 100%);
        background:-o-linear-gradient(top, #0061a7 5%, #007dc1 100%);
        background:-ms-linear-gradient(top, #0061a7 5%, #007dc1 100%);
        background:linear-gradient(to bottom, #0061a7 5%, #007dc1 100%);
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#0061a7', endColorstr='#007dc1',GradientType=0);
        
        background-color:#0061a7;
    }
    .myButton:active {
        position:relative;
        top:1px;
    }
</style>
<?php } } } ?>
