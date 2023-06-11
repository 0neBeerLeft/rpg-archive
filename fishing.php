<?php
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

?>

<center><td class="border_black"><img src="images/Fisherman.png"></td></center>
<center><h2>Vissen</h2></center>
<hr>

<?php
if($_POST){

    if($_POST['rod'] == "1"){
        $item = $db->prepare("SELECT * FROM `gebruikers_item` WHERE `user_id`=:uid AND `Fishing rod`='1'");
        $item->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
        $item->execute();

        $type = "fishing rod";
        $multiple = 0;
    }

    if(($_POST['rod'] == "") == ($item['Fishing rod'] == 0)){
        echo '<div class="red">Je hebt geen fishing pole.</div>';
        $error = 1;
    }

    if($item['Fishing rod'] == 1){
        echo "<div class='red'>Je kan niet zonder een ".$type." vissen.</div>";
        $error = 1;
    }

//Als vissen al gedaan is
    if($gebruiker['fish'] == 0){
        if($gebruiker['premiumaccount'] == 0){
            echo '<div class="red">Je kan helaas niet meer vissen vandaag.<br/>Premiumleden kunnen dit 6x per dag doen. <a href="index.php?page=area-market"><strong>Word hier premium!</strong></a></div>';
        }else{
            echo '<div class="red">Je kan helaas niet meer vissen vandaag.</div>';
        }
        $error = 1;
    }

    if($error != 1){

        $op1 = "Water";
        $op2 = "Ice";

        $swappa1 = mysql_query();

        $swappa1 = $db->prepare("SELECT * FROM `pokemon_wild` WHERE `wild_id` < '650' AND (`type1`=:op1 OR `type1`=:op2 OR `type2`=:op2 OR `type2`=:op2) ORDER BY RAND() Limit 0,1");
        $swappa1->bindValue(':op1', $op1);
        $swappa1->bindValue(':op2', $op2);
        $swappa1->execute();

        $swappah = $swappa1->fetch(PDO::FETCH_OBJ);

        $total = $swappah->hp_base + $swappah->attack_base + $swappah->defence_base + $swappah->speed_base;

        $total = $total * 73;

        $points = rand(1,$total);

        $points = $points + $multiple;


        $updatePoints = $db->prepare("UPDATE `gebruikers` SET `fishing`=:points ,fish=fish-1 WHERE `user_id`=:uid");
        $updatePoints->bindValue(':points', $points, PDO::PARAM_INT);
        $updatePoints->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
        $updatePoints->execute();

        echo "<table class='finished' width='650' align='center'><tr>";
        echo "<td>Met een <b>".$type."</b> heb je een <b>".$swappah->naam."</b> gevangen!<br><br><img src='/images/pokemon/".$swappah->wild_id.".gif'><br><br>De jury heeft <b>".number_format($points)." punten</b> toegekend.</td>";
        echo "</tr></table>";

    }
}
?>

<div class="contentcontent">
    <center><br />
        Welkom op het vistoernooi.<br /> De visser die de grootste vis vangt krijgt de hoofdprijs.<br /><br />
        <b>1e plaats:</b> 2000 <img src="images/icons/silver.png"><br>
        <b>2de plaats:</b> 1500 <img src="images/icons/silver.png"><br>
        <b>3de plaats:</b> 1000 <img src="images/icons/silver.png"><br><br>

        <small><b><font color=red>TIP:</font></b> Elke nieuwe vangst overschrijft je vorige score.</small><br><br>

        <br>
        <form method="post">
            <img src="images/items/Fishing rod.png"></br> Vissen: <input type="radio" name="rod" value="1" <?if($gebruiker['Fishing rod'] == 1){ ?>checked<?php } else { ?> disabled <?php } ?>><br><br>
            <button type="submit" name="fish" class="button">Beginnen</button>
        </form></center>
</div>
<hr>
<div class="title"><b>Vandaag de beste visser</b></div>
<table class="general">
    <tr>
        <td width="20"><b>#</b></td>
        <td width="350"><b> Gebruikersnaam</b></td>
        <td><b>Score</b></td>
    </tr>
    <?
    $profiles1 = $db->prepare("SELECT username,fishing FROM `gebruikers` ORDER BY `fishing` DESC LIMIT 3");
    $profiles1->execute();
    while($profiles = $profiles1->fetch(PDO::FETCH_OBJ)){

        $i++;
        if($profiles->fishing != 0) {
            if ($i == 1) {
                $r = "1.";
            }
            if ($i == 2) {
                $r = "2.";
            }
            if ($i == 3) {
                $r = "3.";
            }
            ?>
            <tr>
                <td><?= $r ?></td>
                <td><a href="index.php?page=profile&player=<?= $profiles->username ?>"><?= $profiles->username ?></a></td>
                <td><?= number_format($profiles->fishing) ?> Punten</td>
            </tr>
            <?
        }
    }
    ?>

</table>

<div class="title"><b>De beste vissers van gisteren</b></div>
<table class="general">
    <tr>
        <td width="20"><b>#</b></td>
        <td><b>Gebruikersnaam</b></td>
    </tr>

    <?

    $checknumber1 = $db->prepare("SELECT * FROM `server` WHERE `id`='1'");

    $checknumber = $checknumber1->fetch(PDO::FETCH_OBJ);

    $lastwin11 = $db->prepare("SELECT username FROM `gebruikers` WHERE `user_id`='$checknumber->fish'");
    $lastwin1 = $lastwin11->fetch(PDO::FETCH_OBJ);
    $lastwin12 = $db->prepare("SELECT username FROM `gebruikers` WHERE `user_id`='$checknumber->fish2'");
    $lastwin2 = $lastwin12->fetch(PDO::FETCH_OBJ);
    $lastwin13 = $db->prepare("SELECT username FROM `gebruikers` WHERE `user_id`='$checknumber->fish3'");
    $lastwin3 = $lastwin13->fetch(PDO::FETCH_OBJ);

    echo "<tr><td>1.</td><td><a href='index.php?page=profile&player=" . $lastwin1->username . "'>" . $lastwin1->username . "</a></td></tr>";

    echo "<tr><td>2.</td><td><a href='index.php?page=profile&player=" . $lastwin2->username . "'>" . $lastwin2->username . "</a></td></tr>";

    echo "<tr><td>3.</td><td><a href='index.php?page=profile&player=" . $lastwin3->username . "'>" . $lastwin3->username . "</a></td></tr>";

    ?>

</table>