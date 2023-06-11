
<?
#Load Safety Script
include("includes/security.php");

#Load language
$page = 'attack/duel/duel-attack';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

#Include Duel Functions
include("attack/duel/duel.inc.php");

#Include Attack Functions
include("attack/attack.inc.php");

#Load duel info
$duel_info = duel_info($_SESSION['duel']['duel_id']);

# Check if uitdager en tegenstander or valid
if(($duel_info['uitdager'] != $_SESSION['naam']) AND ($duel_info['tegenstander'] != $_SESSION['naam'])){
  remove_duel($duel_info['id']);
  #Send back to home
  header("Location: index.php?page=home");
  #Delete Cookie
  unset($_SESSION['duel']['duel_id']);
} 

if($duel_info['uitdager'] == $_SESSION['naam']){
  $duel_info['you'] = "uitdager";
  $duel_info['you_duel'] = "u_klaar";  
  $duel_info['you_sex'] = $duel_info['u_character'];
  $duel_info['opponent'] = "tegenstander";
  $duel_info['opponent_duel'] = "t_klaar";
  $duel_info['opponent_sex'] = $duel_info['t_character'];
  $duel_info['opponent_name'] = $duel_info['tegenstander'];
  
  #Load All Pokemon Info
  $pokemon_info = pokemon_data($duel_info['u_pokemonid']);
  $pokemon_info['naam_klein'] = strtolower($pokemon_info['naam']);
  $pokemon_info['naam_goed'] = pokemon_naam($pokemon_info['naam'],$pokemon_info['roepnaam']);

  #Calculate Life in Procent for Pokemon         
  if($pokemon_info['leven'] != 0) $pokemon_life_procent = round(($pokemon_info['leven']/$pokemon_info['levenmax'])*100);
  else $pokemon_life_procent = 0;

  #Calculate Exp in procent for pokemon
  if($pokemon_info['exp'] != 0) $pokemon_exp_procent = round(($pokemon_info['exp']/$pokemon_info['expnodig'])*100);
  else $pokemon_exp_procent = 0;
  
  #Shiny
  $pokemon_info['map'] = "pokemon";
  $pokemon_info['star'] = "none";
  if($pokemon_info['shiny'] == 1){
    $pokemon_info['map'] = "shiny";
    $pokemon_info['star'] = "block";
  }

  #Load All Opoonent Info
  $opponent_info = pokemon_data($duel_info['t_pokemonid']);
  $opponent_info['naam_klein'] = strtolower($opponent_info['naam']);
  $opponent_info['naam_goed'] = pokemon_naam($opponent_info['naam'],$opponent_info['roepnaam']);

  #Calculate Life in Procent for Pokemon         
  if($opponent_info['leven'] != 0) $opponent_life_procent = round(($opponent_info['leven']/$opponent_info['levenmax'])*100);
  else $opponent_life_procent = 0;
  
  #Shiny
  $opponent_info['map'] = "pokemon";
  $opponent_info['star'] = "none";
  if($opponent_info['shiny'] == 1){
    $opponent_info['map'] = "shiny";
    $opponent_info['star'] = "block";
  }
}

