<?php
	//Script laden zodat je nooit pagina buiten de index om kan laden
	include("includes/security.php");
	
	//Vanaf rank 16 page beschikbaar:
	if($gebruiker['rank'] < 18) header('Location: index.php?page=home');
    if($gebruiker['premiumaccount'] < 1) header('Location: index.php?page=home');

	$page = 'lvl-choose';
	//Goeie taal erbij laden voor de page
	include_once('language/language-pages.php');

	$check_1;
	$check_2;
	$check_3;
	$check_4;
	$check_5;
	$lvl_choose = $gebruiker['lvl_choose'];
	
	if((isset($_POST['submit'])) && (isset($_POST['lvl']))){
    mysql_query("UPDATE `gebruikers` SET `lvl_choose`='".$_POST['lvl']."' WHERE `user_id`='".$_SESSION['id']."'");
    echo '<div class="blue"><img src="images/icons/blue.png"> '.$txt['success_lvl_choose'].'</div>';		
    $lvl_choose = $_POST['lvl'];
	}

	if($lvl_choose === '5-20') $check_1 = "checked";
	elseif($lvl_choose === '20-40') $check_2 = "checked";
	elseif($lvl_choose === '40-60') $check_3 = "checked";
	elseif($lvl_choose === '60-80') $check_4 = "checked";
	elseif($lvl_choose === '80-100') $check_5 = "checked";

?>

<center>
  <div style="padding-bottom:20px;"><?php echo $txt['title_text']; ?></div>
  <form method="post">
    <table width="150">
    <tr>
      <td width="75"><strong><center><?php echo $txt['#']; ?></center></strong></td>
      <td width="75"><strong><?php echo $txt['level']; ?></strong></td>
    </tr>
    <tr>
      <td><center><input type="radio" name="lvl" value="5-20" <? echo $check_1; ?>/></center></td>
      <td><?php echo $txt['5-20']; ?></td>
    </tr>
    <tr>
      <td><center><input type="radio" name="lvl" value="20-40" <? echo $check_2; ?>/></center></td>
      <td><?php echo $txt['20-40']; ?></td>
    </tr>
    <tr>
      <td><center><input type="radio" name="lvl" value="40-60" <? echo $check_3; ?>/></center></td>
      <td><?php echo $txt['40-60']; ?></td>
    </tr>
    <tr>
      <td><center><input type="radio" name="lvl" value="60-80" <? echo $check_4; ?>/></center></td>
      <td><?php echo $txt['60-80']; ?></td>
    </tr>
    <tr>
      <td><center><input type="radio" name="lvl" value="80-100" <? echo $check_5; ?>/></center></td>
      <td><?php echo $txt['80-100']; ?></td>
    </tr>
    <tr>
      <td colspan="2"><center><input type="submit" name="submit" value="<?php echo $txt['button']; ?>" class="button_mini" /></center></td>
    </tr>
    </table>
  </form>
</center>