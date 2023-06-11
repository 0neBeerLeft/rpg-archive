<?php
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

$page = 'store';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

$gebruikerUID = $_GET['player'];

//get userid
$getUserID = $db->prepare("SELECT user_id,username,store FROM gebruikers WHERE username = :username");
$getUserID->bindValue(':username', $_GET['player'], PDO::PARAM_STR);
$getUserID->execute();
$gebruikerUID = $getUserID->fetch();

$selling = true;

if(isset($_POST['kopen'])){
    if($_POST['uid']){
        //get all itemdata
        $getProduct = $db->prepare("SELECT * FROM gebruikers_store WHERE id = :uid");
        $getProduct->bindValue(':uid', $_POST['uid'], PDO::PARAM_INT);
        $getProduct->execute();
        $getProduct = $getProduct->fetch();
        if($getProduct){
            $checkTransaction = false;

            if($getProduct['currency'] == 'silver' AND $gebruiker['silver'] >= $getProduct['prijs']){
                $checkTransaction = true;
                $currencyType = 'silver';
            }
            if($getProduct['currency'] == 'gold' AND $gebruiker['gold'] >= $getProduct['prijs']){
                $checkTransaction = true;
                $currencyType = 'gold';
            }

            if($checkTransaction){

                if($getProduct['soort'] == 'tm' or $getProduct['soort'] == 'hm') {

                    $targetTable = 'gebruikers_tmhm';

                }else {

                    $targetTable = 'gebruikers_item';

                }

                if($gebruikerUID['user_id'] == $_SESSION['id']) {

                    echo showAlert("red", "Je kan je eigen producten niet kopen."); refresh(3,"?page=store&player=".$gebruikerUID['username']);

                } else {

                    $event = '<img src="images/icons/green.png" width="16" height="16" class="imglower"> ' . $_SESSION[naam] . ' heeft een ' . $getProduct[item] . ' van je gekocht.';

                    $q = "UPDATE `" . $targetTable . "` SET `" . $getProduct['item'] . "`=`" . $getProduct['item'] . "`+'1' WHERE `user_id`=:user_id;
                       UPDATE `gebruikers` SET `" . $currencyType . "`=`" . $currencyType . "`-:prijs WHERE `user_id`=:user_id;
                       UPDATE `gebruikers` SET `" . $currencyType . "`=`" . $currencyType . "`+:prijs WHERE `user_id`=:seller;
                       DELETE FROM gebruikers_store WHERE id=:uid;
                       INSERT INTO `gebeurtenis` (`datum` ,`ontvanger_id` ,`bericht`)
                        VALUES (:purchasetime, :seller, :event)";
                    $st = $db->prepare($q);
                    $st->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_INT);
                    $st->bindValue(':uid', $getProduct['id'], PDO::PARAM_INT);
                    $st->bindValue(':prijs', $getProduct['prijs'], PDO::PARAM_INT);
                    $st->bindValue(':seller', $getProduct['user_id'], PDO::PARAM_INT);
                    $st->bindValue(':event', $event, PDO::PARAM_STR);
                    $st->bindValue(':purchasetime', date('Y-m-d H:i:s'), PDO::PARAM_STR);

                    $st->execute();

                    echo showAlert("green", "Je hebt een " . $getProduct['item'] . " gekocht van " . $_GET['player'] . "."); refresh(3,"?page=store&player=".$gebruikerUID['username']);

                }

            } else {

                echo showAlert('red', 'Je hebt niet genoeg '.$getProduct['currency'].' op zak.'); refresh(3,"?page=store&player=".$gebruikerUID['username']);

            }
        }
    }
    $selling = false;
}
if (isset($_POST['intrekken'])) {
    if ($_POST['uid']) {
        //get all itemdata
        $getProduct = $db->prepare("SELECT * FROM gebruikers_store WHERE id = :uid");
        $getProduct->bindValue(':uid', $_POST['uid'], PDO::PARAM_INT);
        $getProduct->execute();
        $getProduct = $getProduct->fetch();
        if ($getProduct['user_id'] == $_SESSION['id']) {

            if ($getProduct['soort'] == 'tm' or $getProduct['soort'] == 'hm') {

                $targetTable = 'gebruikers_tmhm';

            } else {

                $targetTable = 'gebruikers_item';

            }

            $q = "UPDATE `" . $targetTable . "` SET `" . $getProduct['item'] . "`=`" . $getProduct['item'] . "`+'1' WHERE `user_id`=:user_id;
               DELETE FROM gebruikers_store WHERE id=:uid";
            $st = $db->prepare($q);
            $st->bindValue(':uid', $getProduct['id'], PDO::PARAM_INT);
            $st->bindValue(':user_id', $getProduct['user_id'], PDO::PARAM_INT);

            $st->execute();

            echo showAlert("green", "Je hebt een " . $getProduct['item'] . " uit je winkel gehaald."); refresh(3,"?page=store&player=".$gebruikerUID['username']);
        }
    }
    $selling = false;
}

