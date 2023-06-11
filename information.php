<?php
$page = 'information';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

#Pagina's opbouwen.
switch ($_GET['category']) {

    #Persoonlijk openen
    case "game-info":

        echo $txt['informationpage'];

        #Persoonlijk sluiten
        break;

    #Wachtwoord openen
    case "pokemon-info":
        ?>

        <script type="text/javascript">
            function show_info(pokemon){
                if(pokemon == "none") $("#pokemon_info").html("<?php echo $txt['choose_a_pokemon']; ?>")
                else if(pokemon == "undefined") $("#pokemon_info").html()
                else if(pokemon!=""){
                    //pokemon = pokemon.replace(" ", "_");
                    $("#pokemon_info").load("ajax_call/pokemon_info.php?page=pokemon-info&pokemon="+pokemon+"")
                }
                else $("#pokemon_info").html()
            }
        </script>
        <center>
            <div style="padding-bottom:14px;">
                <select class="text_select" style="float:none;" id="pokemon" onChange="show_info(this.value)" />
                <option value="none"><?php echo $txt['choosepokemon']; ?></option>
                <?php
                $allpokemonsql = $db->query("SELECT wild_id, naam FROM pokemon_wild ORDER BY naam ASC");
                while($allpokemon = $allpokemonsql->fetch(PDO::FETCH_ASSOC)){
                    $allpokemon['naam_goed'] = computer_naam($allpokemon['naam']);
                    if(isset($_GET['pokemon']) and $_GET['pokemon'] == $allpokemon['wild_id']){
                        echo '<option value="'.$allpokemon['wild_id'].'" selected>'.$allpokemon['naam_goed'].'</option>';
                    }else{
                        echo '<option value="'.$allpokemon['wild_id'].'">'.$allpokemon['naam_goed'].'</option>';
                    }

                }
                ?>
                </select>
            </div>
            <div id="pokemon_info"><?php echo $txt['choose_a_pokemon']; ?></div>
            <? //echo showAlert('blue','Pokemon zoeken is tijdelijk uitgezet.');?>
        </center>

        <?php
        if(isset($_GET['pokemon'])){
            ?><script type="text/javascript">
                show_info(<?=$_GET['pokemon']?>);
            </script>
            <?
        }
        #wachtwoord sluiten
        break;

    #Profiel openen
    case "attack-info":
        ?>

        <center>
            <table width="660" callpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="top_first_td" width="50"><?php echo $txt['#']; ?></td>
                    <td class="top_td" width="160"><?php echo $txt['name']; ?></td>
                    <td class="top_td" width="120"><?php echo $txt['type']; ?></td>
                    <td class="top_td" width="70"><?php echo $txt['att']; ?></td>
                    <td class="top_td" width="70"><?php echo $txt['acc']; ?></td>
                    <td class="top_td" width="130"><?php echo $txt['effect']; ?></td>
                    <td class="top_td" width="60"><?php echo $txt['ready']; ?></td>
                </tr>
                <?php
                #Pagina nummer opvragen
                if(empty($_GET['subpage'])) $subpage = 1;
                else $subpage = $_GET['subpage'];

                #Max aantal leden per pagina
                $max = 50;
                #Aantal attacks
                $aantal_attacks = 600;

                $aantal_paginas = ceil($aantal_attacks/$max);
                $pagina = $subpage*$max-$max;

                $attackquery = $db->query("SELECT naam, sterkte, soort, mis, effect_kans, effect_naam, klaar FROM aanval ORDER BY naam ASC LIMIT ".$pagina.", ".$max."");

                for($number = 1; $attack = $attackquery->fetch(PDO::FETCH_ASSOC); $number ++){

                    $type      = strtolower($attack['soort']);

                    if(($attack['effect_kans'] == '0') OR ($attack['effect_kans'] == '') OR (($attack['effect_naam'] != 'Sleep') AND ($attack['effect_naam'] != 'Paralyzed') AND ($attack['effect_naam'] != 'Poisoned') AND ($attack['effect_naam'] != 'Flinch') AND ($attack['effect_naam'] != 'Burn') AND ($attack['effect_naam'] != 'Freeze') AND ($attack['effect_naam'] != 'Confued'))) $effect = '-';
                    else $effect = $attack['effect_kans'].'% '.$attack['effect_naam'];

                    if($attack['klaar'] == 'ja') $klaar = '<img src="images/icons/green.png">';
                    else $klaar = '<img src="images/icons/red.png">';

                    $accuracy = 100 - $attack['mis'];

                    echo '<tr>
							<td class="normal_first_td">'.$number.'.</td>
							<td class="normal_td">'.$attack['naam'].'</td>
							<td class="normal_td"><table><tr><td><div class="type '.$type.'">'.$type.'</div></td></tr></table></td>
							<td class="normal_td">'.$attack['sterkte'].'</td>
							<td class="normal_td">'.$accuracy.'</td>
							<td class="normal_td">'.$effect.'</td>
							<td class="normal_td">'.$klaar.'</td>
						  </tr>';
                }
                #Pagina systeem
                $links = false;
                $rechts = false;
                echo '<tr><td colspan=7><br /><center><div class="pagination">';
                if($subpage == 1)
                    echo '<span class="disabled"> &lt; </span>';
                else{
                    $back = $subpage-1;
                    echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&category='.$_GET['category'].'&subpage='.$back.'"> &lt; </a>';
                }
                for($i = 1; $i <= $aantal_paginas; $i++) {
                    if((2 >= $i) && ($subpage == $i))
                        echo '<span class="current">'.$i.'</span>';
                    elseif((2 >= $i) && ($subpage != $i))
                        echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&category='.$_GET['category'].'&subpage='.$i.'">'.$i.'</a>';
                    elseif(($aantal_paginas-2 < $i) && ($subpage == $i))
                        echo '<span class="current">'.$i.'</span>';
                    elseif(($aantal_paginas-2 < $i) && ($subpage != $i))
                        echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&category='.$_GET['category'].'&subpage='.$i.'">'.$i.'</a>';
                    else{
                        $max = $subpage+3;
                        $min = $subpage-3;
                        if($subpage == $i)
                            echo '<span class="current">'.$i.'</span>';
                        elseif(($min < $i) && ($max > $i))
                            echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&category='.$_GET['category'].'&subpage='.$i.'">'.$i.'</a>';
                        else{
                            if($i < $subpage){
                                if(!$links){
                                    echo '<span class="disabled">...</span>';
                                    $links = True;
                                }
                            }
                            else{
                                if(!$rechts){
                                    echo '<span class="disabled">...</span>';
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
                    echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'&category='.$_GET['category'].'&subpage='.$next.'"> &gt; </a>';
                }
                echo "</div></center></td></tr>";
                ?>
            </table>
        </center>
        <?php


        break;

    #standaard
    default:
        #Doorsturen naar persoonlijk
        header("Location: ?page=information&category=game-info");
        break;
}
?>