<?
session_start();
//Verbinding met de database maken
include('../includes/config.php');
$count = 0;
$arr = explode(",", $_GET['id']);
foreach ($arr as $id) {
  if(is_numeric($id)){
    mysql_query("DELETE FROM berichten WHERE `id`='".$id."' AND `ontvanger_id`='".$_SESSION['id']."'");
    $count++;
  }
}
if($count == 0) echo "fail";
else echo "succes";
?>