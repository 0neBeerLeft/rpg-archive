<?
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

$page = 'wereld';
#Goeie taal erbij laden voor de page
include('language/language-pages.php');

#Heeft de speler nog geen wereld, dan pagina zien
if ($gebruiker['wereld'] == '') {
    #Als er op de knop gedrukt is de keuze includen
    
    if ((isset($_POST['verder']))) {
        
        $wereld = null;
        
        if($_POST['wereld'] == 'Kanto'){
            $wereld = 'Kanto';
        }
        if($_POST['wereld'] == 'Johto'){
            $wereld = 'Johto';
        }
        if($_POST['wereld'] == 'Hoenn'){
            $wereld = 'Hoenn';
        }
        if($_POST['wereld'] == 'Sinnoh'){
            $wereld = 'Sinnoh';
        }
        if($_POST['wereld'] == 'Unova'){
            $wereld = 'Unova';
        }
        if($_POST['wereld'] == 'Kalos'){
            $wereld = 'Kalos';
        }
        
        if($wereld){
        
            mysql_query("UPDATE `gebruikers` SET `wereld`='".$wereld."' WHERE `user_id`='".$_SESSION['id']."'");
            $_SESSION['eikeuze'] = 1;
            echo showAlert('green','Je hebt <b>'.$wereld.'</b> gekozen als starter wereld je wordt nu doorgestuurd...'); refresh(3, "?page=beginning");
        }else{
            echo showAlert('red','Kies een wereld voor je starter.'); refresh(3, "?page=wereld");
        }

    } #Anders de pagina laten zien
    else {
        ?>
        <center>
            <form method="post" action="?page=wereld">
                <table width="600" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="380" valign="top">
                            <center>
                            <h2>Kies je starter wereld</h2>
                            <img src="images/globe.png" alt="<?=GLOBALDEF_SITENAME?>" title="<?=GLOBALDEF_SITENAME?>">
                            </center>
                        </td>
                        <td width="380" valign="top">
                            <p>
                                <input type="radio" name="wereld" value="Kanto" id="Kanto"> <label for="Kanto">Kanto</label> <br/>
                                <input type="radio" name="wereld" value="Johto" id="Johto"> <label for="johto">Johto</label><br/>
                                <input type="radio" name="wereld" value="Hoenn" id="Hoenn"> <label for="Hoenn">Hoenn</label><br/>
                                <input type="radio" name="wereld" value="Sinnoh" id="Sinnoh"> <label for="Sinnoh">Sinnoh</label><br/>
                                <input type="radio" name="wereld" value="Unova" id="Unova"> <label for="Unova">Unova</label><br/>
                                <input type="radio" name="wereld" value="Kalos" id="Kalos"> <label for="Kalos">Kalos</label>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td width="380" valign="top" colspan="2">
                                <button type='submit' name='verder' class='button pull-right'>Ga verder</button>
                        </td>
                    </tr>
                </table>
            </form>
        </center>
        <?
    }#Pagina wel of niet laten zien
} #Speler heeft al ei gehad. Terug naar home
else {
    header("Location: index.php?page=home");
}
?>