//get all itemdata
$getStore = $db->prepare("SELECT * FROM gebruikers_store WHERE user_id = :user_id");
$getStore->bindValue(':user_id', $gebruikerUID['user_id'], PDO::PARAM_INT);
$getStore->execute();
$getStore = $getStore->fetchAll();

//get all itemdata
$getMarket = $db->prepare("SELECT `id`, `soort`, `naam`, `silver`, `gold`, `omschrijving_nl` FROM markt ORDER BY soort ASC, id ASC");
$getMarket->execute();
$getMarket = $getMarket->fetchAll();

$megastone = 1;
$balls = 1;
$potions = 1;
$items = 1;
$specials = 1;
$stones = 1;
$hm = 1;
$tm = 1;

if ($gebruikerUID['store']) {
    echo $gebruikerUID['store'];
} else {
    ?>
    <center>
        Welkom in de store van <?= $_GET['player'] ?>
        <br/>
        <br/>
    </center>
    <?
}

if(!empty($getStore)){
foreach ($getStore as $storeItem) {
if ($storeItem['soort'] == 'megastone') {
if ($megastone == 1){
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="item-box">
    <tr>
        <td colspan="5" class="item-title"
            style="text-align:center;text-shadow:1px 1px #000; color:#424242; font-size:18px;" align="center"
            valign="top"><h3>Megastones</h3></td>
    </tr>
    <tr>
        <td class="top_first_td">&nbsp;</td>
        <td class="top_td"><?= $txt['name'] ?></td>
        <td class="top_td"><?= $txt['currency'] ?></td>
        <td class="top_td"><?= $txt['sellprice'] ?></td>
    </tr>
    <?
    $megastone++;
    }
    $megaImage = str_replace(" ", "_", $storeItem['item']);
    ?>
    <form method="post" name="1" action="?page=store&player=<?=$_GET['player']?>">
        <tr style="border-top: thin solid #93c7a6;">
            <td class="normal_first_td"><img src="images/megastones/<?= strtolower($megaImage) ?>.png"
                                             alt="<?= $storeItem['item'] ?>"
                                             style="max-width:24px;max-height:24px;"/>
            </td>
            <td class="normal_td"><a href="?page=viewitem&id=' . $result['id'] . '"
                                     style="text-decoration:underline;"><?= $storeItem['item'] ?></a></td>
            <td class="normal_td"><img src="images/icons/<?= $storeItem['currency'] ?>.png"
                                                    alt="<?= $storeItem['currency'] ?>"/></td>
            <td class="normal_td"><?= $storeItem['prijs'] ?></td>
            <input type="hidden" name="uid" value="<?= $storeItem['id'] ?>"/>
        </tr>
        <tr>
            <td colspan="5">
                <button type="submit" name="kopen"
                        class="button_mini pull-right"><?= $txt['button_buy'] ?></button>
                <?if($gebruikerUID['user_id'] == $_SESSION['id']){?>
                    <button type="submit" name="intrekken"
                            class="button_mini pull-right" style="margin-right: 5px;"><?= $txt['button_cancel'] ?></button>
                <?}?>
            </td>
        </tr>
    </form>
    <?
    }
    }
foreach ($getStore as $storeItem) {
if ($storeItem['soort'] == 'balls') {
if ($balls == 1){
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="item-box">
    <tr>
        <td colspan="5" class="item-title"
            style="text-align:center;text-shadow:1px 1px #000; color:#424242; font-size:18px;" align="center"
            valign="top"><h3>Balls</h3></td>
    </tr>
    <tr>
        <td class="top_first_td">&nbsp;</td>
        <td class="top_td"><?= $txt['name'] ?></td>
        <td class="top_td"><?= $txt['currency'] ?></td>
        <td class="top_td"><?= $txt['sellprice'] ?></td>
    </tr>
    <?
    $balls++;
    }
    ?>
    <form method="post" name="1" action="?page=store&player=<?=$_GET['player']?>">
        <tr style="border-top: thin solid #93c7a6;">
            <td class="normal_first_td"><img src="images/items/<?= $storeItem['item'] ?>.png"
                                             alt="<?= $storeItem['item'] ?>"
                                             style="max-width:24px;max-height:24px;"/>
            </td>
            <td class="normal_td"><a href="?page=viewitem&id=' . $result['id'] . '"
                                     style="text-decoration:underline;"><?= $storeItem['item'] ?></a></td>
            <td class="normal_td"><img src="images/icons/<?= $storeItem['currency'] ?>.png"
                                       alt="<?= $storeItem['currency'] ?>"/></td>
            <td class="normal_td"><?= $storeItem['prijs'] ?></td>
            <input type="hidden" name="uid" value="<?= $storeItem['id'] ?>"/>
        </tr>
        <tr>
            <td colspan="5">
                <button type="submit" name="kopen"
                        class="button_mini pull-right"><?= $txt['button_buy'] ?></button>
                <?if($gebruikerUID['user_id'] == $_SESSION['id']){?>
                <button type="submit" name="intrekken"
                        class="button_mini pull-right" style="margin-right: 5px;"><?= $txt['button_cancel'] ?></button>
                <?}?>
            </td>
        </tr>
    </form>
    <?
    }
    }

foreach ($getStore as $storeItem) {
if ($storeItem['soort'] == 'potions') {
if ($potions == 1){
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="item-box">
    <tr>
        <td colspan="5" class="item-title"
            style="text-align:center;text-shadow:1px 1px #000; color:#424242; font-size:18px;" align="center"
            valign="top"><h3>Potions</h3></td>
    </tr>
    <tr>
        <td class="top_first_td">&nbsp;</td>
        <td class="top_td"><?= $txt['name'] ?></td>
        <td class="top_td"><?= $txt['currency'] ?></td>
        <td class="top_td"><?= $txt['sellprice'] ?></td>
    </tr>
    <?
    $potions++;
    }
    ?>
     <form method="post" name="1" action="?page=store&player=<?=$_GET['player']?>">
        <tr style="border-top: thin solid #93c7a6;">
            <td class="normal_first_td"><img src="images/items/<?= $storeItem['item'] ?>.png"
                                             alt="<?= $storeItem['item'] ?>"
                                             style="max-width:24px;max-height:24px;"/>
            </td>
            <td class="normal_td"><a href="?page=viewitem&id=' . $result['id'] . '"
                                     style="text-decoration:underline;"><?= $storeItem['item'] ?></a></td>
            <td class="normal_td"><img src="images/icons/<?= $storeItem['currency'] ?>.png"
                                       alt="<?= $storeItem['currency'] ?>"/></td>
            <td class="normal_td"><?= $storeItem['prijs'] ?></td>
            <input type="hidden" name="uid" value="<?= $storeItem['id'] ?>"/>
        </tr>
        <tr>
            <td colspan="5">
                <button type="submit" name="kopen"
                        class="button_mini pull-right"><?= $txt['button_buy'] ?></button>
                <?if($gebruikerUID['user_id'] == $_SESSION['id']){?>
                    <button type="submit" name="intrekken"
                            class="button_mini pull-right" style="margin-right: 5px;"><?= $txt['button_cancel'] ?></button>
                <?}?>
            </td>
        </tr>
    </form>
    <?
    }
    }

foreach ($getStore as $storeItem) {
if ($storeItem['soort'] == 'items') {
if ($items == 1){
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="item-box">
    <tr>
        <td colspan="5" class="item-title"
            style="text-align:center;text-shadow:1px 1px #000; color:#424242; font-size:18px;" align="center"
            valign="top"><h3>Items</h3></td>
    </tr>
    <tr>
        <td class="top_first_td">&nbsp;</td>
        <td class="top_td"><?= $txt['name'] ?></td>
        <td class="top_td"><?= $txt['currency'] ?></td>
        <td class="top_td"><?= $txt['sellprice'] ?></td>
    </tr>
    <?
    $items++;
    }
    ?>
    <form method="post" name="1" action="?page=store&player=<?=$_GET['player']?>">
        <tr style="border-top: thin solid #93c7a6;">
            <td class="normal_first_td"><img src="images/items/<?= $storeItem['item'] ?>.png"
                                             alt="<?= $storeItem['item'] ?>"
                                             style="max-width:24px;max-height:24px;"/>
            </td>
            <td class="normal_td"><a href="?page=viewitem&id=' . $result['id'] . '"
                                     style="text-decoration:underline;"><?= $storeItem['item'] ?></a></td>
            <td class="normal_td"><img src="images/icons/<?= $storeItem['currency'] ?>.png"
                                       alt="<?= $storeItem['currency'] ?>"/></td>
            <td class="normal_td"><?= $storeItem['prijs'] ?></td>
            <input type="hidden" name="uid" value="<?= $storeItem['id'] ?>"/>
        </tr>
        <tr>
            <td colspan="5">
                <button type="submit" name="kopen"
                        class="button_mini pull-right"><?= $txt['button_buy'] ?></button>
                <?if($gebruikerUID['user_id'] == $_SESSION['id']){?>
                    <button type="submit" name="intrekken"
                            class="button_mini pull-right" style="margin-right: 5px;"><?= $txt['button_cancel'] ?></button>
                <?}?>
            </td>
        </tr>
    </form>
    <?
    }
    }

foreach ($getStore as $storeItem) {
if (($storeItem['soort'] ==  "special items") || ($storeItem['soort'] == "mega") || ($storeItem['soort'] == "krone") || ($storeItem['soort'] == "newyear") || ($storeItem['soort'] == "narceus") || ($storeItem['soort'] == "rare candy")) {
if ($specials == 1){
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="item-box">
    <tr>
        <td colspan="5" class="item-title"
            style="text-align:center;text-shadow:1px 1px #000; color:#424242; font-size:18px;" align="center"
            valign="top"><h3>Special items</h3></td>
    </tr>
    <tr>
        <td class="top_first_td">&nbsp;</td>
        <td class="top_td"><?= $txt['name'] ?></td>
        <td class="top_td"><?= $txt['currency'] ?></td>
        <td class="top_td"><?= $txt['sellprice'] ?></td>
    </tr>
    <?
    $specials++;
    }
    ?>
    <form method="post" name="1" action="?page=store&player=<?=$_GET['player']?>">
        <tr style="border-top: thin solid #93c7a6;">
            <td class="normal_first_td"><img src="images/items/<?= $storeItem['item'] ?>.png"
                                             alt="<?= $storeItem['item'] ?>"
                                             style="max-width:24px;max-height:24px;"/>
            </td>
            <td class="normal_td"><a href="?page=viewitem&id=' . $result['id'] . '"
                                     style="text-decoration:underline;"><?= $storeItem['item'] ?></a></td>
            <td class="normal_td"><img src="images/icons/<?= $storeItem['currency'] ?>.png"
                                       alt="<?= $storeItem['currency'] ?>"/></td>
            <td class="normal_td"><?= $storeItem['prijs'] ?></td>
            <input type="hidden" name="uid" value="<?= $storeItem['id'] ?>"/>
        </tr>
        <tr>
            <td colspan="5">
                <button type="submit" name="kopen"
                        class="button_mini pull-right"><?= $txt['button_buy'] ?></button>
                <?if($gebruikerUID['user_id'] == $_SESSION['id']){?>
                    <button type="submit" name="intrekken"
                            class="button_mini pull-right" style="margin-right: 5px;"><?= $txt['button_cancel'] ?></button>
                <?}?>
            </td>
        </tr>
    </form>
    <?
    }
    }

foreach ($getStore as $storeItem) {
if ($storeItem['soort'] == 'stones') {
if ($stones == 1){
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="item-box">
    <tr>
        <td colspan="5" class="item-title"
            style="text-align:center;text-shadow:1px 1px #000; color:#424242; font-size:18px;" align="center"
            valign="top"><h3>Stones</h3></td>
    </tr>
    <tr>
        <td class="top_first_td">&nbsp;</td>
        <td class="top_td"><?= $txt['name'] ?></td>
        <td class="top_td"><?= $txt['currency'] ?></td>
        <td class="top_td"><?= $txt['sellprice'] ?></td>
    </tr>
    <?
    $stones++;
    }
    ?>
    <form method="post" name="1" action="?page=store&player=<?=$_GET['player']?>">
        <tr style="border-top: thin solid #93c7a6;">
            <td class="normal_first_td"><img src="images/items/<?= $storeItem['item'] ?>.png"
                                             alt="<?= $storeItem['item'] ?>"
                                             style="max-width:24px;max-height:24px;"/>
            </td>
            <td class="normal_td"><a href="?page=viewitem&id=' . $result['id'] . '"
                                     style="text-decoration:underline;"><?= $storeItem['item'] ?></a></td>
            <td class="normal_td"><img src="images/icons/<?= $storeItem['currency'] ?>.png"
                                       alt="<?= $storeItem['currency'] ?>"/></td>
            <td class="normal_td"><?= $storeItem['prijs'] ?></td>
            <input type="hidden" name="uid" value="<?= $storeItem['id'] ?>"/>
        </tr>
        <tr>
            <td colspan="5">
                <button type="submit" name="kopen"
                        class="button_mini pull-right"><?= $txt['button_buy'] ?></button>
                <?if($gebruikerUID['user_id'] == $_SESSION['id']){?>
                    <button type="submit" name="intrekken"
                            class="button_mini pull-right" style="margin-right: 5px;"><?= $txt['button_cancel'] ?></button>
                <?}?>
            </td>
        </tr>
    </form>
    <?
    }
    }

foreach ($getStore as $storeItem) {
if ($storeItem['soort'] == 'hm') {
if ($hm == 1){
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="item-box">
    <tr>
        <td colspan="5" class="item-title"
            style="text-align:center;text-shadow:1px 1px #000; color:#424242; font-size:18px;" align="center"
            valign="top"><h3>HM</h3></td>
    </tr>
    <tr>
        <td class="top_first_td">&nbsp;</td>
        <td class="top_td"><?= $txt['name'] ?></td>
        <td class="top_td"><?= $txt['currency'] ?></td>
        <td class="top_td"><?= $txt['sellprice'] ?></td>
    </tr>
    <?
    $hm++;
    }
    ?>
    <form method="post" name="1" action="?page=store&player=<?=$_GET['player']?>">
        <tr style="border-top: thin solid #93c7a6;">
            <td class="normal_first_td"><img src="images/items/<?= $storeItem['item'] ?>.png"
                                             alt="<?= $storeItem['item'] ?>"
                                             style="max-width:24px;max-height:24px;"/>
            </td>
            <td class="normal_td"><a href="?page=viewitem&id=' . $result['id'] . '"
                                     style="text-decoration:underline;"><?= $storeItem['item'] ?></a></td>
            <td class="normal_td"><img src="images/icons/<?= $storeItem['currency'] ?>.png"
                                       alt="<?= $storeItem['currency'] ?>"/></td>
            <td class="normal_td"><?= $storeItem['prijs'] ?></td>
            <input type="hidden" name="uid" value="<?= $storeItem['id'] ?>"/>
        </tr>
        <tr>
            <td colspan="5">
                <button type="submit" name="kopen"
                        class="button_mini pull-right"><?= $txt['button_buy'] ?></button>
                <?if($gebruikerUID['user_id'] == $_SESSION['id']){?>
                    <button type="submit" name="intrekken"
                            class="button_mini pull-right" style="margin-right: 5px;"><?= $txt['button_cancel'] ?></button>
                <?}?>
            </td>
        </tr>
    </form>
    <?
    }
    }

foreach ($getStore as $storeItem) {
if ($storeItem['soort'] == 'tm') {
if ($tm == 1){
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="item-box">
    <tr>
        <td colspan="5" class="item-title"
            style="text-align:center;text-shadow:1px 1px #000; color:#424242; font-size:18px;" align="center"
            valign="top"><h3>TM</h3></td>
    </tr>
    <tr>
        <td class="top_first_td">&nbsp;</td>
        <td class="top_td"><?= $txt['name'] ?></td>
        <td class="top_td"><?= $txt['currency'] ?></td>
        <td class="top_td"><?= $txt['sellprice'] ?></td>
    </tr>
    <?
    $tm++;
    }
    ?>
    <form method="post" name="1" action="?page=store&player=<?=$_GET['player']?>">
        <tr style="border-top: thin solid #93c7a6;">
            <td class="normal_first_td"><img src="images/items/<?= $storeItem['item'] ?>.png"
                                             alt="<?= $storeItem['item'] ?>"
                                             style="max-width:24px;max-height:24px;"/>
            </td>
            <td class="normal_td"><a href="?page=viewitem&id=' . $result['id'] . '"
                                     style="text-decoration:underline;"><?= $storeItem['item'] ?></a></td>
            <td class="normal_td"><img src="images/icons/<?= $storeItem['currency'] ?>.png"
                                       alt="<?= $storeItem['currency'] ?>"/></td>
            <td class="normal_td"><?= $storeItem['prijs'] ?></td>
            <input type="hidden" name="uid" value="<?= $storeItem['id'] ?>"/>
        </tr>
        <tr>
            <td colspan="5">
                <button type="submit" name="kopen"
                        class="button_mini pull-right"><?= $txt['button_buy'] ?></button>
                <?if($gebruikerUID['user_id'] == $_SESSION['id']){?>
                    <button type="submit" name="intrekken"
                            class="button_mini pull-right"  style="margin-right: 5px;"><?= $txt['button_cancel'] ?></button>
                <?}?>
            </td>
        </tr>
    </form>
    <?
    }
    }
    } else {

        if($selling) {
            if ($_GET['player'] == $_SESSION['naam']) {
                $message = 'Je hebt geen items in de verkoop.';
            } else {
                $message = $_GET['player'] . ' heeft geen producten in de verkoop.';
            }

            echo showAlert('blue', $message);
        }

    }
?>
</table>