elseif($duel_info['tegenstander'] == $_SESSION['naam']){
  $duel_info['you'] = "tegenstander";
  $duel_info['you_duel'] = "t_klaar";
  $duel_info['you_sex'] = $duel_info['t_character'];
  $duel_info['opponent'] = "uitdager";
  $duel_info['opponent_duel'] = "u_klaar";
  $duel_info['opponent_sex'] = $duel_info['u_character'];
  $duel_info['opponent_name'] = $duel_info['uitdager'];

  #Load All Pokemon Info
  $pokemon_info = pokemon_data($duel_info['t_pokemonid']);
  $pokemon_info['naam_klein'] = strtolower($pokemon_info['naam']);
  $pokemon_info['naam_goed'] = pokemon_naam($pokemon_info['naam'],$pokemon_info['roepnaam']);

  #Calculate Life in Procent for Pokemon         
  if($pokemon_info['leven'] != 0) $pokemon_life_procent = round(($pokemon_info['leven']/$pokemon_info['levenmax'])*100);
  else $pokemon_life_procent = 0;

  #Calculate Exp in procent for pokemon
  if($pokemon_info['exp'] != 0) $pokemon_exp_procent = round(($pokemon_info['exp']/$pokemon_info['expnodig'])*100);
  else $pokemon_exp_procent = 0;
  
  #Shiny
  $pokemon_info['map'] = "pokemon";
  $pokemon_info['star'] = "none";
  if($pokemon_info['shiny'] == 1){
    $pokemon_info['map'] = "shiny";
    $pokemon_info['star'] = "block";
  }

  #Load All Opoonent Info
  $opponent_info = pokemon_data($duel_info['u_pokemonid']);
  $opponent_info['naam_klein'] = strtolower($opponent_info['naam']);
  $opponent_info['naam_goed'] = pokemon_naam($opponent_info['naam'],$opponent_info['roepnaam']);

  #Calculate Life in Procent for Pokemon         
  if($opponent_info['leven'] != 0) $opponent_life_procent = round(($opponent_info['leven']/$opponent_info['levenmax'])*100);
  else $opponent_life_procent = 0;
  #Shiny
  $opponent_info['map'] = "pokemon";
  $opponent_info['star'] = "none";
  if($opponent_info['shiny'] == 1){
    $opponent_info['map'] = "shiny";
    $opponent_info['star'] = "block";
  }
}

$time_left = strtotime(date("Y-m-d H:i:s"))-$duel_info['laatste_beurt_tijd'];
if($time_left > 61) $time_left = 59;

for($inhand = 1; $player_hand = mysql_fetch_assoc($pokemon_sql); $inhand++){
  #Check Wich Pokemon is infight
  if($player_hand['id'] == $pokemon_info['id']) $infight = 1;
  else $infight = 0;
  if($player_hand['ei'] == 1){ 
    $player_hand['naam'] = "??";
    $player_hand['wild_id'] = "??";
  }
  ?>
  <script>
    //If div is ready
    $("div[id='change_pokemon']").ready(function() {
      //Is pokemon in fight, so yes, don't show
      if(<? echo $infight; ?> == 1){
        if(<? echo $player_hand['shiny']; ?> == 1){
          $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").css({ backgroundImage : "url(images/shiny/icon/<? echo strtolower($player_hand['wild_id']); ?>.gif)" });
          $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").attr("title", "<? echo $player_hand['naam']; ?> \nLife: <? echo $player_hand['leven']; ?>/<? echo $player_hand['levenmax']; ?>");
        }
        else{
     	    $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").css({ backgroundImage : "url(images/pokemon/icon/<? echo strtolower($player_hand['wild_id']); ?>.gif)" });
          $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").attr("title", "<? echo $player_hand['naam']; ?> \nLife: <? echo $player_hand['leven']; ?>/<? echo $player_hand['levenmax']; ?>");
        }      
      }
      else if(1 == "<? echo $player_hand['ei']; ?>"){
        $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").css({ backgroundImage : "url(images/icons/egg.gif)" });
        $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").attr("title", "Egg");
        $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").show()
      }
      //Pokemon is not in fight, show.
      else{
        if(<? echo $player_hand['id']; ?> != ""){
          if(<? echo $player_hand['shiny']; ?> == 1){
            $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").css({ backgroundImage : "url(images/shiny/icon/<? echo strtolower($player_hand['wild_id']); ?>.gif)" });
            $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").attr("title", "<? echo $player_hand['naam']; ?> \nLife: <? echo $player_hand['leven']; ?>/<? echo $player_hand['levenmax']; ?>");
          }
          else{
       	    $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").css({ backgroundImage : "url(images/pokemon/icon/<? echo strtolower($player_hand['wild_id']); ?>.gif)" });
            $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").attr("title", "<? echo $player_hand['naam']; ?> \nLife: <? echo $player_hand['leven']; ?>/<? echo $player_hand['levenmax']; ?>");
          }
          $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").show()
        }
      }
    });
  </script>
<?
}
//Player Pokemon In Hand
mysql_data_seek($pokemon_sql, 0);
?>
<script type="text/javascript" src="attack/duel/javascript/duel.js"></script>
<script language="javascript">
var you_to_late
var opp_to_late
var ready_check
var attack
var wissel
var start_text
var max_time = 60
var you_time_used
var opp_time_used
var end
var you = "<? echo $gebruiker['username']; ?>";

