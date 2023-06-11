<?php
session_start();
include_once('../includes/config.php');

header('Content-type: application/json');

$sql = "SELECT id,username,content,UNIX_TIMESTAMP(`post_time`) AS `time` FROM `shoutbox` WHERE `clan`='".$_SESSION['clan']."' ORDER BY id";
$shoutbox = query_cache_onremoved("shoutbox-".$_SESSION['clan'],$sql);

if(!empty($shoutbox)){
    $data=array();
    foreach($shoutbox as $shout) {
        $shoutdata['id'] = $shout['id'];
        $shoutdata['time'] = $shout['time'];
        $shoutdata['name'] = $shout['username'];
        $shoutdata['content'] = $shout['content'];
        $data[]=$shoutdata;
    }

    echo json_encode($data,JSON_NUMERIC_CHECK);
}