<?
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

$page = 'travel';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

#prijzen vaststellen
$prijs['kanto'] = 5;
$prijs['kantototaal'] = $prijs['kanto']*$gebruiker['aantalpokemon'];
$prijs['johto'] = 10;
$prijs['johtototaal'] = $prijs['johto']*$gebruiker['aantalpokemon'];
$prijs['hoenn'] = 15;
$prijs['hoenntotaal'] = $prijs['hoenn']*$gebruiker['aantalpokemon'];
$prijs['sinnoh'] = 20;
$prijs['sinnohtotaal'] = $prijs['sinnoh']*$gebruiker['aantalpokemon'];
$prijs['unova'] = 50;
$prijs['unovatotaal'] = $prijs['unova']*$gebruiker['aantalpokemon'];
$prijs['kalos'] = 60;
$prijs['kalostotaal'] = $prijs['kalos']*$gebruiker['aantalpokemon'];


#Als er op de knop gedrukt word
if(isset($_POST['travel'])){
  $wereld = $_POST['wereld'];
  $prijss = $prijs[$wereld.'totaal'];
  $prijsmooi = highamount($prijss);
  
  #De eerste letter verandere in hoofdletter
  $wereld = ucfirst($wereld);
  if(empty($wereld))
	  $travelerror = '<div class="red">'.$txt['alert_no_world'].'</div>';
  #Zit de speler al in deze wereld?
  elseif($gebruiker['wereld'] == $wereld)
    $travelerror = '<div class="red">'.$txt['alert_already_in_world'].'</div>';
  #Bestaat de wereld wel?
  elseif($wereld != 'Kanto' && $wereld != 'Johto' && $wereld != 'Hoenn' && $wereld != 'Sinnoh' && $wereld != 'Unova' && $wereld != 'Kalos')
  		$travelerror = '<div class="red">'.$txt['alert_world_invalid'].'</div>';
  
  else{
	#Heeft de speler wel genoeg silver?
    if($gebruiker['silver'] <= $prijss)
      $travelerror = '<div class="red">'.$txt['alert_not_enough_money'].'</div>';
    else{ #Speler heeft genoeg silver.
      #silver minderen en nieuwe wereld opslaan
      $payAndMove = $db->prepare("UPDATE `gebruikers` SET `silver`=`silver`-:price, `wereld`=:world WHERE `user_id`=:uid");
      $payAndMove->bindValue(':price', $prijss, PDO::PARAM_INT);
      $payAndMove->bindValue(':world', $wereld, PDO::PARAM_STR);
      $payAndMove->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
      $payAndMove->execute();

      $travelerror = '<div class="green">'.$txt['success_travel'].' <img src="images/icons/silver.png" title="Silver"> '.$prijsmooi.'</div>';
        //complete mission 3
        if($gebruiker['missie_3'] == 0){
          $completeMission3 = $db->prepare("UPDATE `gebruikers` SET `missie_3`=1, `silver`=`silver`+750,`rankexp`=rankexp+50 WHERE `user_id`=:uid");
          $completeMission3->bindValue(':uid', $gebruiker['user_id'], PDO::PARAM_INT);
          $completeMission3->execute();

          echo showToastr("info", "Je hebt een missie behaald!");
        }
    }
  }
}

##########SURF