function your_to_late(){
  clearTimeout(you_to_late)
  $("#message").html("<?php echo $txt['too_late_lost']; ?>")
  setTimeout("show_end_screen();", 500)
}

function you_check_to_late(){
  you_time_used++
  $("#time_left").html(max_time-you_time_used)
  if(you_time_used >= max_time) your_to_late()
  else you_to_late = setTimeout('you_check_to_late()', 500)
}

function opponent_check_to_late(){
  opp_time_used++
  $("#time_left").html(max_time-opp_time_used)
  if(opp_time_used >= max_time) last_move_check()
  else opp_to_late = setTimeout('opponent_check_to_late()', 500)
}

function do_wissel(request){
  if(request[5] == 1){
    console.log(request);
    $("#img_opponent").attr("src","images/shiny/"+request[3]+".gif")
    $("#opponent_naam").html(request[2])
    $("#opponent_level").html(request[9])
    $("#opponent_star").show()
  }
  else{
    console.log(request);
    $("#img_opponent").attr("src","images/pokemon/"+request[3]+".gif")
    $("#opponent_naam").html(request[2])
    $("#opponent_level").html(request[9])
    console.log(request);
    $("#opponent_star").hide()
  }
  var opponent_life_procent = Math.round((request[6]/request[7])*100)
  $("#opponent_life").width(opponent_life_procent+'%')
  $("#opponent_naam").html(request[4])
  if(request[8] != ""){
    $("#opponent_hand_"+request[8]).attr("src","images/icons/pokeball_black.gif")
    $("#opponent_hand_"+request[8]).attr("title","Dead")
  }
}

function last_move_check(){
  clearTimeout(you_to_late)
  $.get("attack/duel/last_move_check.php?duel_id="+<? echo $duel_info['id']; ?>+"&sid="+Math.random(), function(data) {
    request = data.split(" | ")
    //No reaction
    if(request[0] == 0){
      setTimeout('last_move_check()', 500)
      attack = 0
      wissel = 0
    }
    //You can to Attack
    else if(request[0] == 1){
      if(request[2] == "wissel"){
        do_wissel(request)
      }
      else{
        leven_verandering(request[3],'pokemon',request[4])
        $("div[id='change_pokemon'][name='"+request[6]+"']").attr("title", ""+request[5]+" \nLife: "+request[3]+"/"+request[4]+"");
      }
      clearTimeout(opp_to_late)
      $("#message").html(request[1])
      attack = 1
      wissel = 1
      you_time_used = request[9]
      you_check_to_late()
    }
    //Opponent Has to Attack
    else if(request[0] == 3){
      if(request[2] == "wissel"){
        do_wissel(request)
      }
      clearTimeout(opp_to_late)
      $("#message").html(request[1])
      attack = 0
      wissel = 0
      opp_time_used = request[9]
      opponent_check_to_late()
      setTimeout('last_move_check()', 500)
    }
    //Player Has To Change
    else if(request[0] == 4){
      clearTimeout(opp_to_late)
      $("#message").html(request[1])
      leven_verandering(request[3],'pokemon',request[4])
      $("div[id='change_pokemon'][name='"+request[6]+"']").attr("title", ""+request[5]+" \nLife: "+request[3]+"/"+request[4]+"");
      attack = 0
      wissel = 1
      you_time_used = request[9]
      you_check_to_late()
    }
    //Opponent Was to Late
    else if(request[0] == 2){
      clearTimeout(opp_to_late)
      $("#message").html(request[1])
      end = setTimeout("show_end_screen();", 500)
      $("#time_left").html("0")
      attack = 0
      wissel = 0
    }
    //Player lost
    else if(request[0] == 5){
      clearTimeout(opp_to_late)
      $("#message").html(request[1])
      $("#time_left").html("0")
      leven_verandering(request[3],'pokemon',request[4])
      end = setTimeout("show_end_screen();", 500)
      attack = 0
      wissel = 0
    }
  });
}

