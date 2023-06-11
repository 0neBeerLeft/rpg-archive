<?
#Security laden
include('includes/security.php');

$page = 'multiblackjack';

$selectDelete = $db->prepare("SELECT * FROM multiblackjack WHERE creation < NOW() - INTERVAL 2 WEEK");
$selectDelete->execute();
$selectDelete = $selectDelete->fetchAll();

foreach ($selectDelete as $delete) {
    $query = $db->prepare("DELETE FROM multiblackjack WHERE id = ".$delete['id']." ");
    $query->execute();
}

function addEventMessage ($playerToNotify, $endMessage) {

    global $db;

    $aUser = $db->prepare("SELECT user_id FROM `gebruikers` WHERE username = '".$playerToNotify."'");
    $aUser->execute();
    $rUser = $aUser->fetchAll();

    $query = $db->prepare("INSERT INTO gebeurtenis (datum, ontvanger_id, bericht, gelezen)
                          VALUES (NOW(), '" . $rUser[0]['user_id'] . "', '$endMessage', '0')");
    $query->execute();

}

if (empty($_SESSION['blackjack_multi']) AND isset($_GET['stapin']))
{
    $iStapIn = intval($_GET['stapin']);
    unset($_SESSION['bj_kaarten']);
    unset($_SESSION['bj_kaarten_plaatjes']);

    if ($iStapIn == 0)
    {
        if($gebruiker['silver'] >= 150) {
            $query = $db->prepare("UPDATE `gebruikers` SET silver = silver-150 WHERE username = '".$_SESSION['naam']."'");
            $query->execute();
            $query = $db->prepare('INSERT INTO `multiblackjack` (player1,creation) VALUES ("'.$_SESSION['naam'].'",NOW())');
            $query->execute();
            $_SESSION['blackjack_multi'] = $db->lastInsertId();
        }else{
            echo 'Je hebt niet genoeg geld op zak!';
        }
    }
    else
    {
        $rSpel = $db->prepare('SELECT player1,player2,player3,player4,gestart FROM `multiblackjack` WHERE id = '.$iStapIn);
        $rSpel->execute();

        $aSpel = $rSpel->fetchAll();
        if (count($aSpel) > 0)
        {
            if ($aSpel[0]['player1'] == $_SESSION['naam'])
            {
                $iPlayer = 1;
                $_SESSION['blackjack_multi'] = $iStapIn;
            }
            elseif ($aSpel[0]['player2'] == $_SESSION['naam'])
            {
                $iPlayer = 2;
                $_SESSION['blackjack_multi'] = $iStapIn;
            }
            elseif ($aSpel[0]['player3'] == $_SESSION['naam'])
            {
                $iPlayer = 3;
                $_SESSION['blackjack_multi'] = $iStapIn;
            }
            elseif ($aSpel[0]['player4'] == $_SESSION['naam'])
            {
                $iPlayer = 4;
                $_SESSION['blackjack_multi'] = $iStapIn;
            }
            else
            {
                if ($aSpel[0]['gestart'] == 0)
                {
                    if (empty($aSpel[0]['player2']))
                    {
                        if($gebruiker['silver'] >= 150) {
                            $quary = $db->prepare("UPDATE `gebruikers` SET silver = silver-150 WHERE username = '" . $_SESSION['naam'] . "'");
                            $quary->execute();
                            $quary = $db->prepare("UPDATE `multiblackjack` SET player2 = '" . $_SESSION['naam'] . "' WHERE id = '" . $iStapIn . "'");
                            $quary->execute();

                            $_SESSION['blackjack_multi'] = $iStapIn;
                        }else{
                            echo 'Je hebt niet genoeg geld op zak!';
                        }
                    }
                    elseif (empty($aSpel[0]['player3']))
                    {
                        if($gebruiker['silver'] >= 150) {
                            $quary = $db->prepare("UPDATE `gebruikers` SET silver = silver-150 WHERE username = '".$_SESSION['naam']."'");
                            $quary->execute();
                            $quary = $db->prepare("UPDATE `multiblackjack` SET player3 = '".$_SESSION['naam']."' WHERE id = '".$iStapIn."'");
                            $quary->execute();
                            $_SESSION['blackjack_multi'] = $iStapIn;
                        }else{
                            echo 'Je hebt niet genoeg geld op zak!';
                        }
                    }
                    elseif (empty($aSpel[0]['player4']))
                    {
                        if($gebruiker['silver'] >= 150) {
                            $quary = $db->prepare("UPDATE `gebruikers` SET silver = silver-150 WHERE username = '".$_SESSION['naam']."'");
                            $quary->execute();
                            $quary = $db->prepare("UPDATE `multiblackjack` SET player4 = '".$_SESSION['naam']."', gestart = '.time().' WHERE id = '".$iStapIn."'");
                            $quary->execute();
    
                            $_SESSION['blackjack_multi'] = $iStapIn;
                        }else{
                            echo 'Je hebt niet genoeg geld op zak!';
                        }
                    }
                    else
                    {
                        echo 'Het spel heeft al 4 spelers!';
                    }
                }
                else
                {
                    // spel is al gestart
                    echo 'Ze zijn al begonnen zonder jou :-)';
                }
            }
        }
        else
        {
            // bestaat niet
            echo 'Dit spel bestaat niet (meer).';
        }
    }
}
elseif (isset($_GET['stapuit']))
{

    $rSpel = $db->prepare('SELECT id,player1,player2,player3,player4,gestart FROM `multiblackjack` WHERE id = '.$_SESSION['blackjack_multi'].'');
    $rSpel->execute();
    $aSpel = $rSpel->fetchAll();

    if (count($aSpel) > 0)
    {
        if ($aSpel[0]['gestart'] == 0)
        {
            if ($aSpel[0]['player1'] == $_SESSION['naam'])
            {
                $query = $db->prepare('DELETE FROM `multiblackjack` WHERE id = '.$_SESSION['blackjack_multi'].'');
                $query->execute();
            }
            elseif ($aSpel[0]['player2'] == $_SESSION['naam'])
            {
                $query = $db->prepare('UPDATE `multiblackjack` SET player2="" WHERE id = '.$_SESSION['blackjack_multi'].'');
                $query->execute();
            }
            elseif ($aSpel[0]['player3'] == $_SESSION['naam'])
            {
                $query = $db->prepare('UPDATE `multiblackjack` SET player3="" WHERE id = '.$_SESSION['blackjack_multi'].'');
                $query->execute();
            }
            elseif ($aSpel[0]['player4'] == $_SESSION['naam'])
            {
                $query = $db->prepare('UPDATE `multiblackjack` SET player4="" WHERE id = '.$_SESSION['blackjack_multi'].'');
                $query->execute();
            }
            unset($_SESSION['blackjack_multi']);
        }
    }
    else
    {
        unset($_SESSION['blackjack_multi']);
    }
}
elseif (isset($_GET['exit']))
{
    unset($_SESSION['blackjack_multi']);
}

if (isset($_SESSION['blackjack_multi']))
{
    $rSpel = $db->prepare('SELECT id,tijd1,tijd2,tijd3,tijd4,score4,score3,score2,score1,player1,player2,player3,player4,gestart FROM `multiblackjack` WHERE id = '.$_SESSION['blackjack_multi'].'');
    $rSpel->execute();

    $aSpel = $rSpel->fetchAll();

    if (count($aSpel) > 0)
    {
        if (isset($_GET['start']) AND $_GET['start'] == $aSpel[0]['id'])
        {

            $query = $db->prepare('UPDATE `multiblackjack` SET gestart = '.time().' WHERE id = '.$_SESSION['blackjack_multi'].'');
            $query->execute();
            ?>
            <script language='JavaScript'>
                <!--
                setTimeout("location.href='index.php?page=multiblackjack'", 1)
                //-->
            </script>
            <?
        }

        function nieuweKaart()
        {
            $iKaart     = rand(1,8);
            $iSoort     = rand(1,4);
            $aSoort     = Array('','harten','schoppen','ruiten','klaveren');
            $aKaart     = Array('','7','8','9','10','boer','vrouw','koning','aas');
            $aWaarde     = Array('',7,8,9,10,10,10,10,11);

            return Array($aSoort[$iSoort].''.strtolower($aKaart[$iKaart]),$aWaarde[$iKaart]);
        }

        function totaleWaarde ($aKaarten)
        {
            $aWaarde         = Array('',7,8,9,10,10,10,10,11);
            $iTotaleWaarde    = 0;
            $iAzen             = 0;

            foreach ($aKaarten as $iKaart => $value)
            {

                $iTotaleWaarde += $value;
                if ($value == 11)
                {
                    $iAzen++;
                }
            }

            while ($iAzen > 0 AND $iTotaleWaarde > 21)
            {
                $iTotaleWaarde -= 10;
                $iAzen--;
            }

            RETURN $iTotaleWaarde;
        }

        if (isset($aSpel[0]['gestart']) && $aSpel[0]['gestart']== 0)
        {
            $aSpelers[] = $aSpel[0]['player1'];
            if (!empty($aSpel[0]['player2']))
            {
                $aSpelers[] = $aSpel[0]['player2'];
            }
            if (!empty($aSpel[0]['player3']))
            {
                $aSpelers[] = $aSpel[0]['player3'];
            }
            if (!empty($aSpel[0]['player4']))
            {
                $aSpelers[] = $aSpel[0]['player4'];
            }
            $aSpel['spelers'] = implode(', ',$aSpelers);
            echo '';
            if ($aSpel[0]['player1'] == $_SESSION['naam'])
            {
                if (count($aSpelers) > 1)
                {
                    echo '
<table width="100%">
  <caption>De volgende spelers spelen al mee: <br><br>'.$aSpel['spelers'].'
  <br><br>Wacht tot het spel word gestart door jou<br><br>
  </caption>
  <tr>
    <td width="33%" align="left"><a class="button" href="index.php?page=multiblackjack&stapuit=1">Stop het spel</a></td>
	<td width="34%" align="center"><a class="button" href="index.php?page=multiblackjack&start='.$aSpel[0]['id'].'">Start het spel</a></td>
	<td width="33%" align="right"><a class="button" href="index.php?page=multiblackjack&exit=1">Terug</a></td>
  </tr>
</table>
';
                }
                else
                {
                    echo '
<table width="100%">
  <caption>De volgende spelers spelen al mee: <br><br>'.$aSpel['spelers'].'
  <br><br>Wacht tot er voldoende spelers zijn.<br><br>
  </caption>
  <tr>
	<td><a class="button" href="index.php?page=multiblackjack&stapuit=1">Stop het spel</a></td>
	<td align="right"><a class="button" href="index.php?page=multiblackjack&exit=1">Terug</a></td>
  </tr>
</table>
';
                }
            }
            else
            {
                echo '
<table width="100%">
  <caption>
  De volgende spelers spelen al mee: <br><br>'.$aSpel['spelers'].'
  <br><br>Wacht tot het spel word gestart door '.$aSpel[0]['player1'].'.<br><br>
  </caption>
  <tr>
	<td><a class="button" href="index.php?page=multiblackjack&stapuit=1">Stop het spel</a></td>
	<td align="right"><a class="button" href="index.php?page=multiblackjack&exit=1">Terug</a></td>
  </tr>
</table>
';
            }
            echo '';

            ?>
            <script language='JavaScript'>
                <!--
                setTimeout("location.href='index.php?page=multiblackjack'", 15000)
                //-->
            </script>
            <?
        }
        else
        {
            if (($aSpel[0]['player1'] == $_SESSION['naam'] AND $aSpel[0]['tijd1'] == 0) OR ($aSpel[0]['player2'] == $_SESSION['naam'] AND $aSpel[0]['tijd2'] == 0) OR ($aSpel[0]['player3'] == $_SESSION['naam'] AND $aSpel[0]['tijd3'] == 0) OR ($aSpel[0]['player4'] == $_SESSION['naam'] AND $aSpel[0]['tijd4'] == 0))
            {
                if ($aSpel[0]['player1'] == $_SESSION['naam'])
                {
                    $iPlayer = 1;
                }
                elseif ($aSpel[0]['player2'] == $_SESSION['naam'])
                {
                    $iPlayer = 2;
                }
                elseif ($aSpel[0]['player3']== $_SESSION['naam'])
                {
                    $iPlayer = 3;
                }
                else
                {
                    $iPlayer = 4;
                }

                echo '';
                echo 'Het spel is gestart.
					<br>Het spel wordt automatisch geannuleerd als de speeltijd voorbij is';

                // nieuwe kaart
                if (isset($_SESSION['bj_kaarten']) AND isset($_GET['kaart']) AND $_GET['kaart'] == 'new')
                {
                    list($sPlaatje,$iWaarde) = nieuweKaart();
                    $_SESSION['bj_kaarten'][]             = $iWaarde;
                    $_SESSION['bj_kaarten_plaatjes'][]     = $sPlaatje;
                }
                // stoppen
                elseif (isset($_SESSION['bj_kaarten']) AND isset($_GET['kaart']) AND $_GET['kaart'] == 'stop')
                {
                    $query = $db->prepare('UPDATE `multiblackjack` SET score'.$iPlayer.' = '.array_sum($_SESSION['bj_kaarten']).', tijd'.$iPlayer.' = "'.time().'" WHERE id = '.$_SESSION['blackjack_multi'].'');
                    $query->execute();

                    echo '</br></br>Je hebt '.totaleWaarde($_SESSION['bj_kaarten']).' punten behaald.';
                    ?>
                    <script language='JavaScript'>
                        <!--
                        setTimeout("location.href='index.php?page=multiblackjack'", 15000)
                        //-->
                    </script>
                    <?php
                }
                // eerste kaart
                elseif(!isset($_SESSION['bj_kaarten']))
                {
                    list($sPlaatje,$iWaarde) = nieuweKaart();
                    $_SESSION['bj_kaarten'][0]                 = $iWaarde;
                    $_SESSION['bj_kaarten_plaatjes'][0]     = $sPlaatje;
                }

                // kaarten weergeven
                echo '<div style="padding:20px;"><hr><div style="padding:20px;">';
                foreach ($_SESSION['bj_kaarten_plaatjes'] as $sPlaatje)
                {
                    echo '<img src="/images/cards/'.$sPlaatje.'.jpg"> ';
                }
                echo '</div><hr></div>';


                if (totaleWaarde($_SESSION['bj_kaarten']) > 21)
                {
                    $query = $db->prepare('UPDATE `multiblackjack` SET tijd'.$iPlayer.' = "'.time().'" WHERE id = '.$_SESSION['blackjack_multi'].'');
                    $query->execute();

                    echo 'Je hebt verloren.';
                    ?><script language='JavaScript'>
                    <!--
                    setTimeout("location.href='index.php?page=multiblackjack'", 15000)
                    //-->
                </script><?
                }
                elseif (totalewaarde($_SESSION['bj_kaarten']) == 21)
                {

                    $query = $db->prepare('UPDATE `multiblackjack` SET tijd'.$iPlayer.' = "'.time().'", score'.$iPlayer.' = 21 WHERE id = '.$_SESSION['blackjack_multi'].'');
                    $query->execute();

                    echo '<p>Je hebt 21.';
                    ?><script language='JavaScript'>
                    <!--
                    setTimeout("location.href='index.php?page=multiblackjack'", 15000)
                    //-->
                </script><?
                }
                else
                {
                    echo '<a class="button" href="index.php?page=multiblackjack&kaart=new">Nieuwe kaart</a> - <a class="button" href="index.php?page=multiblackjack&kaart=stop">Stop</a>';
                }


                echo '';
            }
            else
            {
                if (($aSpel[0]['tijd1'] > 0 AND $aSpel[0]['tijd2'] > 0 AND (empty($aSpel[0]['player3']) OR $aSpel[0]['tijd3'] > 0) AND (empty($aSpel[0]['player4']) OR $aSpel[0]['tijd4'] > 0)) OR $aSpel[0]['gestart'] < (time()-86400))
                {
                    $iHoogsteScore     = 0;
                    $sBesteSpeler     = '';
                    $iHoogsteTijd     = 0;
                    for ($i=1;$i<5;$i++) {
                        if ($aSpel[0]['score'.$i] >= $iHoogsteScore AND $aSpel[0]['score'.$i] < 22)
                        {
                            if ($aSpel[0]['score'.$i] == $iHoogsteScore)
                            {
                                if ($aSpel[0]['tijd'.$i] < $iHoogsteTijd)
                                {
                                    $iHoogsteScore = $aSpel[0]['score'.$i];
                                    $iHoogsteTijd = $aSpel[0]['tijd'.$i];
                                    $sBesteSpeler = $aSpel[0]['player'.$i];
                                }
                            }
                            else
                            {
                                $iHoogsteScore = $aSpel[0]['score'.$i];
                                $iHoogsteTijd = $aSpel[0]['tijd'.$i];
                                $sBesteSpeler = $aSpel[0]['player'.$i];
                            }
                        }
                    }

                    if ($sBesteSpeler != '')
                    {
                        $iSpelers = 2;
                        if (!empty($aSpel[0]['player3']))
                        {
                            $iSpelers++;
                        }
                        if (!empty($aSpel[0]['player4']))
                        {
                            $iSpelers++;
                        }

                        $iGeld = round(0.9*($iSpelers*150));
                        if ($aSpel[0]['player1'] == $_SESSION['naam'])
                        {
                            $query = $db->prepare('UPDATE `gebruikers` SET silver=silver+'.$iGeld.' WHERE `username` = "'.$sBesteSpeler.'"');
                            $query->execute();
                        }
                        $endMessage = 'De beste speler is '.$sBesteSpeler.' hij/zij heeft het snelste met '.$iHoogsteScore.' punten, <img src="images/icons/silver.png" /> '.$iGeld.',- gewonnen.';
                        echo $endMessage.'<br><br><a class="button"  href="index.php?page=multiblackjack">Terug naar overzicht</a><br><br>';

                        #end message to all users
                        if($aSpel[0]['player1'] and $aSpel[0]['player1'] != $sBesteSpeler) {
                            addEventMessage($aSpel[0]['player1'], $endMessage);
                        }
                        if($aSpel[0]['player2'] and $aSpel[0]['player2'] != $sBesteSpeler) {
                            addEventMessage($aSpel[0]['player2'], $endMessage);
                        }
                        if($aSpel[0]['player3'] and $aSpel[0]['player3'] != $sBesteSpeler) {
                            addEventMessage($aSpel[0]['player3'], $endMessage);
                        }
                        if($aSpel[0]['player4'] and $aSpel[0]['player4'] != $sBesteSpeler) {
                            addEventMessage($aSpel[0]['player4'], $endMessage);
                        }
                        if($sBesteSpeler) {
                            $endMessage = 'Gefeliciteerd je hebt met '.$iHoogsteScore.' punten, je hebt <img src="images/icons/silver.png" /> '.$iGeld.',- gewonnen.';
                            addEventMessage($sBesteSpeler, $endMessage);
                        }
                    }
                    else
                    {
                        $endMessage = 'Er is geen beste speler, iedereen had meer dan 21.';
                        echo $endMessage.'<p><a class="button"  href="index.php?page=multiblackjack">Terug naar spellenoverzicht</a><br><br>';

                        #end message to all users
                        if($aSpel[0]['player1']) {
                            addEventMessage($aSpel[0]['player1'], $endMessage);
                        }
                        if($aSpel[0]['player2']) {
                            addEventMessage($aSpel[0]['player2'], $endMessage);
                        }
                        if($aSpel[0]['player3']) {
                            addEventMessage($aSpel[0]['player3'], $endMessage);
                        }
                        if($aSpel[0]['player4']) {
                            addEventMessage($aSpel[0]['player4'], $endMessage);
                        }
                    }
                    $query = $db->prepare('DELETE FROM `multiblackjack` WHERE id = '.$_SESSION['blackjack_multi'].'');
                    $query->execute();
                    unset($_SESSION['bj_kaarten']);
                    unset($_SESSION['blackjack_multi']);
                }
                else {
                    echo 'Wacht tot de andere spelers hun kaarten hebben gespeeld. <br/><br/>
                        <a class="button" href="index.php?page=multiblackjack&exit=1">Terug</a>';
                    ?><script language='JavaScript'>
                        <!--
                        setTimeout("location.href='index.php?page=multiblackjack'", 15000)
                        //-->
                    </script><?
                }
            }
        }
    }
    else {
        // bestaat niet
        echo 'Dit spel is geëindigd.';

        unset($_SESSION['blackjack_multi']);
        ?><script language='JavaScript'>
            <!--
            setTimeout("location.href='index.php?page=multiblackjack'", 15000)
            //-->
        </script><?
    }
}
else
{
    echo "
<table width='100%'>
  <caption>Welkom in de blackjack multiplayer room.<br/><br/></caption>";

    echo "
  <tr align='left'>
    <th>#</th>
    <th>SPELERS</th>
    <th>GESTART</th>
    <th>MEEDOEN</th>
    <th>GEOPEND</th>
  </tr>
		";

    $rSpellen = $db->prepare('SELECT id,player1,player2,player3,player4,gestart,creation FROM `multiblackjack` ORDER BY id DESC');
    $rSpellen->execute();

    while ($aSpel = $rSpellen->fetchObject())
    {
        $aJaOfNee = Array('ja','nee');

        if ($aSpel->gestart > 1 and $aSpel->player1 != $_SESSION['naam'] and $aSpel->player2 != $_SESSION['naam'] and $aSpel->player3 != $_SESSION['naam'] and $aSpel->player4 != $_SESSION['naam'])
        {
            $aSpel->instappen = 'Spel is reeds gestart';
        }
        elseif ($aSpel->gestart > 1 and $aSpel->player1 == $_SESSION['naam'])
        {
            $aSpel->instappen = "<a  href='index.php?page=multiblackjack&stapin=$aSpel->id'>Voortzetten</a>";
        }
        elseif ($aSpel->gestart > 1 and $aSpel->player2 == $_SESSION['naam'])
        {
            $aSpel->instappen = "<a  href='index.php?page=multiblackjack&stapin=$aSpel->id'>Voortzetten</a>";
        }
        elseif ($aSpel->gestart > 1 and $aSpel->player3 == $_SESSION['naam'])
        {
            $aSpel->instappen = "<a  href='index.php?page=multiblackjack&stapin=$aSpel->id'>Voortzetten</a>";
        }
        elseif ($aSpel->gestart > 1 and $aSpel->player4 == $_SESSION['naam'])
        {
            $aSpel->instappen = "<a  href='index.php?page=multiblackjack&stapin=$aSpel->id'>Voortzetten</a>";
        }
        else
        {
            $aSpel->instappen = "<a  href='index.php?page=multiblackjack&stapin=$aSpel->id'>Stap in</a>";
        }

        if ($aSpel->gestart == 0)
        {
            $aSpel->gestart = '<a style="font-weight: bold;color:#008000;">nee</a>';
        }
        else {
            $aSpel->gestart = '<a style="color:darkgrey;">ja</a>';
            
        }
        
        if($aSpel->player1){
            $aSpel->player1 = "<a>".$aSpel->player1."</a>";
        }else{
            $aSpel->player1 = "";
            
        }
        if($aSpel->player2){
            $aSpel->player2 = "<a>, ".$aSpel->player2."</a>";
        }else{
            $aSpel->player2 = "";
            
        }
        if($aSpel->player3){
            $aSpel->player3 = "<a>, ".$aSpel->player3."</a>";
        }else{
            $aSpel->player3 = "";
        }
        if($aSpel->player4){
            $aSpel->player4 = "<a>, ".$aSpel->player4."</a>";
        }else{
            $aSpel->player4 = "";
        }
        
        $openDate = date("d-m-Y", strtotime($aSpel->creation));

        echo "
  <tr>
    <td>$aSpel->id</td>
    <td>$aSpel->player1$aSpel->player2$aSpel->player3$aSpel->player4</td>
    <td>$aSpel->gestart</td>
    <td>$aSpel->instappen</td>
    <td>$openDate</td>
  </tr>
			";
    }

    echo "
  <tr>
    <td colspan='5' style='padding-top:30px;' align='center'>Games die ouder zijn dan twee weken worden automatisch verwijderd</td>
  </tr>
    <tr>
    <td colspan='5' style='padding-top:20px;'><a class='button' href='index.php?page=multiblackjack&stapin=0'>Start een spel</a> (kosten  <img src=\"images/icons/silver.png\" /> 150)</td>
  </tr>
</table>
		";
}
?>