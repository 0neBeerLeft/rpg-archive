<?php
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

$page = 'house';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

#Pokemon tellen die speler in "huis" heeft
$sqlinhuis = $db->query("SELECT COUNT(`id`) AS `aantal` FROM `pokemon_speler` WHERE `user_id`='".$_SESSION['id']."' AND (opzak = 'nee' OR opzak = 'tra')");
$inhuissql = $sqlinhuis->fetch(PDO::FETCH_ASSOC);
$inhuis = $inhuissql['aantal'];

if($gebruiker['huis'] == "doos"){
    $huiss = $txt['box'];
    $linkk = "house1.png";
    $over  = 2-$inhuis;
}
elseif($gebruiker['huis'] == "shuis"){
    $huiss = $txt['little_house'];
    $linkk = "house2.gif";
    $over  = 20-$inhuis;
}
elseif($gebruiker['huis'] == "nhuis"){
    $huiss = $txt['normal_house'];
    $linkk = "house3.gif";
    $over  = 100-$inhuis;
}
elseif(($gebruiker['huis'] == "villa") OR ($gebruiker['huis'] == "Villa")){
    $huiss = $txt['big_house'];
    $linkk = "house4.gif";
    $over  = 500-$inhuis;
}
elseif(($gebruiker['huis'] == "hotel") OR ($gebruiker['huis'] == "Hotel")){
    $huiss = $txt['hotel'];
    $linkk = "house5.gif";
    $over  = 900-$inhuis;
}else{
    $huiss = $txt['box'];
    $linkk = "house1.png";
    $over  = 0-$inhuis;
}

$huisSQL = $db->query("SELECT `ruimte` FROM `huizen` WHERE `afkorting`='".$gebruiker['huis']."'");
$huis = $huisSQL->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['wegbrengen'])){
    #Gegevens laden van geselecteerde pokemon
    $pokemon_wegSQL = $db->query("SELECT `id`, `opzak_nummer`, `ei` FROM `pokemon_speler` WHERE `user_id`='".$_SESSION['id']."' AND `id`='".$_POST['id']."'");
    $pokemon_weg = $pokemon_wegSQL->fetch(PDO::FETCH_ASSOC);
    #Pokemon in huis tellen
    $pokemoninhuis = $db->query("SELECT COUNT(`id`) AS `aantal` FROM `pokemon_speler` WHERE `user_id`='".$_SESSION['id']."' AND (opzak = 'nee' OR opzak = 'tra')");
    $pokemoninhuissql = $pokemoninhuis->fetch(PDO::FETCH_ASSOC);
    $pokemoninhuis = $pokemoninhuissql['aantal'];


    #Kijken als de pokemon wel van de juiste speler is
    $isPlayerPkmSQL = $db->query("SELECT `id` FROM `pokemon_speler` WHERE `user_id`='".$_SESSION['id']."' AND `id`='".$_POST['id']."'");
    if($isPlayerPkmSQL->fetchColumn() == 0)
        echo '<div class="red">'.$txt['alert_not_your_pokemon'].'</div>';
    #als je geen villa hebt en pokemoninhuis is even groot of groter dan je ruimte dan kun je de pokemon niet wegbrengen
    elseif(($gebruiker['huis'] != "Villa") AND ($pokemoninhuis >= $huis['ruimte']))
        echo '<div class="red">'.$txt['alert_house_full'].'</div>';
    else{
        #Pokemon naar huis verplaatsen
        $sendHomeSQL = $db->prepare("UPDATE `pokemon_speler` SET `opzak`='nee', `opzak_nummer`='' WHERE `id`=:pokeId");
        $sendHomeSQL->bindValue(':pokeId', $_POST['id'], PDO::PARAM_INT);
        $sendHomeSQL->execute();
        #weergave op het scherm
        echo '<div class="green">'.$txt['success_bring'].'</div>';
        #pokemons laden die je opzak hebt behalve die je hebt aangeklikt
        $select1 = $db->prepare("SELECT `id`,`opzak_nummer` FROM `pokemon_speler` WHERE `user_id`=:uid AND `id`!=:pokeId AND `opzak`='ja' ORDER BY `opzak_nummer` ASC");
        $select1->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
        $select1->bindValue(':pokeId', $POST['id'], PDO::PARAM_INT);
        $select1->execute();


        for($i = 1; $select = $select1->fetch(PDO::FETCH_ASSOC); $i++){
            #Alle opzak_nummers Ã©Ã©ntje lager maken van alle pokemons die over blijven
            $lowerPokemonInHandCount = $db->prepare("UPDATE `pokemon_speler` SET `opzak_nummer`= :opzakNummer WHERE `id`= :pokemonId ");
            $lowerPokemonInHandCount->bindValue(':opzakNummer', $i, PDO::PARAM_INT);
            $lowerPokemonInHandCount->bindValue(':pokemonId', $select['id'], PDO::PARAM_INT);
            $lowerPokemonInHandCount->execute();

        }
        $over -= 1;
        $gebruiker['in_hand'] -= 1;
    }
}