function show_start_text(begin,your,opp,opp_life,you_link,opp_link,you_life,you_exp){
  clearTimeout(start_text)
  $("#img_you").attr("src",you_link)
  $("#img_opponent").attr("src",opp_link)
  $("#opponent_life").width(opp_life+'%')
  $("#pokemon_life").width(you_life+'%')
  $("#pokemon_exp").width(you_exp+'%')

  $("#you_naam").html("<? echo $pokemon_info['naam_goed']; ?>")
  $("#you_level").html("<? echo $pokemon_info['level']; ?>")
  $("#opponent_naam").html("<? echo $opponent_info['naam_goed']; ?>")
  $("#opponent_level").html("<? echo $opponent_info['level']; ?>")
  $("#you_text").show()
  if(begin == you+"_begin"){
    $("#message").html("<?php echo $txt['you_first_attack']; ?>")
    attack = 1
    wissel = 1
    you_time_used = 0
    you_check_to_late()
  }

  else {
    $("#message").html("<? echo $duel_info['opponent_name'].' '.$txt['opponent_first_attack']; ?>")
    attack = 0
    wissel = 0
    last_move_check()
    opp_time_used = 0
    opponent_check_to_late()
  }
}

function check_ready(){
  $.get("attack/duel/duel-check-ready.php?duel_id="+<? echo $duel_info['id']; ?>+"&sid="+Math.random(), function(data) {
    request = data.split(" | ")
    if(request[0] == 0){
      if(request[1] != "") $("#message").html(request[1])
      else ready_check = setTimeout("check_ready()", 500)
    }
    else if(request[0] == 1){
      clearTimeout(ready_check)
      start_text = setTimeout("show_start_text(\'"+request[1]+"\',\'"+request[2]+"\',\'"+request[3]+"\',\'"+request[4]+"\',\'"+request[5]+"\',\'"+request[6]+"\',\'"+request[7]+"\',\'"+request[8]+"\');", 1000)
    }
    else ready_check = setTimeout("check_ready()", 500)
  });
}

