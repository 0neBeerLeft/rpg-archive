<?
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

$query1 = "SELECT * FROM pokemon_wild WHERE wild_id =:pokemon";
$query1 = $db->prepare($query1);
$query1->bindParam(':pokemon', $_GET['pokemon'], PDO::PARAM_INT);
$query1->execute();
$query1 = $query1->fetch(PDO::FETCH_ASSOC);

$query2 = "SELECT * FROM gebruikers INNER JOIN pokemon_speler ON pokemon_speler.user_id = gebruikers.user_id WHERE pokemon_speler.wild_id = :pokemon AND gebruikers.account_code = '1' AND gebruikers.admin = '0' GROUP BY pokemon_speler.user_id";
$query2 = $db->prepare($query2);
$query2->bindParam(':pokemon', $_GET['pokemon'], PDO::PARAM_INT);
$query2->execute();
$query2 = $query2->rowCount();

$query3 = "SELECT * FROM gebruikers INNER JOIN pokemon_speler ON pokemon_speler.user_id = gebruikers.user_id WHERE pokemon_speler.wild_id = :pokemon AND gebruikers.account_code = '1' AND gebruikers.admin = '0' GROUP BY pokemon_speler.user_id";
$query3 = $db->prepare($query3);
$query3->bindParam(':pokemon', $_GET['pokemon'], PDO::PARAM_INT);
$query3->execute();
$query3 = $query3->fetchAll(PDO::FETCH_ASSOC);

$page = 'catched';
#Goeie taal erbij laden voor de page
include('language/language-pages.php');

?>
<div class="catched-shiny"><?=$txt['shiny']?></div>
<div class="catched"><?=$txt['normal']?></div>

<center>
    <a href="?page=information&category=pokemon-info&pokemon=<?= $query1['wild_id'] ?>" style="display: inline-block;width: 100%;">
        <img src="images/pokemon/<?= $query1['wild_id'] ?>.gif"/>
    </a>

        <u><?= $query1['naam'] ?></u> <?=$txt['amount_caught']?>

    <div class="sep"></div>
    <table width="720">
        <tr>
        <?
        foreach ($query3 as $query) {

            $catched = "SELECT * FROM gebruikers WHERE user_id = :user_id AND account_code = '1' AND admin = '0' GROUP BY user_id DESC LIMIT 1";
            $catched = $db->prepare($catched);
            $catched->bindParam(':user_id', $query['user_id'], PDO::PARAM_INT);
            $catched->execute();
            $catched = $catched->fetch(PDO::FETCH_ASSOC);

            $shiny = '';
            if ($query['shiny'] == 1) {
                $shiny = '-shiny';
            }
            echo '<div class="catched' . $shiny . '"><a href="?page=profile&player=' . $catched['username'] . '">' . $catched['username'] . '</a></div>';
        }
        ?>
        </tr>
    </table>
</center>