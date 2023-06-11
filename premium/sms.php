<div style="width: 100%;text-align: center;">

<?
session_start();

function validatePincode($code,$pack){
    // Stel deze parameters in

    $packselect = mysql_fetch_assoc(mysql_query("SELECT `targetpay_keyword` FROM `premium` WHERE `naam`='".$pack."'"));
    $rtlo = "";       // Uw layoutcode
    $keyword= $packselect['targetpay_keyword'];  // Uw Keyword + subkeyword BETAAL AA
    $shortcode="3010";     // Shortcode
    $co= "31";             // Countrycode, 31=NL, 32=BE
    $test="0";

    $sRequest="http://www.targetpay.com/api/sms-pincode";
    $strParamString = "?rtlo=".$rtlo."&keyword=".$keyword.
        "&code=".$code."&shortcode=".$shortcode."&country=".$co.
        "&test=".$test;


    # get request
    $ch = curl_init($sRequest.$strParamString);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
    $strResponse = curl_exec($ch);
    curl_close($ch);

    return $strResponse;
}

$_SESSION['packnaam'] = mysql_escape_string($_GET['name']);
$dbres = mysql_fetch_assoc(mysql_query("SELECT `id`, `naam`, `kosten`, COUNT(DISTINCT id) AS `aantal`,targetpay_keyword FROM `premium` WHERE `naam`='".$_SESSION['packnaam']."' GROUP BY `id`"));

#Check of pack bestaat
if($dbres['aantal'] != 1){
    echo "Er is geen geldig pakket geselecteerd.";
}else {
    if ($_POST['pincode'] and $_SESSION['packnaam']) {
        $strResponse = validatePincode($_POST['pincode'], $_GET['name']);
        if($strResponse == "106 Pincode already checked or not paid"){
            echo "Pincode niet geldig.";
        }elseif($strResponse == "000 OK") {
            echo "Je hebt een ".$_SESSION['packnaam']." gekocht.";
            require_once('add_premium.inc.php');
            add_premium($_SESSION['naam'],$_GET['name']);
            $dbpaid = mysql_query("INSERT INTO `premium_gekocht` (`datum`, `wie`, `wat`, `paycode`,`number`, `status`, `payment_id`, `verwerkt`)
                VALUES ('NOW()', '".$_SESSION['naam']."', '".$_SESSION['packnaam']."', 'sms', '', 'completed', '', 1) ");
            $_SESSION['packnaam'] = '';
        }else{
            echo "Pincode niet geldig.";
        }
    } elseif (isset($_SESSION['packnaam']) and empty($_POST['pincode'])) {
        echo "SMS <b>".str_replace("+", " ", $dbres['targetpay_keyword'])."</b> naar 3010 om een ".$dbres['naam']." te kopen,<br/>vul daarna je ontvangen toegangscode hieronder in.<br/><br/>";
        echo "<FORM method=\"POST\" action=\"/index.php?page=premium/sms&name=".$_SESSION['packnaam']."\">";
        echo "toegangscode: <input type=\"text\" class=\"bar curved5\" name=\"pincode\">";
        echo "&nbsp;&nbsp;<input type=\"submit\" value='Verzenden'>";
        echo "</form>";
    } else {
        echo "Er is geen geldig pakket geselecteerd.";
    }
}

?>
</div>