$("#message").ready(function() {
  if("<? echo $_SESSION['duel']['begin_zien']; ?>" == 1) {
    $("#you_text").hide()
    if(you == "<? echo $duel_info['tegenstander']; ?>"){
      $("#message").html("<? echo $duel_info['uitdager'].' '.$txt['has_invite_you']; ?>")
      $("#opponent_naam").html("<? echo $duel_info['opponent_name']; ?>.")    
    }

    else if(you == "<? echo $duel_info['uitdager']; ?>"){
      $("#message").html("<?php echo $txt['you_invite_1'].' '.$duel_info['tegenstander'].' '.$txt['you_invite_2']; ?>")
      $("#opponent_naam").html("<? echo $duel_info['opponent_name']; ?>.")
    }
    //Set Images
    $("#img_you").attr("src","<? echo $duel_info['you_sex']; ?>")
    $("#img_opponent").attr("src","<? echo $duel_info['opponent_sex']; ?>")
    $("#opponent_life").width('100%')
    $("#pokemon_life").width('100%')
    $("#pokemon_exp").width('0%')
    
    ready_check = setTimeout("check_ready()", 500)
  }
  else if("<? echo $duel_info['laatste_beurt']; ?>" == you+"_begin"){
    $("#message").html("<?php echo $txt['you_first_attack']; ?>")
    attack = 1
    wissel = 1
    you_time_used = <? echo $time_left; ?>;
    you_check_to_late()
  }
  else if ("<? echo $duel_info['laatste_beurt']; ?>".match(/begin.*/)) {
    var who = "<? echo $duel_info['laatste_beurt']; ?>".split("_",1);
    $("#message").html(who+" <?php echo $txt['opponent_first_attack']; ?>")
    attack = 0
    wissel = 0
    last_move_check()
    opp_time_used = <? echo $time_left; ?>;
    opponent_check_to_late()
  }
  else if("<? echo $duel_info['volgende_zet']; ?>" == "end_screen"){
    end = show_end_screen()
  }
  else if(("<? echo $duel_info['volgende_beurt']; ?>" == you) && ("<? echo $duel_info['volgende_zet']; ?>" == "wisselen")){
    $("#message").html("<?php echo $txt['change_now']; ?>")
    attack = 0
    wissel = 1
    you_time_used = <? echo $time_left; ?>;
    you_check_to_late()
  }
  else if("<? echo $duel_info['volgende_beurt']; ?>" == you){
    $("#message").html("<?php echo $txt['your_turn']; ?>")
    attack = 1
    wissel = 1
    you_time_used = <? echo $time_left; ?>;
    you_check_to_late()
  }
  else if("<? echo $duel_info['volgende_zet']; ?>" == "wisselen"){
    $("#message").html("<? echo $duel_info['tegenstander'].' '.$txt['opponent_change']; ?>")
    attack = 0
    wissel = 0
    last_move_check()
    opp_time_used = <? echo $time_left; ?>;
    opponent_check_to_late()
  }
  else{
    $("#message").html("<? echo $duel_info['tegenstander'].' '.$txt['opponents_turn']; ?>")
    attack = 0
    wissel = 0
    last_move_check()
    opp_time_used = <? echo $time_left; ?>;
    opponent_check_to_late()
  }
});

function show_end_screen(){
  clearTimeout(end)
  $.get("attack/duel/duel-finish.php?duel_id="+<? echo $duel_info['id']; ?>+"&sid="+Math.random(), function(data) {
    request = data.split(" | ")
    if(request[0] == 1){
      $("#message").html("<?php echo $txt['fight_over_win']; ?> ")
      if(request[1] > 0) $("#message").append("<?php echo $txt['you_win']; ?> "+request[1]+" Silver.")
    }
    else if(request[0] == 2){
      $("#message").html("<?php echo $txt['fight_over_lost']; ?> ")
      if(request[1] > 0) $("#message").append("<?php echo $txt['you_lose']; ?> "+request[1]+" Silver.")
    }
    $("#pokemon_text").hide()
    $("#trainer_naam").html(request[3])
    //Set Images
    $("#img_pokemon").attr("src",""+request[4]+"")
    $("#img_trainer").attr("src",""+request[5]+"")
    setTimeout("location.href='index.php?page=pokemoncenter'", 500)
  });
}

function attack_status_2(msg){
  request = msg.split(" | ")
  $("#message").html(request[0])
  if(request[1] == 1){
    setTimeout('last_move_check()', 500)
    opp_time_used = 0
    opponent_check_to_late() 
    attack = 0
    wissel = 0
    if(request[3] == 0) exp_change(request[6],request[7])
  }
  else if(request[1] == 2){
    exp_change(request[6],request[7])
    setTimeout("show_end_screen();", 500)
  }
}

