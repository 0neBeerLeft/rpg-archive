<?php
mysql_query("set character_set_client='utf8'");
mysql_query("set character_set_results='utf8'");
mysql_query("set collation_connection='utf8'");
include("includes/security.php");
$page = 'clan-items';
$claner = mysql_fetch_assoc(mysql_query('SELECT clan FROM gebruikers WHERE user_id = "'.$_SESSION['id'].'"'));
if ($claner['clan'] == "" ) {echo'<div class="red">אתה לא נמצא בקבוצה.</div>'; } else {
?>
<center><h2><div class="h1text">חפצי הקבוצה</div></h2></center>
<div class="info">כאן ניתן לראות אילו חפצים הקבוצה קנתה.</div>
<div class="sep"></div>
<?php
$getitems = mysql_query('SELECT * FROM clan_items WHERE clanname = "'.$claner['clan'].'"');
if (mysql_num_rows($getitems) > 0) {
while ($items = mysql_fetch_assoc($getitems)) {
$item = mysql_fetch_assoc(mysql_query('SELECT * FROM clan_shop WHERE id = "'.$items['item_id'].'"'));
if ($items['expire']+172800 < time()) { $expired = 1; }
$expire = date('d/m/y בשעה G:i', $items['expire']+172800);
?>
<table style="background-color:<?php if (!$expired)echo'#9bff9b';else echo'#ff9b9b';?>;border:1px solid #d9d9d9" onMouseover="this.style.backgroundColor='<?php if (!$expired)echo'#6cbe6a';else echo'#be6a6a';?>'" onmouseout="this.style.backgroundColor='<?php if (!$expired)echo'#9bff9b';else echo'#ff9b9b';?>'" width="650" height="100">
<tr>
<td rowspan="5" style="padding-right:10px"><img src="img/clan/<?=$item['image']?>" width="130" height="130"></td>

<td style="padding:5px;padding-top:10px" colspan="2"><b><font size="3"><?=$item['itemname']?></font></b></td></tr>
<tr><td valign="top"><b>שם הפריט:</b></td><td valign="top"> <?=$item['itemname']?></td></tr>
<tr><td valign="top"><b>תיאור הפריט:</b></td><td valign="top" width="340"> <?=$item['descr']?></td></tr>
<tr><td valign="top"><b>תקף עד:</b></td><td valign="top" width="340"> <?=$expire?> <?php if ($expired) {echo'(פג תוקף)'; }?></td></tr>
</form></td></tr>
</table>
<?php $expired = 0; } } else { echo'<div class="red">עדיין אין לקבוצה זו חפצים.</div>'; } } ?>
