<?php

#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

#Admin controle
if($gebruiker['admin'] < 3){ header('location: index.php?page=home'); }

if($_POST['submit']){

    mysql_query("UPDATE settings SET value = '".$_POST['destroySession']."' WHERE setting = 'destroySession'");
    mysql_query("UPDATE settings SET value = '".$_POST['showMaintenance']."' WHERE setting = 'showMaintenance'");
    mysql_query("UPDATE settings SET value = '".$_POST['maintenanceMessage']."' WHERE setting = 'maintenanceMessage'");
    mysql_query("UPDATE settings SET value = '".$_POST['showExitBattle']."' WHERE setting = 'showExitBattle'");
    mysql_query("UPDATE settings SET value = '".$_POST['kansUitsluitingen']."' WHERE setting = 'kansUitsluitingen'");

}
$destroySession = mysql_fetch_assoc(mysql_query("SELECT * FROM settings WHERE setting = 'destroySession' "));
$showMaintenance = mysql_fetch_assoc(mysql_query("SELECT * FROM settings WHERE setting = 'showMaintenance' "));
$maintenanceMessage = mysql_fetch_assoc(mysql_query("SELECT * FROM settings WHERE setting = 'maintenanceMessage' "));
$showExitBattle = mysql_fetch_assoc(mysql_query("SELECT * FROM settings WHERE setting = 'showExitBattle' "));
$kansUitsluitingen = mysql_fetch_assoc(mysql_query("SELECT * FROM settings WHERE setting = 'kansUitsluitingen' "));
?>

<form method="post">
    <center>
        <table width="100%" style="border: 1px solid #000;">
            <tr>
                <td width="80"><strong>destroySession:</strong></td>
                <td ><input type="text" name="destroySession" class="text_long" style="width: 95%;" value="<?=$destroySession['value']?>" /></td>
            </tr>
            <tr>
                <td width="80"><strong>showMaintenance:</strong></td>
                <td ><input type="text" name="showMaintenance" class="text_long" style="width: 95%;" value="<?=$showMaintenance['value']?>" /></td>
            </tr>
            <tr>
                <td width="80"><strong>maintenanceMessage:</strong></td>
                <td ><input type="text" name="maintenanceMessage" class="text_long" style="width: 95%;" value="<?=$maintenanceMessage['value']?>" /></td>
            </tr>
            <tr>
                <td width="80"><strong>showExitBattle:</strong></td>
                <td ><input type="text" name="showExit" class="text_long" style="width: 95%;" value="<?=$showExitBattle['value']?>" /></td>
            </tr>
            <tr>
                <td width="80"><strong>Megastone kansUitsluitingen:</strong></td>
                <td ><input type="text" name="kansUitsluitingen" class="text_long" style="width: 95%;" value="<?=$kansUitsluitingen['value']?>" /></td>
            </tr>
            <tr>
                <td colspan="2" align="right"><input type="submit" name="submit" class="button" value="Opslaan"/></td>
            </tr>
        </table>
    </center>
</form>