function attack_status(msg){
  request = msg.split(" | ")


  var time = 250
  if(request[2] < 25) time = 500
  else if(request[2] < 50) time = 500
  else if(request[2] < 100) time = 500
  else if(request[2] < 150) time = 500
  else if(request[2] < 200) time = 500
  else if(request[2] < 250) time = 500
  else if(request[2] >= 250) time = 500

  if(request[2] > 0) leven_verandering(request[3],'opponent',request[4]);
    if(request[4] == 'pokemon') $("#leven").html(request[2]);
  attack_timer = setTimeout("attack_status_2('"+msg+"');", time)
}

//Change Pokemon Function
function change_pokemon_status(msg){
  //Get php variables
  request = msg.split(" | ")
  //Send message
  $("#message").html(request[0])
  //Change was succesfull
  if(request[1] == 1){ 
    //Change Pokemon in fight name, level and attacks
    $("#pokemon_naam").html(request[2])
    $("#pokemon_level").html(request[3])
    $("button:eq(0)").html(request[9])
    $("button:eq(1)").html(request[10])
    $("button:eq(2)").html(request[11])
    $("button:eq(3)").html(request[12])
    
    //set initial life
    $("#leven").html(request[10])
    $("#levenmax").html(request[11])
    //Create image for new pokemon in fight
    if(request[4] == 0){
      $("#img_you").attr("src","images/pokemon/back/" + request[15] + ".gif")
      $("#pokemon_star").hide()
    }
    else{
      $("#img_you").attr("src","images/shiny/back/" + request[15] + ".gif")
      $("#pokemon_star").show() 
    }
    //Show all pokemon in your hand
    $("div[id*='change_pokemon'][name*='1']").show()
    $("div[id*='change_pokemon'][name*='2']").show()
    $("div[id*='change_pokemon'][name*='3']").show()
    $("div[id*='change_pokemon'][name*='4']").show()
    $("div[id*='change_pokemon'][name*='5']").show()
    $("div[id*='change_pokemon'][name*='6']").show()
    //Hide the new pokemon that is in fight
    $("div[id*='change_pokemon'][name*='"+request[13]+"']").hide()
    //Change the HP Status from new pokemon in fight
    var pokemon_life_procent = Math.round((request[5]/request[6])*100)
    $("#pokemon_life").width(pokemon_life_procent+'%')
    //Change EXP Status from new pokemon in fight
    var exp_procent = Math.round((request[7]/request[8])*100)
    $("#pokemon_exp").width(exp_procent+'%')
    //Opponent make next turn
    wissel = 0
    if(request[14] == you){
      attack = 1
      you_time_used = 0;
      you_check_to_late()
    }
    else{
      attack = 0
      setTimeout('last_move_check()', 500)
      opp_time_used = 0
      opponent_check_to_late()
    }
  }
}

//Player Can Do Stuff
$(document).ready(function(){
  //Player Do Attack
  $("button[id='aanval']").click(function(){
    if(attack == 1){
      if($(this).html() != ""){
  			$("#message").html("<?php echo $txt['use_attack_1'] ?> "+$(this).html()+".")
  			clearTimeout(you_to_late)
			  $.ajax({
  			  type: "GET",
  			  url: "attack/duel/duel-do_attack.php?attack_name="+$(this).html()+"&wie="+you+"&duel_id="+<? echo $duel_info['id']; ?>+"&sid="+Math.random(),
  			  success: attack_status
  			}); 
  			attack = 0;
		  }
		}
  });
  //Player Make Change Pokemon
  $("div[id='change_pokemon']").click(function(){
    if(wissel == 1){
      if(($(this).attr("name") != "") && (($(this).attr("title")) != "Egg") && (($(this).attr("title")) != "")){
        clearTimeout(you_to_late)
        $.ajax({
          type: "GET",
          url: "attack/duel/duel-change-pokemon.php?opzak_nummer="+$(this).attr("name")+"&wie="+you+"&duel_id="+<? echo $duel_info['id']; ?>+"&sid="+Math.random(),
          success: change_pokemon_status
        }); 
      }
    }
  });
});

