<?php
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

$page = 'items';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

#Something is sold

#query
$itemdataSQL = $db->prepare("SELECT gebruikers_item.*, gebruikers_tmhm.*
                         FROM gebruikers_item
                         INNER JOIN gebruikers_tmhm
                         ON gebruikers_item.user_id = gebruikers_tmhm.user_id
                         WHERE gebruikers_item.user_id = :uid");
$itemdataSQL->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
$itemdataSQL->execute();
$itemdata = $itemdataSQL->fetch(PDO::FETCH_ASSOC);


if (isset($_POST['verkoop'])) {
    $selectSQL = $db->prepare("SELECT `naam`, `soort`, `silver`, `gold` FROM `markt` WHERE `naam`=:name");
    $selectSQL->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
    $selectSQL->execute();
    $select = $selectSQL->fetch(PDO::FETCH_ASSOC);

    if ($select['soort'] == "items") $_POST['amount'] = 1;
    if (empty($_POST['amount']))
        $error = '<div class="red">' . $txt['alert_no_amount'] . '</div>';
    elseif ($select['naam'] == "Alte Krone")
        $error = '<div class="red">Dit item kan niet worden verkocht.</div>';
    elseif ($_POST['amount'] == 0)
        $error = '<div class="red">' . $txt['alert_no_amount'] . '</div>';
    elseif (!is_numeric($_POST['amount']))
        $error = '<div class="red">' . $txt['alert_no_amount'] . '</div>';
    elseif ($_POST['amount'] > $itemdata[$select['naam']])
        $error = '<div class="red">' . $txt['alert_too_much_items_selected'] . '</div>';

    else {
        if (($select['naam'] == 'Meisterball') OR ($select['naam'] == 'Rare candy') OR ($select['naam'] == 'Heat') OR ($select['naam'] == 'Fan') OR ($select['naam'] == 'Mow') OR ($select['naam'] == 'Wash') OR ($select['naam'] == 'Frost') OR ($select['naam'] == 'D-Meteorit') OR ($select['naam'] == 'S-Meteorit') OR ($select['naam'] == 'A-Meteorit') OR ($select['naam'] == 'Megastein')) {
            $bedrag_gold = $_POST['amount'] * ($select['gold'] / 2);
            $price = number_format($bedrag_gold, 2, ',', '');
            $show = '<img src="images/icons/gold.png" title="Gold"> ' . $bedrag_gold;
        } else {
            $bedrag = $_POST['amount'] * ($select['silver'] / 2);
            $price = number_format($bedrag, 2, ',', '');
            $show = '<img src="images/icons/silver.png" title="Silver"> ' . $price;
        }

        $amount = -1;
        if ($select['soort'] == "tm") {
            $selectSQL = $db->prepare("SELECT * FROM `gebruikers_tmhm` WHERE user_id=:uid");
            $selectSQL->bindParam(':uid', $_SESSION['id'], PDO::PARAM_INT);
            $selectSQL->execute();
            $select = $selectSQL->fetch();

            // does the item exist in the user table
            if(isset($select[$_POST['name']])){
                $amount = $select[$_POST['name']]-$_POST['amount'];
            }
            if($amount >= 0){
                $lessTM = $db->prepare("UPDATE `gebruikers_tmhm` SET {$_POST['name']}=:amount WHERE `user_id`=:uid");
                $lessTM->bindValue(':amount', $amount, PDO::PARAM_INT);
                $lessTM->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
                $lessTM->execute();
            } else {
                $error = '<div class="red">' . $txt['alert_too_much_items_selected'] . '</div>';
            }
        } else {
            $selectSQL = $db->prepare("SELECT * FROM `gebruikers_item` WHERE user_id=:uid");
            $selectSQL->bindParam(':uid', $_SESSION['id'], PDO::PARAM_INT);
            $selectSQL->execute();
            $select = $selectSQL->fetch();

            // does the item exist in the user table
            if(isset($select[$_POST['name']])){
                $amount = $select[$_POST['name']]-$_POST['amount'];
            }
            if($amount >= 0){
                $lessItem = $db->prepare("UPDATE gebruikers_item SET {$_POST['name']}=:amount WHERE user_id=:uid");
                $lessItem->bindValue(':amount', $amount, PDO::PARAM_INT);
                $lessItem->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
                $result =  $lessItem->execute();
            } else {
                $error = '<div class="red">' . $txt['alert_too_much_items_selected'] . '</div>';
            }
        }

        if(!isset($error)) {
            $updateMoney = $db->prepare("UPDATE `gebruikers` SET `silver`=`silver`+:bedrag, `gold`=`gold`+:bedrag_gold WHERE `user_id`=:uid");
            $updateMoney->bindValue(':bedrag', $bedrag);
            $updateMoney->bindValue(':bedrag_gold', $bedrag_gold);
            $updateMoney->bindValue(':uid', $_SESSION['id']);
            $updateMoney->execute();

            $error = '<div class="green">' . $txt['success_items'] . ' ' . $show . '</div>';
            #Load new data
            $itemdataSQL = $db->prepare("SELECT gebruikers_item.*, gebruikers_tmhm.* FROM gebruikers_item INNER JOIN gebruikers_tmhm ON gebruikers_item.user_id = gebruikers_tmhm.user_id WHERE gebruikers_item.user_id = :uid");
            $itemdataSQL->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
            $itemdataSQL->execute();
            $itemdata = $itemdataSQL->fetch(PDO::FETCH_ASSOC);
        }
    }
}
if (isset($_POST['winkel'])) {

    //check if item on stock
    if($itemdata[$_POST['name']]){

        $product = $_POST['name'];

        if((int)$_POST['prijs']){

            $currencyCheck = false;

            if($_POST['currency'] == 1){

                $currencyCheck = 'gold';

            } elseif($_POST['currency'] == 2){

                $currencyCheck = 'silver';

            }

            if($currencyCheck){

                $getProduct = $db->prepare("SELECT `naam`, `soort`, `silver`, `gold` FROM markt WHERE naam = :item");
                $getProduct->bindValue(':item', $_POST['name'], PDO::PARAM_STR);
                $getProduct->execute();
                $getProduct = $getProduct->fetch();

                if($getProduct['soort'] == 'tm' or $getProduct['soort'] == 'hm') {

                    $targetTable = 'gebruikers_tmhm';

                }else {

                    $targetTable = 'gebruikers_item';

                }

                $st = $db->prepare("UPDATE `" . $targetTable . "` SET `" . $getProduct['naam'] . "`=`" . $getProduct['naam'] . "`-'1' WHERE `user_id`=:user_id;
                       INSERT INTO `gebruikers_store` (`user_id` ,`soort` ,`item`,`prijs`,`currency`)
                        VALUES (:user_id, :soort, :item, :prijs, :currency)");
                $st->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_INT);
                $st->bindValue(':soort', $getProduct['soort'], PDO::PARAM_STR);
                $st->bindValue(':item', $getProduct['naam'], PDO::PARAM_STR);
                $st->bindValue(':prijs', $_POST['prijs'], PDO::PARAM_INT);
                $st->bindValue(':currency', $currencyCheck, PDO::PARAM_STR);

                $st->execute();

                echo showAlert("green","Je hebt succesvol ".$getProduct['naam']." in je winkel geplaatst.")."<br/><br/>"; refresh(3,"?page=items");

            } else {

                echo showAlert('red','Geef op of je het item voor gold of silver wil verkopen.').'<br/><br/>';

            }

        } else {

            echo showAlert('red','Geef een prijs op.').'<br/><br/>';

        }

    }

}

#alle gegevens ophalen voor alle opties
$marktsql = $db->prepare("SELECT `id`, `soort`, `naam`, `silver`, `gold`, `omschrijving_nl` FROM markt ORDER BY soort ASC, id ASC");
$marktsql->execute();

if ($gebruiker['itembox'] == 'Red box') $ruimte['max'] = 250;
elseif ($gebruiker['itembox'] == 'Blue box') $ruimte['max'] = 100;
elseif ($gebruiker['itembox'] == 'Yellow box') $ruimte['max'] = 50;
elseif ($gebruiker['itembox'] == 'Green box') $ruimte['max'] = 500;
elseif ($gebruiker['itembox'] == 'Bag') $ruimte['max'] = 20;

?>

<center>
    <?php echo '<img src="images/items/' . $gebruiker['itembox'] . '.png"> ' . $txt['title_text_1'] . ' nog <strong>' . $gebruiker['item_over'] . '</strong> van <strong>' . $ruimte['max'] . '</strong> plaatsen vrij. <img src="images/items/' . $gebruiker['itembox'] . '.png">'; ?></center>
<br/>
<?
if (isset($error)) {
    echo $error;
    refresh(3);
}
$balls = 0;
$potions = 0;
$items = 0;
$arceus = 0;
$spcitems = 0;
$stones = 0;
$megastones = 0;
$bag_seen = False;
while ($result = $marktsql->fetch(PDO::FETCH_ASSOC)) {
    #Show Megastons
    if ($result['soort'] == "megastone") {
        if ($itemdata[$result['naam']] > 0) {
            $megastones++;
            if ($megastones == 1) {
                echo '<center>';
                echo '
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="item-box">
            <tr>
              <td colspan="5" class="item-title" style="text-align:center;text-shadow:1px 1px #000; color:#424242; font-size:18px;" align="center" valign="top"><h3>Megastones</h3></td>
            </tr>
            <tr>
              <td class="top_first_td">&nbsp;</td>
              <td class="top_td">' . $txt['name'] . '</td>
              <td class="top_td">' . $txt['number'] . '</td>
              <td class="top_td">' . $txt['sellprice'] . '</td>
            </tr>
          ';
            }
            $megaImage = str_replace(" ", "_", $result['naam']);
            echo '
          <form method="post" name="1">
            <tr style="border-top: thin solid #93c7a6;">
              <td class="normal_first_td"><img src="images/megastones/' . strtolower($megaImage) . '.png" alt="' . $result['naam'] . '"style="max-width:24px;max-height:24px;" /></td>
              <td class="normal_td"><a href="?page=viewitem&id=' . $result['id'] . '" style="text-decoration:underline;">' . $result['naam'] . '</a></td>
              <td class="normal_td">' . $itemdata[$result['naam']] . '</td>
              <td class="normal_td">geen</td>
              <input type="hidden" name="name" value="' . $result['naam'] . '">
              <input type="hidden" name="amount" value="1"/>
            </tr>
            <tr>
              <td colspan="5">
              <input type="number" name="prijs" class="text_long" value=""/>
              <select class="text_long" name="currency">
                  <option value="1">Gold</option>
                  <option value="2">Silver</option>
                </select><br/><br/>
              <button type="submit" name="winkel"
                            class="button_mini" style="margin-right: 5px;">Verkoop in winkel</button>
              <a name="use_stone" href="?page=includes/use_stone&name=' . $result['naam'] . '" style="margin-right:5px;" class="button_mini pull-right">' . $txt['button_use'] . '</a>
              </td>
            </tr>
          </form>
        ';
        }
    }
    #Show Pokeballs
    if ($result['soort'] == "balls") {
        if ($itemdata[$result['naam']] > 0) {
            $balls++;
            if ($balls == 1) {
                echo '<center>';
                echo '
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="item-box">
            <tr>
              <td colspan="5" class="item-title" style="text-align:center;text-shadow:1px 1px #000; color:#424242; font-size:18px;" align="center" valign="top"><h3>' . $txt['balls'] . '</h3></td>
            </tr>
            <tr>
              <td class="top_first_td">&nbsp;</td>
              <td class="top_td">' . $txt['name'] . '</td>
              <td class="top_td">' . $txt['number'] . '</td>
              <td class="top_td">' . $txt['sellprice'] . '</td>
            </tr>
          ';
            }
            if ($result['naam'] == 'Meisterball') {
                $prijs = $result['gold'] / 2;
                $price = number_format($prijs, 2, ',', '');
                $munt = 'gold';
            } else {
                $prijs = $result['silver'] / 2;
                $price = number_format($prijs, 2, ',', '');
                $munt = 'silver';
            }
            echo '
          <form method="post" name="1">
            <tr style="border-top: thin solid #93c7a6;">
              <td class="normal_first_td"><img src="images/items/' . $result['naam'] . '.png" alt="' . $result['naam'] . '"style="max-width:24px;max-height:24px;" /></td>
              <td class="normal_td"><a href="?page=viewitem&id=' . $result['id'] . '" style="text-decoration:underline;">' . $result['naam'] . '</a></td>
              <td class="normal_td">' . $itemdata[$result['naam']] . '</td>
              <td class="normal_td"><img src="images/icons/' . $munt . '.png" title="' . $munt . '" style="margin-bottom:-3px;" /> ' . $price . '</td>
              <input type="hidden" name="name" value="' . $result['naam'] . '">
              <input type="hidden" name="amount" value="1"/>
            </tr>
            <tr>
              <td colspan="5">
              <input type="number" name="prijs" class="text_long" value=""/>
              <select class="text_long" name="currency">
                  <option value="1">Gold</option>
                  <option value="2">Silver</option>
                </select><br/><br/>
              <button type="submit" name="winkel"
                            class="button_mini" style="margin-right: 5px;">Verkoop in winkel</button>
                            <button type="submit" name="verkoop" class="button_mini pull-right">' . $txt['button_sell'] . '</button></td>
            </tr>
          </form>
        ';
        }
    } #Show Potions
    elseif ($result['soort'] == "potions") {
        if ($itemdata[$result['naam']] > 0) {
            $potions++;
            if ($potions == 1) {
                echo '
            </table>
            <div class="item-footer"></div>
            <br />
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="item-box">
            <tr>
              <td colspan="6" class="item-title" style="text-align:center;text-shadow:1px 1px #000; color:#424242; font-size:18px;" align="center" valign="top"><h3>' . $txt['potions'] . '</h3></td>
            </tr>
            <tr>
              <td class="top_first_td">&nbsp;</td>
              <td class="top_td">' . $txt['name'] . '</td>
              <td class="top_td">' . $txt['number'] . '</td>
              <td class="top_td">' . $txt['sellprice'] . '</td>
            </tr>
          ';
            }
            $prijs = $result['silver'] / 2;
            $price = number_format($prijs, 2, ',', '');
            echo '
          <form method="post">
            <tr style="border-top: thin solid #93c7a6;">
              <td class="normal_first_td"><img src="images/items/' . $result['naam'] . '.png" alt="' . $result['naam'] . '" style="max-width:24px;max-height:24px;" /></td>
              <td class="normal_td"><a href="?page=viewitem&id=' . $result['id'] . '" style="text-decoration:underline;">' . $result['naam'] . '</a></td>
              <td class="normal_td">' . $itemdata[$result['naam']] . '</td>
              <td class="normal_td"><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> ' . $price . '</td>
              <input type="hidden" name="wat" value="use_potion">
              <input type="hidden" name="name" value="' . $result['naam'] . '">
              <input type="hidden" name="amount" value="1"/>
              </td>
            </tr>
            <tr>
              <td colspan="5">
              <input type="number" name="prijs" class="text_long" value=""/>
              <select class="text_long" name="currency">
                  <option value="1">Gold</option>
                  <option value="2">Silver</option>
                </select><br/><br/>
              <button type="submit" name="winkel"
                            class="button_mini" style="margin-right: 5px;">Verkoop in winkel</button>
                            <button type="submit" name="verkoop" class="button_mini pull-right">' . $txt['button_sell'] . '</button>
              <a name="use_potion" href="?page=includes/use_potion&name=' . $result['naam'] . '" style="margin-right:5px;" class="button_mini pull-right">' . $txt['button_use'] . '</a>
              </td>
            </tr>
          </form>
        ';
        }
    } #Show Items
    elseif ($result['soort'] == "items") {
        if ($itemdata[$result['naam']] > 0) {
            $items++;
            if ($items == 1) {
                echo '
          </table>
          <div class="item-footer"></div>
          <br />
          <table width="100%" cellspacing="0" cellpadding="0" class="item-box">
            <tr>
              <td colspan="5" class="item-title" style="text-align:center;text-shadow:1px 1px #000; color:#424242; font-size:18px;" align="center" valign="top"><h3>' . $txt['items'] . '</h3></td>
            </tr>
            <tr>
              <td class="top_first_td">&nbsp;</td>
              <td class="top_td">' . $txt['name'] . '</td>
              <td class="top_td">' . $txt['number'] . '</td>
              <td class="top_td">' . $txt['sellprice'] . '</td>
            </tr>';
            }
            if ($result['naam'] == 'Badge case') {
                echo '
			 <tr>
              <td class="normal_first_td"><img src="images/items/' . $result['naam'] . '.png" alt="' . $result['naam'] . '"/></td>
              <td class="normal_td">' . $result['naam'] . '</td>
              <td class="normal_td">1</td>
              <td class="normal_td"> -- </td>
            </tr>';
            }

            $prijs = $result['silver'] / 2;
            $price = number_format($prijs, 2, ',', '');
            $price = "<img src='images/icons/silver.png' title='Silver'> " . $price;
            if ($result['naam'] != 'Badge case') {
                echo '
          <form method="post">
            <tr style="border-top: thin solid #93c7a6;">
              <td class="normal_first_td"><img src="images/items/' . $result['naam'] . '.png" alt="' . $result['naam'] . '" style="max-width:24px;max-height:24px;" /></td>
              <td class="normal_td"><a href="?page=viewitem&id=' . $result['id'] . '" style="text-decoration:underline;">' . $result['naam'] . '</a></td>
              <td class="normal_td">1</td>
              <td class="normal_td">' . $price . '</td>
              <input type="hidden" name="name" value="' . $result['naam'] . '">
            </tr>
            <tr>
              <td colspan="4">
              <input type="number" name="prijs" class="text_long" value=""/>
              <select class="text_long" name="currency">
                  <option value="1">Gold</option>
                  <option value="2">Silver</option>
                </select><br/><br/>
              <button type="submit" name="winkel"
                            class="button_mini" style="margin-right: 5px;">Verkoop in winkel</button>
                            <button type="submit" name="verkoop" class="button_mini pull-right">' . $txt['button_sell'] . '</button></td>
            </tr>
          </form>
        ';
            }
        }
    } #Show Special Items
    elseif (($result['soort'] == "special items") || ($result['soort'] == "mega") || ($result['soort'] == "krone") || ($result['soort'] == "newyear") || ($result['soort'] == "narceus") || ($result['soort'] == "rare candy")) {
        if ($itemdata[$result['naam']] > 0) {
            $spcitems++;
            if ($spcitems == 1) {
                echo '
            </table>
            <div class="item-footer"></div>
            <br />
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="item-box">
              <tr>
                <td colspan="6" class="item-title" style="text-align:center;text-shadow:1px 1px #000; color:#424242; font-size:18px;" align="center" valign="top"><h3>' . $txt['spc_items'] . '</h3></td>
              </tr>
              <tr>
                <td class="top_first_td">&nbsp;</td>
                <td class="top_td">' . $txt['name'] . '</td>
                <td class="top_td">' . $txt['number'] . '</td>
                <td class="top_td">' . $txt['sellprice'] . '</td>
              </tr>';
            }
            if ($result['soort'] == 'rare candy') {
                $prijs = round($result['gold'] / 2);
                $price = number_format($prijs, 2, ',', '');
                $munt = 'gold';
                echo '
            <form method="post">
              <tr style="border-top: thin solid #93c7a6;">
                <td class="normal_first_td"><img src="images/items/' . $result['naam'] . '.png" alt="' . $result['naam'] . '" style="max-width:24px;max-height:24px;" /></td>
                <td class="normal_td"><a href="?page=viewitem&id=' . $result['id'] . '" style="text-decoration:underline;">' . $result['naam'] . '</a></td>
                <td class="normal_td">' . $itemdata[$result['naam']] . '</td>
                <td class="normal_td"><img src="images/icons/' . $munt . '.png" title="' . $munt . '" style="margin-bottom:-3px;" /> ' . $price . '</td>
                <input type="hidden" name="name" value="' . $result['naam'] . '">
                <input type="hidden" name="amount" value="1"/>
              </tr>
              <tr>
                <td colspan="4">
              <input type="number" name="prijs" class="text_long" value=""/>
              <select class="text_long" name="currency">
                  <option value="1">Gold</option>
                  <option value="2">Silver</option>
                </select><br/><br/>
              <button type="submit" name="winkel"
                            class="button_mini" style="margin-right: 5px;">Verkoop in winkel</button>
                            <button type="submit" name="verkoop" class="button_mini pull-right">' . $txt['button_sell'] . '</button>
                <a name="use_rarecandy" href="?page=includes/use_rarecandy&name=' . $result['naam'] . '" style="margin-right:5px;" class="button_mini pull-right">' . $txt['button_use'] . '</a>
                </td>
              </tr>
            </form>';
            } elseif ($result['naam'] == 'Megastein') {
                $prijs = $result['gold'] / 2;
                $price = number_format($prijs, 2, ',', '');
                $munt = 'gold';
                echo '
            <form method="post">
              <tr style="border-top: thin solid #93c7a6;">
                <td class="normal_first_td"><img src="images/items/' . $result['naam'] . '.png" alt="' . $result['naam'] . '" style="max-width:24px;max-height:24px;" /></td>
                <td class="normal_td"><a href="?page=viewitem&id=' . $result['id'] . '" style="text-decoration:underline;">' . $result['naam'] . '</a></td>
                <td class="normal_td">' . $itemdata[$result['naam']] . '</td>
                <td class="normal_td"><img src="images/icons/' . $munt . '.png" title="' . $munt . '" style="margin-bottom:-3px;" /> ' . $price . '</td>
                <input type="hidden" name="name" value="' . $result['naam'] . '">
                <input type="hidden" name="amount" value="1"/>
              </tr>
              <tr>
                <td colspan="4"><button type="submit" name="verkoop" class="button_mini pull-right">' . $txt['button_sell'] . '</button></td>
              </tr>
            </form>';
            } elseif ($result['soort'] == 'narceus') {
                $prijs = $result['silver'] / 2;
                $price = number_format($prijs, 2, ',', '');
                $munt = 'silver';
                echo '
            <form method="post">
              <tr style="border-top: thin solid #93c7a6;">
                <td class="normal_first_td"><img src="images/items/' . $result['naam'] . '.png" alt="' . $result['naam'] . '"style="max-width:24px;max-height:24px;" /></td>
                <td class="normal_td"><a href="?page=viewitem&id=' . $result['id'] . '" style="text-decoration:underline;">' . $result['naam'] . '</a></td>
                <td class="normal_td">' . $itemdata[$result['naam']] . '</td>
                <td class="normal_td"><img src="images/icons/' . $munt . '.png" title="' . $munt . '" style="margin-bottom:-3px;" /> ' . $price . '</td>
                <input type="hidden" name="name" value="' . $result['naam'] . '">
                <input type="hidden" name="amount" value="1"/>
              </tr>
              <tr>
                <td colspan="4">
              <input type="number" name="prijs" class="text_long" value=""/>
              <select class="text_long" name="currency">
                  <option value="1">Gold</option>
                  <option value="2">Silver</option>
                </select><br/><br/>
              <button type="submit" name="winkel"
                            class="button_mini" style="margin-right: 5px;">Verkoop in winkel</button>
                            <button type="submit" name="verkoop" class="button_mini pull-right">' . $txt['button_sell'] . '</button>
                <a name="use_arceus" href="?page=includes/use_arceus&name=' . $result['naam'] . '" style="margin-right:5px;" class="button_mini pull-right">' . $txt['button_use'] . '</a>
                </td>
              </tr>
            </form>';
            } elseif ($result['soort'] == 'krone') {
                $prijs = $result['gold'] / 2;
                $price = number_format($prijs, 2, ',', '');
                $munt = 'gold';
                echo '
            <form method="post">
              <tr style="border-top: thin solid #93c7a6;">
                <td class="normal_first_td"><img src="images/items/' . $result['naam'] . '.png" alt="' . $result['naam'] . '"style="max-width:24px;max-height:24px;" /></td>
                <td class="normal_td"><a href="?page=viewitem&id=' . $result['id'] . '" style="text-decoration:underline;">' . $result['naam'] . '</a></td>
                <td class="normal_td">' . $itemdata[$result['naam']] . '</td>
                <td class="normal_td"><img src="images/icons/' . $munt . '.png" title="' . $munt . '" style="margin-bottom:-3px;" /> ' . $price . '</td>
                <input type="hidden" name="name" value="' . $result['naam'] . '">
                <input type="hidden" name="amount" value="1"/>
              </tr>
              <tr>
                <td colspan="4">
              <input type="number" name="prijs" class="text_long" value=""/>
              <select class="text_long" name="currency">
                  <option value="1">Gold</option>
                  <option value="2">Silver</option>
                </select><br/><br/>
              <button type="submit" name="winkel"
                            class="button_mini" style="margin-right: 5px;">Verkoop in winkel</button>
                            <button type="submit" name="verkoop" class="button_mini pull-right">' . $txt['button_sell'] . '</button>
                <input type="submit" value="Verkoop" style="margin-right:5px;" class="button_mini pull-right"></td>
              </tr>
            </form>';
            } else {
                $prijs = $result['silver'] / 2;
                $price = number_format($prijs, 2, ',', '');
                $munt = 'silver';
                echo '
            <form method="post">
              <tr style="border-top: thin solid #93c7a6;">
                <td class="normal_first_td"><img src="images/items/' . $result['naam'] . '.png" alt="' . $result['naam'] . '" style="max-width:24px;max-height:24px;" /></td>
                <td class="normal_td"><a href="?page=viewitem&id=' . $result['id'] . '" style="text-decoration:underline;">' . $result['naam'] . '</a></td>
                <td class="normal_td">' . $itemdata[$result['naam']] . '</td>
                <td class="normal_td"><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> ' . $price . '</td>
                <input type="hidden" name="name" value="' . $result['naam'] . '">
                <input type="hidden" name="amount" value="1"/>
              </tr>
              <tr>
                <td colspan="4">
              <input type="number" name="prijs" class="text_long" value=""/>
              <select class="text_long" name="currency">
                  <option value="1">Gold</option>
                  <option value="2">Silver</option>
                </select><br/><br/>
              <button type="submit" name="winkel"
                            class="button_mini" style="margin-right: 5px;">Verkoop in winkel</button>
                            <button type="submit" name="verkoop" class="button_mini pull-right">' . $txt['button_sell'] . '</button>
                <a name="use_spcitem" href="?page=includes/use_spcitem&name=' . $result['naam'] . '" style="margin-right:5px;" class="button_mini pull-right">' . $txt['button_use'] . '</a>
                </td>
              </tr>
            </form>';
            }
        }
    } #Show Special Items
    elseif ($result['soort'] == "stones") {
        if ($itemdata[$result['naam']] > 0) {
            $stones++;
            if ($stones == 1) {
                echo '
            </table>
            <div class="item-footer"></div>
            <br />
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="item-box">
              <tr>
                <td colspan="6" class="item-title" style="text-align:center;text-shadow:1px 1px #000; color:#424242; font-size:18px;" align="center" valign="top"><h3>' . $txt['stones'] . '</h3></td>
              </tr>
              <tr>
                <td class="top_first_td">' . $txt['#'] . '</td>
                <td class="top_td">' . $txt['name'] . '</td>
                <td class="top_td">' . $txt['number'] . '</td>
                <td class="top_td">' . $txt['sellprice'] . '</td>
              </tr>
          ';
            }
            if ($result['naam'] == 'Fan' OR $result['naam'] == 'Mow' OR $result['naam'] == 'Megastein' OR $result['naam'] == 'Heat' OR $result['naam'] == 'Frost' OR $result['naam'] == 'Wash' OR $result['naam'] == 'A-Meteorit' OR $result['naam'] == 'D-Meteorit' OR $result['naam'] == 'S-Meteorit') {
                $prijs = $result['gold'] / 2;
                $price = number_format($prijs, 2, ',', '');
                $munt = 'gold';
                echo '
            <form method="post">
              <tr style="border-top: thin solid #93c7a6;">
                <td class="normal_first_td"><img src="images/items/' . $result['naam'] . '.png" alt="' . $result['naam'] . '" style="max-width:24px;max-height:24px;" /></td>
                <td class="normal_td"><a href="?page=viewitem&id=' . $result['id'] . '" style="text-decoration:underline;">' . $result['naam'] . '</a></td>
                <td class="normal_td">' . $itemdata[$result['naam']] . '</td>
                <td class="normal_td"><img src="images/icons/' . $munt . '.png" title="' . $munt . '" style="margin-bottom:-3px;" /> ' . $price . '</td>
                <input type="hidden" name="name" value="' . $result['naam'] . '">
                <input type="hidden" name="amount" value="1"/>
              </tr>
              <tr>
                <td colspan="4">
              <input type="number" name="prijs" class="text_long" value=""/>
              <select class="text_long" name="currency">
                  <option value="1">Gold</option>
                  <option value="2">Silver</option>
                </select><br/><br/>
              <button type="submit" name="winkel"
                            class="button_mini" style="margin-right: 5px;">Verkoop in winkel</button>
                            <button type="submit" name="verkoop" class="button_mini pull-right">' . $txt['button_sell'] . '</button>
                <a name="use_rarecandy" href="?page=includes/use_rarecandy&name=' . $result['naam'] . '" style="margin-right:5px;" class="button_mini pull-right">' . $txt['button_use'] . '</a>
                </td>
              </tr>
            </form>';
            } else {
                $prijs = $result['silver'] / 2;
                $price = number_format($prijs, 2, ',', '');
                $munt = 'silver';
                echo '
            <form method="post">
              <tr style="border-top: thin solid #93c7a6;">
                <td class="normal_first_td"><img src="images/items/' . $result['naam'] . '.png" alt="' . $result['naam'] . '" style="max-width:24px;max-height:24px;" /></td>
                <td class="normal_td"><a href="?page=viewitem&id=' . $result['id'] . '" style="text-decoration:underline;">' . $result['naam'] . '</a></td>
                <td class="normal_td">' . $itemdata[$result['naam']] . '</td>
                <td class="normal_td"><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> ' . $price . '</td>
                <input type="hidden" name="name" value="' . $result['naam'] . '">
                <input type="hidden" name="amount" value="1"/>
              </tr>
            <tr>
              <td colspan="4">
              <input type="number" name="prijs" class="text_long" value=""/>
              <select class="text_long" name="currency">
                  <option value="1">Gold</option>
                  <option value="2">Silver</option>
                </select><br/><br/>
              <button type="submit" name="winkel"
                            class="button_mini" style="margin-right: 5px;">Verkoop in winkel</button>
                            <button type="submit" name="verkoop" class="button_mini pull-right">' . $txt['button_sell'] . '</button>
              <a name="use_spcitem" href="?page=includes/use_stone&name=' . $result['naam'] . '" style="margin-right:5px;" class="button_mini pull-right">' . $txt['button_use'] . '</a>
              </td>
            </tr>
            </form>';
            }
        }
    } #Show Special Items
    elseif ($result['soort'] == "tm") {

        $inaam = $result['naam'];

        $tmsqlQuery = $db->prepare("SELECT `type2` FROM tmhm WHERE `naam` = :name");
        $tmsqlQuery->bindValue(':name', $inaam, PDO::PARAM_STR);
        $tmsqlQuery->execute();
        $tmsql = $tmsqlQuery->fetch(PDO::FETCH_ASSOC);

        if ($itemdata[$result['naam']] > 0) {
            $tm++;
            if ($tm == 1) {
                echo '
            </table>
            <div class="item-footer"></div>
            <br />
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="item-box">
              <tr>
                <td colspan="6" class="item-title" style="text-align:center;text-shadow:1px 1px #000; color:#424242; font-size:18px;" align="center" valign="top"><h3>' . $txt['tm'] . '</h3></td>
              </tr>
              <tr>
                <td class="top_first_td">' . $txt['#'] . '</td>
                <td class="top_first_td"><b>' . $txt['name'] . '</b></td>
                <td class="top_td"><b>' . $txt['number'] . '</b></td>
                <td class="top_td"><b>' . $txt['sellprice'] . '</b></td>
              </tr>';
            }
            $prijs = $result['silver'] / 2;
            $price = number_format($prijs, 2, ',', '');
            echo '
          <form method="post">
            <tr style="border-top: thin solid #93c7a6;">
              <td class="normal_first_td"><img src="images/items/Attack_' . $tmsql['type2'] . '.png" alt="' . $result['naam'] . '" style="max-width:24px;max-height:24px;" /></td>
              <td class="normal_td"><a href="?page=viewitem&id=' . $result['id'] . '" style="text-decoration:underline;">' . $inaam . '</a></td>
              <td class="normal_td">' . $itemdata[$result['naam']] . '</td>
              <td class="normal_td"><img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> ' . $price . '</td>
              <input type="hidden" name="name" value="' . $inaam . '">
              <input type="hidden" name="amount" value="1"/>
            </tr>
            <tr>
              <td colspan="4">
              <input type="number" name="prijs" class="text_long" value=""/>
              <select class="text_long" name="currency">
                  <option value="1">Gold</option>
                  <option value="2">Silver</option>
                </select><br/><br/>
              <button type="submit" name="winkel"
                            class="button_mini" style="margin-right: 5px;">Verkoop in winkel</button>
                            <button type="submit" name="verkoop" class="button_mini pull-right">' . $txt['button_sell'] . '</button>
              <a name="use_attack" href="?page=includes/use_attack&name=' . $result['naam'] . '" style="margin-right:5px;" class="button_mini pull-right">' . $txt['button_use'] . '</a>
              </td>
            </tr>
          </form>';
        }
    } #Show Special HM
    elseif ($result['soort'] == "hm") {

        $inaam = $result['naam'];

        if ($inaam == 'HM01') $type = 'Grass';
        elseif ($inaam == 'HM02') $type = 'Flying';
        elseif ($inaam == 'HM03' || $inaam == 'HM07' || $inaam == 'HM08') $type = 'Water';
        elseif ($inaam == 'HM04' || $inaam == 'HM06') $type = 'Fighting';
        else $type = 'Electric';


        if ($itemdata[$result['naam']] > 0) {
            $hm++;
            if ($hm == 1) {
                echo '
            </table>
            <div class="item-footer"></div>
            <br />
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="item-box">
              <tr>
                <td colspan="4" class="item-title" style="text-align:center;text-shadow:1px 1px #000; color:#424242; font-size:18px;" align="center" valign="top"><h3>' . $txt['hm'] . '</h3></td>
              </tr>
              <tr>
                <td class="top_first_td">' . $txt['#'] . '</td>
                <td class="top_td">' . $txt['name'] . '</td>
                <td class="top_td">' . $txt['number'] . '</td>
                <td class="top_td">' . $txt['sellprice'] . '</td>
              </tr>
          ';
            }
            $prijs = $result['silver'] / 2;
            $price = number_format($prijs, 2, ',', '');
            echo '
          <form method="post">
            <tr style="border-top: thin solid #93c7a6;">
              <td class="normal_first_td"><img src="images/items/Attack_' . $type . '.png" alt="' . $result['naam'] . '" style="max-width:24px;max-height:24px;" /></td>
              <td class="normal_td"><a href="?page=viewitem&id=' . $result['id'] . '" style="text-decoration:underline;">' . $inaam . '</a></td>
              <td class="normal_td">1</td>
              <td class="normal_td"> -- </td>
			  <input type="hidden" name="name" value="' . $inaam . '">
              <input type="hidden" name="amount" value="1"/>
            </tr>
             <tr>
              <td colspan="4">
              <input type="number" name="prijs" class="text_long" value=""/>
              <select class="text_long" name="currency">
                  <option value="1">Gold</option>
                  <option value="2">Silver</option>
                </select><br/><br/>
              <button type="submit" name="winkel"
                            class="button_mini" style="margin-right: 5px;">Verkoop in winkel</button>
              <a name="use_attack" href="?page=includes/use_attack&name=' . $result['naam'] . '" style="margin-right:5px;" class="button_mini pull-right">' . $txt['button_use'] . '</a>
              </td>
            </tr>
          </form>';
        }
    }
}
?>
</table>
<div class="item-footer"></div>
</center>