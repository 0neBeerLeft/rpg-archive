<?php

#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

#Admin controle
if($gebruiker['admin'] < 3){ header('location: index.php?page=home'); }

$datum = date('Y-m-d H:i:s');

if(isset($_POST['submit'])) {

    if($_POST['security'] == 'abxgo') {

        #Alle items weg!
        $gebruikers_item = mysql_query("UPDATE `gebruikers_item` SET `itembox`='Bag', `Badge case`='', `Poke ball`='', `Great ball`='', `Ultra ball`='', `Premier ball`='', `Net ball`='', `Dive ball`='', `Nest ball`='', 
        `Repeat ball`='', `Timer ball`='', `Master ball`='', `Potion`='', `Super potion`='', `Hyper potion`='', `Full heal`='', `Revive`='', `Max revive`='', `Pokedex`='', `Pokedex chip`='', `Pokedex zzchip`='', 
        `Fishing rod`='', `Cave suit`='', `Protein`='', `Iron`='', `Carbos`='', `Calcium`='', `HP up`='', `Rare candy`='', `Duskstone`='', `Firestone`='', `Leafstone`='', `Moonstone`='', `Ovalstone`='', 
        `Shinystone`='', `Sunstone`='', `Thunderstone`='', `Waterstone`='', `Dawnstone`='', `Darkball`='',`Abomasite`='', `Absolite`='', `Aerodactylite`='', `Aggronite`='', `Alakazite`='', `Altarianite`='', 
        `Ampharosite`='', `Audinite`='', `Banettite`='', `Beedrillite`='', `Blastoisinite`='', `Blazikenite`='', `Cameruptite`='', `Charizardite X`='', `Charizardite Y`='', `Diancite`='', `Galladite`='', 
        `Garchompite`='', `Gardevoirite`='', `Gengarite`='', `Glalitite`='', `Gyaradosite`='', `Heracronite`='', `Houndoominite`='', `Kangaskhanite`='', `Latiasite`='', `Latiosite`='', `Lopunnite`='', 
        `Lucarionite`='', `Manectite`='', `Mawilite`='', `Medichamite`='', `Metagrossite`='', `Mewtwonite X`='', `Mewtwonite Y`='', `Pidgeotite`='', `Pinsirite`='', `Sablenite`='', `Salamencite`='', 
        `Sceptilite`='', `Scizorite`='', `Sharpedonite`='', `Slowbronite`='', `Steelixite`='', `Swampertite`='', `Tyranitarite`='', `Venusaurite`=''");
        if ($gebruikers_item) {
            echo showAlert('green', 'gebruikers_item succesvol geleegd.');
        } else {
            echo showAlert('red', 'gebruikers_item fout gegaan.');
        }

        #Alle badges weg!
        $badges = mysql_query("UPDATE `gebruikers_badges` SET 
        `Boulder`='0', `Cascade`='0', `Thunder`='0', `Rainbow`='0', `Marsh`='0', `Soul`='0', `Volcano`='0', `Earth`='0', `Zephyr`='0', `Hive`='0', `Plain`='0', `Fog`='0', `Storm`='0', `Mineral`='0', 
        `Glacier`='0', `Rising`='0', `Stone`='0', `Knuckle`='0', `Dynamo`='0', `Heat`='0', `Balance`='0', `Feather`='0', `Mind`='0', `Rain`='0', `Coal`='0', `Forest`='0', `Cobble`='0', `Fen`='0', 
        `Relic`='0', `Mine`='0', `Icicle`='0', `Beacon`='0', `Trio`='0', `Basic`='0', `Insect`='0', `Bolt`='0', `Quake`='0', `Jet`='0', `Freeze`='0', `Legend`='0',
        `Bug`='0', `Cliff`='0', `Rumble`='0', `Plant`='0', `Voltage`='0', `Fairy`='0', `Psychic`='0', `Iceberg`='0'");
        if ($badges) {
            echo showAlert('green', 'gebruikers_badges succesvol geleegd.');
        } else {
            echo showAlert('red', 'gebruikers_badges fout gegaan.');
        }

        #Alle tmhm weg!
        $gebruikers_tmhm = mysql_query("UPDATE `gebruikers_tmhm` SET `TM01`='0', `TM02`='0', `TM03`='0', `TM04`='0', `TM05`='0', `TM06`='0', `TM07`='0', `TM08`='0', `TM09`='0', `TM10`='0', 
        `TM11`='0', `TM12`='0', `TM13`='0', `TM14`='0', `TM15`='0', `TM16`='0', `TM17`='0', `TM18`='0', `TM19`='0', `TM20`='0', `TM21`='0', `TM22`='0', `TM23`='0', 
        `TM24`='0', `TM25`='0', `TM26`='0', `TM27`='0', `TM28`='0', `TM29`='0', `TM30`='0', `TM31`='0', `TM32`='0', `TM33`='0', `TM34`='0', `TM35`='0', `TM36`='0', 
        `TM37`='0', `TM38`='0', `TM39`='0', `TM40`='0', `TM41`='0', `TM41`='0', `TM42`='0', `TM43`='', `TM44`='0', `TM45`='0', `TM46`='0', `TM47`='0', `TM48`='0', 
        `TM49`='0', `TM50`='0', `TM51`='0', `TM52`='0', `TM53`='0', `TM54`='0', `TM55`='0', `TM56`='0', `TM57`='0', `TM58`='0', `TM59`='0', `TM60`='0', `TM61`='0', 
        `TM62`='0', `TM63`='0', `TM64`='0', `TM65`='0', `TM66`='0', `TM67`='0', `TM68`='0', `TM69`='0', `TM70`='0', `TM71`='0', `TM72`='0', `TM73`='0', `TM74`='0', 
        `TM75`='0', `TM76`='0', `TM77`='0', `TM78`='0', `TM79`='0', `TM80`='0', `TM81`='0', `TM82`='0', `TM83`='0', `TM84`='0', `TM85`='0', `TM86`='', `TM87`='0', 
        `TM88`='0', `TM89`='0', `TM90`='0', `TM91`='0', `TM92`='0', `HM01`='0', `HM02`='0', `HM03`='0', `HM04`='0', `HM05`='0', `HM06`='0', `HM07`='0', `HM08`='0'");
        if ($gebruikers_tmhm) {
            echo showAlert('green', 'gebruikers_tmhm succesvol geleegd.');
        } else {
            echo showAlert('red', 'gebruikers_tmhm fout gegaan.');
        }

        #Veel gebruikers dingen weg!
        $gebruikers = mysql_query("UPDATE `gebruikers` 
        SET `wereld`='', 
        `views`='0',
        `silver`='75', 
        `bank`='', 
        `storten`='3', 
        `huis`='doos', 
        `geluksrad`='1', 
        `rank`='1', 
        `rankexp`='0', 
        `rankexpnodig`='245', 
        `aantalpokemon`='0', 
        `badges`='0', 
        `captcha_tevaak_fout`='0', 
        `werkervaring`='0', 
        `gewonnen`='0', 
        `verloren`='0', 
        `eigekregen`='0', 
        `lvl_choose`='', 
        `wiequiz`='0000-00-00 00:00:00', 
        `werktijd`='0', 
        `pokecentertijd`='0', 
        `gevangenistijd`='0', 
        `geluksrad`='3', 
        `hasStore`='0',
        `races_winst`='3', 
        `races_verlies`='3', 
        `pok_gezien`='', 
        `pok_bezit`='',
        `missie_1`='0',
        `missie_2`='0',
        `missie_3`='0',
        `missie_4`='0',
        `missie_5`='0',
        `missie_6`='0',
        `missie_7`='0',
        `missie_8`='0',
        `missie_9`='0',
        `missie_10`='0',
        `clan`='0',
        `respect`='0',
        `referer`='0',
        `pok_gehad`=''");
        if ($gebruikers) {
            echo showAlert('green', 'gebruikers succesvol geleegd.');
        } else {
            echo showAlert('red', 'gebruikers fout gegaan.');
        }

        #Alle Pokemons weg!
        $pokemon_speler = mysql_query("TRUNCATE TABLE `pokemon_speler`");
        if ($pokemon_speler) {
            echo showAlert('green', 'pokemon_speler succesvol geleegd.');
        } else {
            echo showAlert('red', 'pokemon_speler fout gegaan.');
        }
        
        #Alle Storegegevens weg!
        $gebruikers_store = mysql_query("TRUNCATE TABLE `gebruikers_store`");
        if ($gebruikers_store) {
            echo showAlert('green', 'gebruikers_store succesvol geleegd.');
        } else {
            echo showAlert('red', 'gebruikers_store fout gegaan.');
        }

        #Alle Pokemon gevechten weg!
        $pokemon_speler_gevecht = mysql_query("TRUNCATE TABLE `pokemon_speler_gevecht`");
        if ($pokemon_speler_gevecht) {
            echo showAlert('green', 'pokemon_speler_gevecht succesvol geleegd.');
        } else {
            echo showAlert('red', 'pokemon_speler_gevecht fout gegaan.');
        }

        #Transferlijst Leeg!
        $transferlijst = mysql_query("TRUNCATE TABLE `transferlijst`");
        if ($transferlijst) {
            echo showAlert('green', 'transferlijst succesvol geleegd.');
        } else {
            echo showAlert('red', 'transferlijst fout gegaan.');
        }

        #transferlist_log Leeg!
        $transferlist_log = mysql_query("TRUNCATE TABLE `transferlist_log`");
        if ($transferlist_log) {
            echo showAlert('green', 'transferlist_log succesvol geleegd.');
        } else {
            echo showAlert('red', 'transferlist_log fout gegaan.');
        }

        #trade_biedingen Leeg!
        $trade_biedingen = mysql_query("TRUNCATE TABLE `trade_biedingen`");
        if ($trade_biedingen) {
            echo showAlert('green', 'trade_biedingen succesvol geleegd.');
        } else {
            echo showAlert('red', 'trade_biedingen fout gegaan.');
        }

        #trade_center Leeg!
        $trade_center = mysql_query("TRUNCATE TABLE `trade_center`");
        if ($trade_center) {
            echo showAlert('green', 'trade_center succesvol geleegd.');
        } else {
            echo showAlert('red', 'trade_center fout gegaan.');
        }

        #Daycare Leeg!
        $daycare = mysql_query("TRUNCATE TABLE `daycare`");
        if ($daycare) {
            echo showAlert('green', 'daycare succesvol geleegd.');
        } else {
            echo showAlert('red', 'daycare fout gegaan.');
        }

        #Gebeurtenissen leeg!
        $gebeurtenissen = mysql_query("TRUNCATE TABLE `gebeurtenis`");
        if ($gebeurtenissen) {
            echo showAlert('green', 'gebeurtenis succesvol geleegd.');
        } else {
            echo showAlert('red', 'gebeurtenis fout gegaan.');
        }

        #ban leeg!
        $ban = mysql_query("TRUNCATE TABLE `ban`");
        if ($ban) {
            echo showAlert('green', 'ban succesvol geleegd.');
        } else {
            echo showAlert('red', 'ban fout gegaan.');
        }

        #bank_logs leeg!
        $bank_logs = mysql_query("TRUNCATE TABLE `bank_logs`");
        if ($bank_logs) {
            echo showAlert('green', 'bank_logs succesvol geleegd.');
        } else {
            echo showAlert('red', 'bank_logs fout gegaan.');
        }

        #berichten leeg!
        $berichten = mysql_query("TRUNCATE TABLE `berichten`");
        if ($berichten) {
            echo showAlert('green', 'berichten succesvol geleegd.');
        } else {
            echo showAlert('red', 'berichten fout gegaan.');
        }

        #clans leeg!
        $clans = mysql_query("TRUNCATE TABLE `clans`");
        if ($clans) {
            echo showAlert('green', 'clans succesvol geleegd.');
        } else {
            echo showAlert('red', 'clans fout gegaan.');
        }

        #clan_invites leeg!
        $clan_invites = mysql_query("TRUNCATE TABLE `clan_invites`");
        if ($clan_invites) {
            echo showAlert('green', 'clan_invites succesvol geleegd.');
        } else {
            echo showAlert('red', 'clan_invites fout gegaan.');
        }

        #duel_logs leeg!
        $duel_logs = mysql_query("TRUNCATE TABLE `duel_logs`");
        if ($duel_logs) {
            echo showAlert('green', 'duel_logs succesvol geleegd.');
        } else {
            echo showAlert('red', 'duel_logs fout gegaan.');
        }

        #multiblackjack leeg!
        $multiblackjack = mysql_query("TRUNCATE TABLE `multiblackjack`");
        if ($multiblackjack) {
            echo showAlert('green', 'multiblackjack succesvol geleegd.');
        } else {
            echo showAlert('red', 'multiblackjack fout gegaan.');
        }

        #premium_gekocht leeg!
        $premium_gekocht = mysql_query("TRUNCATE TABLE `premium_gekocht`");
        if ($premium_gekocht) {
            echo showAlert('green', 'premium_gekocht succesvol geleegd.');
        } else {
            echo showAlert('red', 'premium_gekocht fout gegaan.');
        }

        #referer_logs leeg!
        $referer_logs = mysql_query("TRUNCATE TABLE `referer_logs`");
        if ($referer_logs) {
            echo showAlert('green', 'referer_logs succesvol geleegd.');
        } else {
            echo showAlert('red', 'referer_logs fout gegaan.');
        }

        #shoutbox leeg!
        $shoutbox = mysql_query("TRUNCATE TABLE `shoutbox`");
        if ($shoutbox) {
            echo showAlert('green', 'shoutbox succesvol geleegd.');
        } else {
            echo showAlert('red', 'shoutbox fout gegaan.');
        }
    } else {
        echo showAlert('red', 'Vul de controle in.');
    }
}

?>


<form method="post">
    <center>
    <input name="security" class="text_long" value="" placeholder="Controle">
    <br/>
    <br/>
    <button class="button" type="submit" name="submit">Reset het spel</button>
    </center>
</form>