#Als er op de knop gedrukt word
if(isset($_POST['surf'])){
	if($_POST['wereld'] == '' || $_POST['pokemonid'] == ''){
		$surferror = '<div class="red">'.$txt['alert_not_everything_selected'].'</div>';
	}
	else{
	  #query voor alle info

    $pkmninfoSQL = $db->prepare("SELECT id, user_id, level, aanval_1, aanval_2, aanval_3, aanval_4 FROM pokemon_speler WHERE id = :pokeId");
    $pkmninfoSQL->bindValue(':pokeId', $_POST['pokemonid'], PDO::PARAM_INT);
    $pkmninfo = $pkmninfoSQL->fetch(PDO::FETCH_ASSOC);

 	  #De eerste letter verandere in hoofdletter
	  $wereld = $_POST['wereld'];
	  $wereld = ucfirst($wereld);
	  #eigenaar check
	  if($pkmninfo['user_id'] != $_SESSION['id'])
			$surferror = ' <div class="red">'.$txt['alert_not_your_pokemon'].'</div>';
	  #Bestaat de wereld wel?
	  elseif($wereld != 'Kanto' && $wereld != 'Johto' && $wereld != 'Hoenn' && $wereld != 'Sinnoh' && $wereld != 'Unova' && $wereld != 'Kalos')
			$surferror = '<div class="red">'.$txt['alert_world_invalid'].'</div>';
	  #Zit de speler al in deze wereld?
	  elseif($gebruiker['wereld'] == $wereld)
			$surferror = '<div class="red">'.$txt['alert_already_in_world'].'</div>';
	  #KIjken of pokemon de aanval wel heeft
	  elseif($pkmninfo['aanval_1'] != 'Surf' && $pkmninfo['aanval_2'] != 'Surf' && $pkmninfo['aanval_3'] != 'Surf' && $pkmninfo['aanval_4'] != 'Surf')
			$surferror = '<div class="red">'.$txt['alert_no_surf'].'</div>';
	  #Kijken of de pokemon level 80 is
	  elseif($pkmninfo['level'] < 80)
			$surferror = '<div class="red">'.$txt['alert_not_strong_enough'].'</div>';
	  #Alles goed:
	  else{
      $updateWorld = $db->prepare("UPDATE `gebruikers` SET `wereld`='".$wereld."' WHERE `user_id`=:uid");
      $updateWorld->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
      $updateWorld->execute();

		  $travelerror = '<div class="green">'.$txt['success_surf'].'</div>';
	  }
	}
}


##########SURF

#Als er op de knop gedrukt word
if(isset($_POST['fly'])){
	if($_POST['wereld'] == '' || $_POST['pokemonid'] == ''){
		$flyerror = '<div class="red">'.$txt['alert_not_everything_selected'].'</div>';
	}
	else{
	  #query voor alle info
    $pkmninfoSQL = $db->prepare("SELECT id, user_id, level, aanval_1, aanval_2, aanval_3, aanval_4 FROM pokemon_speler WHERE id = :pokeId");
    $pkmninfoSQL->bindValue(':pokeId', $_POST['pokemonid'], PDO::PARAM_INT);
    $pkmninfoSQL->execute();
    $pkmninfo = $pkmninfoSQL->fetch(PDO::FETCH_ASSOC);

	  #De eerste letter verandere in hoofdletter
	  $wereld = $_POST['wereld'];
	  $wereld = ucfirst($wereld);
	  #eigenaar check
	  if($pkmninfo['user_id'] != $_SESSION['id'])
			$flyerror = ' <div class="red">'.$txt['alert_not_your_pokemon'].'</div>';
	  #Bestaat de wereld wel?
	  elseif($wereld != 'Kanto' && $wereld != 'Johto' && $wereld != 'Hoenn' && $wereld != 'Sinnoh' && $wereld != 'Unova' && $wereld != 'Kalos')
			$flyerror = '<div class="red">'.$txt['alert_world_invalid'].'</div>';
	  #Zit de speler al in deze wereld?
	  elseif($gebruiker['wereld'] == $wereld)
			$flyerror = '<div class="red">'.$txt['alert_already_in_world'].'</div>';
	  #KIjken of pokemon de aanval wel heeft
	  elseif($pkmninfo['aanval_1'] != 'Fly' && $pkmninfo['aanval_2'] != 'Fly' && $pkmninfo['aanval_3'] != 'Fly' && $pkmninfo['aanval_4'] != 'Fly')
			$flyerror = '<div class="red">'.$txt['alert_no_fly'].'</div>';
	  #Kijken of de pokemon level 80 is
	  elseif($pkmninfo['level'] < 80)
			$flyerror = '<div class="red">'.$txt['alert_not_strong_enough'].'</div>';
	  #Alles goed:
	  else{
      $updateWorld = $db->prepare("UPDATE `gebruikers` SET `wereld`='".$wereld."' WHERE `user_id`=:uid");
      $updateWorld->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
      $updateWorld->execute();

		  $travelerror = '<div class="green">'.$txt['success_fly'].'</div>';
	  }
	}
}





