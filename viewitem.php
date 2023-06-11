<?php
mysql_query("set character_set_client='utf8'");
mysql_query("set character_set_results='utf8'");
mysql_query("set collation_connection='utf8'");
$itemid = htmlspecialchars($_GET['id']);
$sql = mysql_query("SELECT * FROM `markt` WHERE `id`='" . $itemid . "' ");
$item = mysql_fetch_assoc($sql);
$image = $item['naam'];
if ($item['soort'] == "tm") $image = "Attack_Normal";

if ($item['soort'] == "tm") {
    $howmuch = mysql_num_rows(mysql_query("SELECT `" . $item['naam'] . "` FROM `gebruikers_tmhm` WHERE `" . $item['naam'] . "`>'0' "));
} else {
    $howmuch = mysql_num_rows(mysql_query("SELECT `" . $item['naam'] . "` FROM `gebruikers_item` WHERE `" . $item['naam'] . "`>'0' "));
}

if ($item['soort'] == "megastone") {
    $megaImage = str_replace(" ", "_", $item['naam']);
    $image = "images/megastones/" . strtolower($megaImage) . ".png";
}
?>
<center>
    <h2>Items: <?php echo $item['naam']; ?></h2>
</center>
<div class="info">
    <div class="item">
        <? if ($item['soort'] == "megastone") {
            ?>
            <img src="<?php echo $image; ?>">
            <?
        } else {
            ?>
            <img src="images/items/<?php echo $image; ?>.png">
            <?
        } ?>
    </div>
    <div class="itemname">
        <b>Naam:</b> <?php echo $item['naam']; ?>
    </div>
    <div class="description">
        <b>Beschrijving:</b><br/>
        <?php echo $item['omschrijving_en']; ?>
    </div>
</div>
<div class="sep" style="margin-top:105px;"></div>
<center>
    <b><?php echo $howmuch; ?></b> Spelers in bezit <b><?php echo $item['naam']; ?></b>.
</center>