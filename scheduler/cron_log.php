<?
include_once ('cronConfig.php');

$selectsql = mysql_query("SELECT `id`, `from`, `to`, `message`, `sent`, `recd` FROM `chat`");
while ($select = mysql_fetch_array($selectsql)){
    $bericht = $select['id']."|".$select['from']."|".$select['to']."|".$select['message']."|".$select['sent']."|".$select['recd']."\n";
    $tekst .= $bericht;
}
$date = date('Y-m-d');
$fp = fopen("../includes/logs/chat/".$date.".txt","w");
fputs($fp, "$tekst");
fclose($fp);

mysql_query("TRUNCATE TABLE `chat`");

if(date("l") == "Sunday") {

    $selectsql = mysql_query("SELECT `id`, `date`, `sender`, `receiver`, `amount`, `what`, `type` FROM `bank_logs`");
    while ($select = mysql_fetch_array($selectsql)) {
        $bericht = $select['id'] . "|" . $select['date'] . "|" . $select['sender'] . "|" . $select['receiver'] . "|" . $select['amount'] . "|" . $select['what'] . "|" . $select['type'] . "\n";
        $tekst .= $bericht;
    }
    $date = date('Y-m-d');
    $fp = fopen("../includes/logs/bank/" . $date . ".txt", "w");
    fputs($fp, "$tekst");
    fclose($fp);

    mysql_query("TRUNCATE TABLE `bank_logs`");
}

if(date("l") == "Sunday") {

    $selectsql = mysql_query("SELECT * FROM shoutbox WHERE id NOT IN ( 
                              SELECT id 
                              FROM ( 
                                SELECT id 
                                FROM shoutbox 
                                ORDER BY id DESC 
                                LIMIT 60
                              ) x 
                            );");
    while ($select = mysql_fetch_array($selectsql)) {
        $bericht = json_encode($select)."\n";

        $tekst .= $bericht;
    }
    $date = date('Y-m-d');
    $fp = fopen("../includes/logs/shoutbox/" . $date . ".txt", "w");
    fputs($fp, "$tekst");
    fclose($fp);

    mysql_query("DELETE FROM shoutbox WHERE id NOT IN ( 
                 SELECT id 
                 FROM ( 
                   SELECT id 
                   FROM shoutbox 
                   ORDER BY id DESC 
                   LIMIT 60
                  ) x 
               );");
}