#Als er op de haal op knop gedrukt word dit in werking zetten
if(isset($_POST['ophalen'])){
    #Aantal pokemon dat opzak is
    if($gebruiker['in_hand'] > 0) $pokemon_sql->fetch(PDO::FETCH_ASSOC);
    $naampokemon = $pokemon_sql->fetch(PDO::FETCH_ASSOC);
    #Is de hand van de speler al vol?
    if($gebruiker['in_hand'] == 6)
        echo '<div class="red">'.$txt['alert_hand_full'].'</div>';
    #Wil de speler een pokemon naar z'n hand brengen die op de transferlijst staat?
    elseif($naampokemon['transferlijst'] == 'ja')
        echo '<div class="red">'.$txt['alert_pokemon_on_transferlist'].'</div>';
    else{
        #Opzak_nummer + 1
        $opzaknummer = $gebruiker['in_hand']+1;
        #Pokemon naar hand verplaatsen
        $pokemonToHand = $db->prepare("UPDATE `pokemon_speler` SET `opzak`='ja', `opzak_nummer`= :opzaknummer WHERE `id` = :pokemonId");
        $pokemonToHand->bindValue(':opzaknummer', $opzaknummer, PDO::PARAM_INT);
        $pokemonToHand->bindValue(':pokemonId', $_POST['id'], PDO::PARAM_INT);
        $pokemonToHand->execute();
        #weergave op het scherm
        echo '<div class="green">'.$txt['success_get'].'</div>';
    }
    $over += 1;
}
?>

