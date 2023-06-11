<?
if((isset($_GET['duel_id'])) AND (isset($_GET['sid']))){
  //Session On
  session_start();  
  //Connect With Database
  include_once("../../includes/config.php");
  //include duel functions
  include_once("duel.inc.php");
  //Load Duel Data
  $duel_sql = mysql_query("SElECT `uitdager`, `tegenstander`, `u_klaar`, `t_klaar`, `u_pokemonid`, `t_pokemonid`, `laatste_beurt`, `laatste_aanval` FROM `duel` WHERE `id`='".$_GET['duel_id']."'");
  //Default text
  $ready = 0;
  //If there is no duel
  if(mysql_num_rows($duel_sql) == 1){
    $duel = mysql_fetch_array($duel_sql);
    if((($duel['t_klaar'] == 1) AND ($duel['u_klaar'] == 1)) AND ($duel['laatste_beurt'] != "")){
      $ready = 1;
      $_SESSION['duel']['begin_zien'] = False;
      $mes = $duel['laatste_beurt'];
      $uitdager = mysql_fetch_array(mysql_query("SELECT pw.wild_id, pw.naam, psg.levenmax, psg.leven, ps.shiny, ps.exp, ps.expnodig FROM pokemon_speler AS ps INNER JOIN pokemon_speler_gevecht AS psg ON psg.id = ps.id INNER JOIN pokemon_wild AS pw On ps.wild_id = pw.wild_id WHERE psg.id='".$duel['u_pokemonid']."'"));
      $tegenstander = mysql_fetch_array(mysql_query("SELECT pw.wild_id, pw.naam, psg.levenmax, psg.leven, ps.shiny, ps.exp, ps.expnodig FROM pokemon_speler AS ps INNER JOIN pokemon_speler_gevecht AS psg ON psg.id = ps.id INNER JOIN pokemon_wild AS pw On ps.wild_id = pw.wild_id WHERE psg.id='".$duel['t_pokemonid']."'"));
      if($duel['uitdager'] == $_SESSION['naam']){
        $you_name = $uitdager['naam'];
        $opp_name = $tegenstander['naam'];
        if($tegenstander['shiny'] == 1) $opp_link = 'images/shiny/'.$tegenstander['wild_id'].'.gif';
        else $opp_link = 'images/pokemon/'.$tegenstander['wild_id'].'.gif';
        if($uitdager['shiny'] == 1) $you_link = 'images/shiny/back/'.$uitdager['wild_id'].'.gif';
        else $you_link = 'images/pokemon/back/'.$uitdager['wild_id'].'.gif';
        if($tegenstander['leven'] == 0) $opp_life = 0;
        else $opp_life = round(($tegenstander['leven']/$tegenstander['levenmax'])*100);
        if($uitdager['leven'] == 0) $you_life = 0;
        else $you_life = round(($uitdager['leven']/$uitdager['levenmax'])*100);
        if($uitdager['exp'] == 0) $you_exp = 0;
        else $you_exp = round(($uitdager['exp']/$uitdager['expnodig'])*100);
      }  
      elseif($duel['tegenstander'] == $_SESSION['naam']){
        $you_name = $tegenstander['naam'];
        $opp_name = $uitdager['naam'];
        if($tegenstander['shiny'] == 1) $you_link = 'images/shiny/back/'.$tegenstander['wild_id'].'.gif';
        else $you_link = 'images/pokemon/back/'.$tegenstander['wild_id'].'.gif';
        if($uitdager['shiny'] == 1) $opp_link = 'images/shiny/'.$uitdager['wild_id'].'.gif';
        else $opp_link = 'images/pokemon/'.$uitdager['wild_id'].'.gif';
        if($uitdager['leven'] == 0) $opp_life = 0;
        else $opp_life = round(($uitdager['leven']/$uitdager['levenmax'])*100);
        if($tegenstander['leven'] == 0) $you_life = 0;
        else $you_life = round(($tegenstander['leven']/$tegenstander['levenmax'])*100);
        if($tegenstander['exp'] == 0) $you_exp = 0;
        else $you_exp = round(($tegenstander['exp']/$tegenstander['expnodig'])*100);
      }  
      //Save Current Time
      $time = strtotime(date("Y-m-d H:i:s"));
      mysql_query("UPDATE `duel` SET `laatste_beurt_tijd`='".$time."' WHERE `id`='".$_GET['duel_id']."'");
    } 
  }
  else $mes = "Foutcode: 6001";
  
  echo $ready." | ".$mes." | ".$you_name." | ".$opp_name." | ".$opp_life." | ".$you_link." | ".$opp_link." | ".$you_life." | ".$you_exp;
}
?>