if($travelerror) echo $travelerror; ?>
<center>
  <?php echo $txt['title_text']; ?>
  <br/><br/>
  <form method="post">
    <table width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td width="25%" rowspan="6"><img src="images/boat.gif" /></td>
        <td width="5%" class="top_td"><?php echo $txt['#']; ?></td>
        <td width="20%" class="top_td"><?php echo $txt['world']; ?></td>
        <td width="30%" class="top_td"><?php echo $txt['price']; ?></td>
        <td width="20%" class="top_td"><?php echo $txt['price_total']; ?></td>
      </tr>
      <?
      if($gebruiker['wereld'] != "Kanto") {
        ?>
        <tr>
          <td class="normal_td"><label for="travelkanto"><input type="radio" name="wereld" value="kanto" id="travelkanto"></label></td>
          <td class="normal_td"><label for="travelkanto">Kanto</label></td>
          <td class="normal_td"><img src="images/icons/silver.png" title="Silver"> <? echo $prijs['kanto']; ?></td>
          <td class="normal_td"><img src="images/icons/silver.png" title="Silver"> <? echo $prijs['kantototaal']; ?></td>
        </tr>
        <?
      }
      if($gebruiker['wereld'] != "Johto") {
        ?>
        <tr>
          <td class="normal_td"><label for="traveljohto"><input type="radio" name="wereld" value="johto" id="traveljohto"></label></td>
          <td class="normal_td"><label for="traveljohto">Johto</label></td>
          <td class="normal_td"><img src="images/icons/silver.png" title="Silver"> <? echo $prijs['johto']; ?></td>
          <td class="normal_td"><img src="images/icons/silver.png" title="Silver"> <? echo $prijs['johtototaal']; ?></td>
        </tr>
        <?
      }
      if($gebruiker['wereld'] != "Hoenn") {
        ?>
        <tr>
          <td class="normal_td"><label for="travelhoenn"><input type="radio" name="wereld" value="hoenn" id="travelhoenn"></label></td>
          <td class="normal_td"><label for="travelhoenn">Hoenn</label></td>
          <td class="normal_td"><img src="images/icons/silver.png" title="Silver"> <? echo $prijs['hoenn']; ?></td>
          <td class="normal_td"><img src="images/icons/silver.png" title="Silver"> <? echo $prijs['hoenntotaal']; ?></td>
        </tr>
        <?
      }
      if($gebruiker['wereld'] != "Sinnoh") {
        ?>
        <tr>
          <td class="normal_td"><label for="travelsinnoh"><input type="radio" name="wereld" value="sinnoh" id="travelsinnoh"></label></td>
          <td class="normal_td"><label for="travelsinnoh">Sinnoh</label></td>
          <td class="normal_td"><img src="images/icons/silver.png" title="Silver"> <? echo $prijs['sinnoh']; ?></td>
          <td class="normal_td"><img src="images/icons/silver.png" title="Silver"> <? echo $prijs['sinnohtotaal']; ?></td>
        </tr>
        <?
      }
      if($gebruiker['wereld'] != "Unova") {
        ?>
        <tr>
          <td class="normal_td"><label for="travelunova"><input type="radio" name="wereld" value="unova" id="travelunova"></label></td>
          <td class="normal_td"><label for="travelunova">Unova</label></td>
          <td class="normal_td"><img src="images/icons/silver.png" title="Silver"> <? echo $prijs['unova']; ?></td>
          <td class="normal_td"><img src="images/icons/silver.png" title="Silver"> <? echo $prijs['unovatotaal']; ?></td>
        </tr>
        <?
      }
      if($gebruiker['wereld'] != "Kalos") {
        ?>
        <tr>
          <td class="normal_td"><label for="travelkalos"><input type="radio" name="wereld" value="kalos" id="travelkalos"></label></td>
          <td class="normal_td"><label for="travelkalos">Kalos</label></td>
          <td class="normal_td"><img src="images/icons/silver.png" title="Silver"> <? echo $prijs['kalos']; ?></td>
          <td class="normal_td"><img src="images/icons/silver.png" title="Silver"> <? echo $prijs['kalostotaal']; ?></td>
        </tr>
        <?
      }
      ?>

      <tr>
        <td colspan="5"><br/><br/><button type="submit" name="travel" class="button" ><?php echo $txt['button_travel']; ?></button></td>
      </tr>
    </table>
    <br/><br/>
  </form>

  <?php if($gebruiker['rank'] >= 5 && $gebruiker['in_hand'] != 0){ ?>

    <HR />
    <?php if($surferror) echo $surferror; ?>
    <table width="100%" border="0">
      <tr>
        <td><center><?php echo $txt['title_text_surf']; ?></center></td>
      </tr>
    </table>
    <form method="post">
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td width="20%" rowspan="6"><img src="images/surf.gif" style="border: 1px solid #000;" /></td>
          <td align="center" width="20%" class="top_first_td"><?php echo $txt['#']; ?></td>
          <td width="20%" class="top_td"><?php echo $txt['world']; ?></td>
          <td width="20%" class="top_td"><?php echo $txt['pokemon']; ?></td>
        </tr>
        <tr>
          <?
          if($gebruiker['wereld'] != "Kanto") {
            ?>
            <td align="center" class="normal_td"><label for="surfkanto"><input type="radio" name="wereld" value="kanto" id="surfkanto"></td>
            <td class="normal_td">Kanto</label></td>
            <?
          } else {
            echo "<td></td>
              <td></td>";
          }
          ?>
          <td class="normal_td" rowspan="5">
            <?
            #Pokemon query ophalen
            while($pokemon = $pokemon_sql->fetch(PDO::FETCH_ASSOC)){
              #Gegevens juist laden voor de pokemon
              $pokemon = pokemonei($pokemon);
              $pokemon['naam'] = pokemon_naam($pokemon['naam'],$pokemon['roepnaam']);
              $popup = pokemon_popup($pokemon, $txt);

              #Als pokemon geen baby is
              if($pokemonei['baby'] != "Ja") echo '<label><img src="'.$pokemon['animatie'].'" width="32" height="32"><input type="radio" name="pokemonid" value="'.$pokemon['id'].'"></label><br/>';
              else echo '<label><img src="images/items/Poke ball.png" width="32" height="32"><br /><input type="radio" name="pokemonid" value="'.$pokemon['id'].' disabled"><label>';
            }
            ?>
          </td>
        </tr>
        <?
        if($gebruiker['wereld'] != "Johto") {
          ?>
          <tr>
            <td align="center" class="normal_td"><label for="surfjohto"><input type="radio" name="wereld" value="johto" id="surfjohto"></td>
            <td class="normal_td">Johto</label></td>
          </tr>
          <?
        }
        if($gebruiker['wereld'] != "Hoenn") {
          ?>
          <tr>
            <td align="center" class="normal_td"><label for="surfhoenn"><input type="radio" name="wereld" value="hoenn" id="surfhoenn"></td>
            <td class="normal_td">Hoenn</label></td>
          </tr>
          <?
        }
        if($gebruiker['wereld'] != "Sinnoh") {
          ?>
          <tr>
            <td align="center" class="normal_td"><label for="surfsinnoh"><input type="radio" name="wereld" value="sinnoh" id="surfsinnoh"></td>
            <td class="normal_td">Sinnoh</label></td>
          </tr>
          <?
        }
        if($gebruiker['wereld'] != "Unova") {
          ?>
          <tr>
            <td align="center" class="normal_td"><label for="surfunova"><input type="radio" name="wereld" value="unova" id="surfunova"></td>
            <td class="normal_td">Unova</label></td>
          </tr>
          <?
        }
        if($gebruiker['wereld'] != "Kalos") {
          ?>
          <tr>
            <td align="center" class="normal_td"><label for="surfkalos"><input type="radio" name="wereld" value="kalos" id="surfkalos"></td>
            <td class="normal_td">Kalos</label></td>
          </tr>
          <?
        }
        ?>
        <tr>
          <td colspan="3"><button type="submit" name="travel" class="button" ><?php echo $txt['button_travel']; ?></button></td>
        </tr>
      </table>
    </form>



    <HR />
    <?php if($flyerror) echo $flyerror; ?>

    <table width="100%" border="0">
      <tr>
        <td><center><?php echo $txt['title_text_fly']; ?></center></td>
      </tr>
    </table>
    <form method="post">
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td width="20%" rowspan="6"><img src="images/fly.gif" style="border: 1px solid #000;" /></td>
          <td width="20%" align="center" class="top_first_td"><?php echo $txt['#']; ?></td>
          <td width="20%" class="top_td"><?php echo $txt['world']; ?></td>
          <td width="20%" class="top_td"><?php echo $txt['pokemon']; ?></td>
        </tr>
        <tr>
          <?
          if($gebruiker['wereld'] != "Kanto") {
            ?>
            <td align="center" class="normal_td"><label for="flykanto"><input type="radio" name="wereld" value="kanto" id="flykanto"></td>
            <td class="normal_td">Kanto</label></td>
            <?
          } else {
            echo "<td></td>
            <td></td>";
          }
          ?>
          <td class="normal_td" rowspan="5">
            <?
            #Pokemon query ophalen
            while($pokemon = $pokemon_sql->fetch(PDO::FETCH_ASSOC)){
              #Gegevens juist laden voor de pokemon
              $pokemon = pokemonei($pokemon);
              $pokemon['naam'] = pokemon_naam($pokemon['naam'],$pokemon['roepnaam']);
              $popup = pokemon_popup($pokemon, $txt);

              #Als pokemon geen baby is
              if($pokemonei['baby'] != "Ja") echo '<label><img src="'.$pokemon['animatie'].'" width="32" height="32"><input type="radio" name="pokemonid" value="'.$pokemon['id'].'"></label><br/>';
              else echo '<img src="images/items/Poke ball.png" width="32" height="32"><br /><input type="radio" name="pokemonid" value="'.$pokemon['id'].' disabled"></label>';
            }
            ?>
          </td>
        </tr>
        <?
        if($gebruiker['wereld'] != "Johto") {
          ?>
          <tr>
            <td align="center" class="normal_td"><label for="flyjohto"><input type="radio" name="wereld" value="johto" id="flyjohto"></td>
            <td class="normal_td">Johto</label></td>
          </tr>
          <?
        }
        if($gebruiker['wereld'] != "Hoenn") {
          ?>
          <tr>
            <td align="center" class="normal_td"><label for="flyhoenn"><input type="radio" name="wereld" value="hoenn" id="flyhoenn"></td>
            <td class="normal_td">Hoenn</label></td>
          </tr>
          <?
        }
        if($gebruiker['wereld'] != "Sinnoh") {
          ?>
          <tr>
            <td align="center" class="normal_td"><label for="flysinnoh"><input type="radio" name="wereld" value="sinnoh" id="flysinnoh"></td>
            <td class="normal_td">Sinnoh</label></td>
          </tr>
          <?
        }
        if($gebruiker['wereld'] != "Unova") {
          ?>
          <tr>
            <td align="center" class="normal_td"><label for="flyunova"><input type="radio" name="wereld" value="unova" id="flyunova"></td>
            <td class="normal_td">Unova</label></td>
          </tr>
          <?
        }
        if($gebruiker['wereld'] != "Kalos") {
          ?>
          <tr>
            <td align="center" class="normal_td"><label for="flykalos"><input type="radio" name="wereld" value="kalos" id="flykalos"></td>
            <td class="normal_td">Kalos</label></td>
          </tr>
          <?
        }
        ?>
        <tr>
          <td colspan="3"><button type="submit" name="fly" class="button" ><?php echo $txt['button_fly']; ?></button></td>
        </tr>
      </table>
    </form>
  <?php } ?>