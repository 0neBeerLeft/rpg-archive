<?php
session_start();
chdir('../');
include_once('includes/config.php');
include_once('includes/ingame.inc.php');

header('Content-type: application/json');

$sql = "SELECT id,username,content,UNIX_TIMESTAMP(`post_time`) AS `time` FROM `shoutbox` WHERE `clan` IS NULL ORDER BY id";
$shoutbox = query_cache_onremoved("shoutbox",$sql);

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