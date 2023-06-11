<?

if(isset($_POST['here'])){
  //Check alive pokemon.
  if(mysql_num_rows(mysql_query("SELECT id FROM pokemon_speler WHERE user_id='".$_SESSION['id']."' AND `opzak`='ja' AND leven>'0'")) == 0)
    echo "Je hebt alleen dode pokemon!";
  else{
    //Include Duel Functions
    include_once('duel/duel-start.php');
    
    $excist_sql = mysql_query("SELECT id FROM duel WHERE uitdager='".$_SESSION['naam']."' OR tegenstander='".$_SESSION['naam']."'");
    if(mysql_num_rows($excist_sql) == 0){
      if($round_info['user_id_1'] == $_SESSION['id']){
        $t = mysql_fetch_array(mysql_query("SELECT username, `character` FROM gebruikers WHERE user_id='".$round_info['user_id_2']."'"));
        $u['username'] = $gebruiker['username'];
        $u['character'] = $gebruiker['character'];
        $wat = 'uitdager';
        $starter = $u['username'];
      }
      else{
        $u = mysql_fetch_array(mysql_query("SELECT username, `character` FROM gebruikers WHERE user_id='".$round_info['user_id_1']."'"));
        $t['username'] = $gebruiker['username'];
        $t['character'] = $gebruiker['character'];
        $wat = 'tegenstander';
        $starter = $u['username'];
      }
      echo 0;
      $date = date("Y-m-d H:i:s");
      $time = strtotime($date);
      mysql_query("INSERT INTO duel (datum, ronde_id, uitdager, tegenstander, u_character, t_character, status, laatste_beurt_tijd, laatste_beurt)
        VALUES ('".$date."', '".$round_info['ronde']."', '".$u['username']."', '".$t['username']."',  '".$u['character']."', '".$t['character']."',  'wait', '".$time."', '".$starter."')");
      $duel_id = mysql_insert_id();
      $_SESSION['duel']['duel_id'] = $duel_id;
      
      //Start Duel
      //Clear Player
      mysql_query("DELETE FROM `pokemon_speler_gevecht` WHERE `user_id`='".$_SESSION['id']."'");
      //Update Player as Duel
      mysql_query("UPDATE `gebruikers` SET `pagina`='duel' WHERE `user_id`='".$_SESSION['id']."'");
      //Copy Pokemon
      $count = 0;
      //Spelers van de pokemon laden die hij opzak heeft
      $pokemonopzaksql = mysql_query("SELECT * FROM pokemon_speler WHERE user_id='".$_SESSION['id']."' AND `opzak`='ja' ORDER BY opzak_nummer ASC");
      //Nieuwe stats berekenen aan de hand van karakter, en opslaan
      while($pokemonopzak = mysql_fetch_array($pokemonopzaksql)){
        //Alle gegevens opslaan, incl nieuwe stats
        mysql_query("INSERT INTO `pokemon_speler_gevecht` (`id`, `user_id`, `aanval_log_id`, `duel_id`, `levenmax`, `leven`, `exp`, `totalexp`, `effect`, `hoelang`) 
          VALUES ('".$pokemonopzak['id']."', '".$_SESSION['id']."', '-1', '".$excist['id']."', '".$pokemonopzak['levenmax']."', '".$pokemonopzak['leven']."', '".$pokemonopzak['exp']."', '".$pokemonopzak['totalexp']."', '".$pokemonopzak['effect']."', '".$pokemonopzak['hoelang']."')");  
      }
      ?>
        <span id="status">wait</span>
        <script type="text/javascript">
        var t
        function status_check(){
          $.get("attack/tour_ready.php?duel_id="+<? echo $duel_id; ?>+"&sid="+Math.random(), function(data) {
            if(data == 0){
              $("#status").append(".")
              t = setTimeout('status_check()', 2000)
            } 
            else if(data == 1){
              clearTimeout(t) 
              setTimeout("location.href='index.php?page=attack/duel/duel-attack'", 0)
            }
            else if(data == 2){
              clearTimeout(t) 
              $("#status").append("Je tegenstander heeft niet gereageerd. Jij hebt gewonnen.")
            }
            else{
              $("#status").append("?")
              t = setTimeout('status_check()', 2000)
            }
          });
        }
        $("#status").html("Loading")
        status_check()
        </script>
      <?
    }
    else{
      $excist = mysql_fetch_array($excist_sql);
      if($_SESSION['id'] == $round_info['user_id_1']) $wat = 'u';
      else $wat = 't';
      $_SESSION['duel']['duel_id'] = $excist['id'];
      //Clear Player
      mysql_query("DELETE FROM `pokemon_speler_gevecht` WHERE `user_id`='".$_SESSION['id']."'");
      //Update Player as Duel
      mysql_query("UPDATE `gebruikers` SET `pagina`='duel' WHERE `user_id`='".$_SESSION['id']."'");
      //Copy Pokemon
      $count = 0;
      //Spelers van de pokemon laden die hij opzak heeft
      $pokemonopzaksql = mysql_query("SELECT * FROM pokemon_speler WHERE user_id='".$_SESSION['id']."' AND `opzak`='ja' ORDER BY opzak_nummer ASC");
      //Nieuwe stats berekenen aan de hand van karakter, en opslaan
      while($pokemonopzak = mysql_fetch_array($pokemonopzaksql)){
        //Alle gegevens opslaan, incl nieuwe stats
        mysql_query("INSERT INTO `pokemon_speler_gevecht` (`id`, `user_id`, `aanval_log_id`, `duel_id`, `levenmax`, `leven`, `exp`, `totalexp`, `effect`, `hoelang`) 
          VALUES ('".$pokemonopzak['id']."', '".$_SESSION['id']."', '-1', '".$excist['id']."', '".$pokemonopzak['levenmax']."', '".$pokemonopzak['leven']."', '".$pokemonopzak['exp']."', '".$pokemonopzak['totalexp']."', '".$pokemonopzak['effect']."', '".$pokemonopzak['hoelang']."')");
        if(($count == 0) AND ($pokemonopzak['leven'] > 0) AND ($pokemonopzak['ei'] == 0)){
          $count++;
          mysql_query("UPDATE `duel` SET `".$wat."_pokemonid`='".$pokemonopzak['id']."', `".$wat."_used_id`=',".$pokemonopzak['id'].",' WHERE `id`='".$excist['id']."'");
        }  
      }
      
      $_SESSION['duel']['begin_zien'] = true;
      header("Location: index.php?page=attack/duel/duel-attack");
    }
  }
}
else{
  /*
  
  
  Daarna stappen doorlopen zoals dat met duel ook gebeurd
  */
  echo "Jouw wedstrijd begint. Klik hier onder om mee te doen!<br />";
  echo '
    <form method="post">
      <input type="submit" name="here" value="Ik doe mee">
    </form>';
}
?>