<table width="600" cellpadding="0" cellspacing="0">
    <tr>
        <td colspan="5" valign="top">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="26%" rowspan="13"  valign="top">
                        <table width="160" border="0">
                            <tr>
                                <td><center><img src="images/<? echo $linkk; ?>" /></center></td>
                            </tr>
                            <tr>
                                <td><div align="center" style="padding-top:6px;"><strong><? echo $over.' '.$txt['places_over']; ?></strong></div></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?
                #Pagina's opbouwen
                switch ($_GET['option']) {
                    case "bringaway" :
                        echo '<tr>
                    <td width="40" class="top_first_td">'.$txt['#'].'</td>
                    <td width="60" class="top_td">&nbsp;</td>
                    <td width="130" class="top_td">'.$txt['clamour_name'].'</td>
                    <td width="80" class="top_td">'.$txt['level'].'</td>
                    <td width="60" class="top_td">'.$txt['bring_away'].'</td>
                </tr>';


                        if($gebruiker['in_hand'] > 0) {
                            for($i=1; $pokemon = $pokemon_sql->fetch(PDO::FETCH_ASSOC); $i++){
                                $pokemon = pokemonei($pokemon);
                                $pokemon['naam'] = pokemon_naam($pokemon['naam'],$pokemon['roepnaam']);
                                $popup = pokemon_popup($pokemon, $txt);

                                #Default
                                $shinyimg = 'pokemon';
                                $shinystar = '';
                                #Shiny?
                                if($pokemon['shiny'] == "1.5"){
                                    $shinyimg = 'shiny';
                                    $shinystar = '<img src="images/icons/lidbetaald.png" width="16" height="16" style="margin-bottom:-3px;" border="0" alt="Shiny" title="Shiny">';
                                }

                                echo '<tr>
                    <td class="normal_first_td">'.$i.'.</td>
                    <td class="normal_td"><a href="#" class="tooltip" onMouseover="showhint(\''.$popup.'\', this)"><img src="'.$pokemon['animatie'].'" width="32" height="32"/></a></td>
                    <td class="normal_td">'.$pokemon['naam'].$shinystar.'</td>
                    <td class="normal_td">'.$pokemon['level'].'</td>
                    <td class="normal_td">
                      <form method="post">
                        <button type="submit" name="wegbrengen" class="button">'.$txt['button_bring'].'</button>
                        <input type="hidden" name="id" value="'.$pokemon['id'].'">
                      </form>
                    </td>
                  </tr>';
                            }
                            $pokemon_sql->fetch(PDO::FETCH_ASSOC);
                            for ($j=$i; $j<=6; $j++){
                                echo '<tr>
                    <td class="normal_first_td" height="28">'.$j.'.</td>
                    <td class="normal_td"><img src="images/items/Poke ball.png"></td>
                    <td class="normal_td" colspan="4">'.$txt['empty'].'</td>
                </tr>';
                            }
                        }
                        break;

                    case "pickup" :
                        if(empty($_GET['subpage'])) $subpage = 1;
                        else $subpage = $_GET['subpage'];

                        #Max aantal leden per pagina
                        $max = 10;
                        #Pokemon tellen
                        $aantalPkmSQL = $db->prepare("SELECT `id` FROM `pokemon_speler` WHERE `user_id`=:uid AND `opzak`='nee'");
                        $aantalPkmSQL->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
                        $aantalPkmSQL->execute();
                        $aantal_pokemon = $aantalPkmSQL->fetchColumn();

                        $aantal_paginas = ceil($aantal_pokemon/$max);
                        $pagina = $subpage*$max-$max;

                        if($aantal_paginas == 0) $aantal_paginas = 1;
                        #Pokemon laden van de gebruiker die hij niet opzak heeft

                        $poke = $db->prepare("SELECT pokemon_speler.*, `pokemon_wild`.`naam`, `pokemon_wild`.`type1`, `pokemon_wild`.`type2`
                               FROM `pokemon_speler`
                               INNER JOIN `pokemon_wild`
                               ON `pokemon_speler`.`wild_id` = `pokemon_wild`.`wild_id`
                               WHERE `pokemon_speler`.`user_id`=:uid AND `pokemon_speler`.`opzak`='nee' ORDER BY `pokemon_speler`.`wild_id` ASC LIMIT :page, :max");
                        $poke->bindParam(':uid', $_SESSION['id'], PDO::PARAM_INT);
                        $poke->bindValue(':page', $pagina, PDO::PARAM_INT);
                        $poke->bindValue(':max', $max, PDO::PARAM_INT);

                        $poke->execute();


                        echo '<tr>
                  <td width="40" class="top_first_td">'.$txt['#'].'</td>
                  <td width="60" class="top_td">&nbsp;</td>
                  <td width="130" class="top_td">'.$txt['clamour_name'].'</td>
                  <td width="80" class="top_td">'.$txt['level'].'</td>
                  <td width="60" class="top_td">'.$txt['take'].'</td>
                </tr>';
                        if($aantal_pokemon == 0){
                            echo '<tr>
                                <td colspan="5" class="normal_first_td">Er zijn geen pokemon in je huis.</td>
                              </tr>';
                        }
                        else{
                            for($i=$pagina+1; $pokemon = $poke->fetch(PDO::FETCH_ASSOC); $i++){
                                $pokemon = pokemonei($pokemon);
                                $pokemon['naam'] = pokemon_naam($pokemon['naam'],$pokemon['roepnaam']);
                                $popup = pokemon_popup($pokemon, $txt);
                                #Default
                                $shinyimg = 'pokemon';
                                $shinystar = '';
                                #Shiny?
                                if($pokemon['shiny'] == 1){
                                    $shinyimg = 'shiny';
                                    $shinystar = '<img src="images/icons/lidbetaald.png" width="16" height="16" style="margin-bottom:-3px;" border="0" alt="Shiny" title="Shiny">';
                                }

                                echo '<tr>
                  <td class="normal_first_td">'.$i.'.</td>
                  <td class="normal_td"><a href="#" class="tooltip" onMouseover="showhint(\''.$popup.'\', this)"><img src="'.$pokemon['animatie'].'" width="32" height="32" /></a></td>
                  <td class="normal_td">'.$pokemon['naam'].$shinystar.'</td>
                  <td class="normal_td">'.$pokemon['level'].'</td>
                  <td class="normal_td"><form method="post">
                  <div><button type="submit" name="ophalen" class="button">'.$txt['button_take'].'</button></div>
                  <input type="hidden" name="id" value="'.$pokemon['id'].'"></form></td>
                  </tr>';
                            }
                        }
                        #Pagina systeem
                        $links = false;
                        $rechts = false;
                        echo '<tr><td colspan=5><br /><center><div class="sabrosus">';
                        if($subpage == 1)
                            echo '<span class="disabled"> &lt; </span>';
                        else{
                            $back = $subpage-1;
                            echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&option=pickup&subpage='.$back.'"> &lt; </a>';
                        }
                        for($i = 1; $i <= $aantal_paginas; $i++){
                            if((2 >= $i) && ($subpage == $i))
                                echo '<span class="current">'.$i.'</span>';
                            elseif((2 >= $i) && ($subpage != $i))
                                echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&option=pickup&subpage='.$i.'">'.$i.'</a>';
                            elseif(($aantal_paginas-2 < $i) && ($subpage == $i))
                                echo '<span class="current">'.$i.'</span>';
                            elseif(($aantal_paginas-2 < $i) && ($subpage != $i))
                                echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&option=pickup&subpage='.$i.'">'.$i.'</a>';
                            else{
                                $max = $subpage+3;
                                $min = $subpage-3;
                                if($subpage == $i)
                                    echo '<span class="current">'.$i.'</span>';
                                elseif(($min < $i) && ($max > $i))
                                    echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&option=pickup&subpage='.$i.'">'.$i.'</a>';
                                else{
                                    if($i < $subpage){
                                        if(!$links){
                                            echo '...';
                                            $links = True;
                                        }
                                    }
                                    else{
                                        if(!$rechts){
                                            echo '...';
                                            $rechts = True;
                                        }
                                    }
                                }
                            }
                        }
                        if($aantal_paginas == $subpage)
                            echo '<span class="disabled"> &gt; </span>';
                        else{
                            $next = $subpage+1;
                            echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&option=pickup&subpage='.$next.'"> &gt; </a>';
                        }
                        echo "</div></center></td>";

                        break;

                    default:
                        echo '
              <td height="21" colspan="5"  valign="Top">
              <div style="padding-left:4px;">
              '.$txt['title_text_1'].' '.$huiss.', '.$txt['title_text_2'].' '.$huis['ruimte'].' '.$txt['title_text_3'].'
              </div>
              </td>
            ';
                        break;
                }
                ?>
            </table>
        </td>
    </tr>
</table>