</script>
<?
  if($gebruiker['battleScreen']){
    $battleScreen = "battlearea";
    $battleShadow = 'text-shadow:1px 1px 1px #000;';
  } else {
    $battleScreen = "battleareaoff";
    $battleShadow = '';
  }
  ?>
<center>
  <table class="<?=$battleScreen?> pvp">
    <tr>
      <td>
        <div style="padding:0px 0 5px 0px;">
          <div class="new_bar2">
            <div style="padding: 15px 0 0 120px;">
              <img src="../images/battlescreen/lvl.png" style="padding:0 0 0 30px;">
              <font size="3" style="<?=$battleShadow?>">
                <strong id="opponent_level"><?=$opponent_info['level']?></strong>
              </font>
            </div>
            <div style="padding:0px 0 0 80px;">
              <div class="hp_red">
                <div class="hp_progress" id="opponent_life" style="width: <?php echo $opponent_life_procent; ?>%"></div>
              </div>
            </div>
            <div align="left" style="padding: 12px 0px 0px 10px;">
              <font style="<?=$battleShadow?>" size="3"><strong id="opponent_naam"><?=$opponent_info['naam_goed']?> </strong></font><br/>
              <?
              $opponent_pok = mysql_query("SELECT psg.id, psg.leven FROM gebruikers AS g INNER JOIN pokemon_speler_gevecht AS psg ON g.user_id = psg.user_id INNER JOIN pokemon_speler AS ps ON psg.id = ps.id WHERE g.username='".$duel_info['opponent_name']."' AND psg.duel_id='".$duel_info['id']."' ORDER BY ps.opzak_nummer");
              while($opponent_pokemon = mysql_fetch_array($opponent_pok)){
                if($opponent_pokemon['leven'] > 0) echo '<img id="opponent_hand_'.$opponent_pokemon['id'].'" src="./images/icons/pokeball.gif" width="14" height="14" alt="Alive" title="Alive" />';
                else echo '<img id="opponent_hand_'.$opponent_pokemon['id'].'" src="./images/icons/pokeball_black.gif" width="14" height="14" "Dead" title="Dead" />';
              }
              ?>
            </div>
          </div>
        </div>
      </td>
      <td>
        <img id="img_opponent" src="images/<? echo $opponent_info['map']."/".$opponent_info['wild_id']; ?>.gif" style="position:relative;top:110px;left:205px;max-height: 96px;"/>
      </td>
    </tr>
    <tr>
      <td>
        <img id="img_you" src="images/<? echo $pokemon_info['map']; ?>/back/<? echo $pokemon_info['wild_id']; ?>.gif" style="position:relative;top:50px;left:155px;max-height: 96px;"/>
      </td>
      <td>
        <div style="padding:100px 0 0 130px;">
          <div class="new_bar">
            <div style="padding:16px 0 0px 10px;"><strong>
                <font size="3" style="<?=$battleShadow?>">
                  <span  id="pokemon_naam"  style="float:left;"><?=$pokemon_info['naam_goed']?> </span></strong>
              </font>
              <span id="pokemon_star" style="display:none;"></span>
            </div>
            <font size="3" style="<?=$battleShadow?>">
              <strong>
                <i><img src="../images/battlescreen/lvl.png" style="padding:0 0 0 30px;">
                  <span id="pokemon_level"  style="padding:0px 0 0px 5px;"><?=$pokemon_info['level']?></span>
                </i>
              </strong>
            </font>
            <div style="position:relative;top:-3px;left: 110px;">
              <div class="hp_red">
                <div class="hp_progress" id="pokemon_life"
                     style="width: <?php echo $pokemon_life_procent; ?>%"></div>
              </div>
            </div>
            <div style="position:relative;bottom:5px;color:#fff;float:right;right:55px;font:italic bold 13px Arial,Helvetica,sans-serif;">
              <span id="leven"><?=$pokemon_info['leven']?></span> / <span id="levenmax"><?=$pokemon_info['levenmax']?></span>
            </div>
            <div style="position:relative;bottom:2px;left:68px;">
              <div class="exp_blue">
                <div class="exp_progress" id="pokemon_exp"
                     style="width: <?php echo $pokemon_exp_procent; ?>%"></div>
              </div>
            </div>
          </div>
        </div>
      </td>
    </tr>
  </table>
   <table cellpadding=0 cellspacing=0 width="660">
    <tr>
      <td colspan=4><HR></td>
    </tr>
    <tr>
      <td colspan=4 style="height:50px;"><div id="message" align="center"></div><td>
	  </tr>
		<tr>
		  <td colspan=4><HR></td>
    </tr>
  </table>
  <style>
    #aanval {
      width: 10em;  height: 3em;
    }
    #use_item {
      width: 10em;  height: 3em;
    }
  </style>
    <table cellpadding=0 cellspacing=0 width="660">
  	<tr>
    	<td width=284>
      	<table cellpadding=0 cellspacing=0 width=284 border=0>
        	<tr>
            <td colspan=2 height=20><strong><?php echo $txt['attack']; ?></strong></td>
          </tr>
            <tr>                       	
              <td width=140><button id="aanval" class="button" style="min-width: 70px;"><? echo $pokemon_info['aanval_1']; ?></button></td>
              <td width=144><button id="aanval" class="button" style="margin-left:4px; min-width: 70px;"><? echo $pokemon_info['aanval_2']; ?></button></td>
            </tr>                                	  
            <tr>                  		
              <td><button id="aanval" class="button" style="min-width: 70px;"><? echo $pokemon_info['aanval_3']; ?></button></td>
              <td><button id="aanval" class="button" style="margin-left:4px; min-width: 70px;"><? echo $pokemon_info['aanval_4']; ?></button></td>
            </tr>
          </table>  
        </td>
        <td width=176>
        	<table width=176 cellpadding=0 cellspacing=0 border=0>
          	<tr>
              <td><div style="padding: 0px 8px 0px 8px;"><strong><?php echo $txt['change']; ?></strong></div></td>
            </tr>
            <tr>
              <td height=54><div style="padding: 0px 8px 0px 8px;">
            	   <div id="change_pokemon" name="1" title="" style="display:none; background-image: url(); float: left; height: 32px; width: 32px; border: 0px; cursor: pointer;" /></div>
                 <div id="change_pokemon" name="2" title="" style="display:none; background-image: url(); float: left; height: 32px; width: 32px; border: 0px; cursor: pointer;" /></div>
                 <div id="change_pokemon" name="3" title="" style="display:none; background-image: url(); float: left; height: 32px; width: 32px; border: 0px; cursor: pointer;" /></div>
                 <div id="change_pokemon" name="4" title="" style="display:none; background-image: url(); float: left; height: 32px; width: 32px; border: 0px; cursor: pointer;" /></div>
                 <div id="change_pokemon" name="5" title="" style="display:none; background-image: url(); float: left; height: 32px; width: 32px; border: 0px; cursor: pointer;" /></div>
                 <div id="change_pokemon" name="6" title="" style="display:none; background-image: url(); float: left; height: 32px; width: 32px; border: 0px; cursor: pointer;" /></div>
              </td>
            </tr>
          </table>
        </td>
        <td width=140>
          <table width=140 cellpadding=0 cellspacing=0 border=0>
            <tr>
              <td height=20><strong><?php echo $txt['time']; ?></strong></td>
            </tr>
            <tr>
            <td><span id="time_left"></span> <?php echo $txt['seconds_left']; ?></td>
            </tr>
          </table>
        </td>  
  	</tr>
  </table>        
</center>
<?
//Page Completly loaded, Player Ready
mysql_query("UPDATE `duel` SET `".$duel_info['you_duel']."`='1' WHERE `id`='".$duel_info['id']."'");
?>