<?
session_start();

$_SESSION['packnaam'] = mysql_escape_string($_GET['name']);
$dbres = mysql_fetch_assoc(mysql_query("SELECT `id`, `naam`, `kosten`, COUNT(DISTINCT id) AS `aantal`,kosten_centen FROM `premium` WHERE `naam`='".$_SESSION['packnaam']."' GROUP BY `id`"));

$rtlo='';
$description="PokeWorld ".$_SESSION['packnaam'];
$amount=$dbres['kosten_centen'];
$returnurl="http://pokeworld.nl/index.php?page=premium/paysafecard&name=".$_SESSION['packnaam'];
$reporturl="http://pokeworld.nl/index.php?page=premium/paysafecard&name=".$_SESSION['packnaam'];

// De returnurl wordt aangeroepen. We moeten de status controleren
if( isset($_GET['ShoppingCartID'])){
    // 000000 OK Betekent succesvol. We kunnen het product leveren
    if( ($status = CheckReturnurl( $rtlo,  $_GET['ShoppingCartID'] )) == "000000 OK" ){
        // Zet uw orderinformatie in db
        $dbstart = mysql_query("INSERT INTO `premium_gekocht` (`datum`, `wie`, `wat`, `paycode`,`number`, `status`, `payment_id`, `verwerkt`)
                VALUES ('NOW()', '".$_SESSION['naam']."', '".$_SESSION['packnaam']."', 'paysafecard', '', 'completed', '', 1) ");

        require_once('add_premium.inc.php');
        add_premium($_SESSION['naam'], $_SESSION['packnaam']);
        $_SESSION['packnaam'] = '';
        die("Je hebt een " . $_SESSION['packnaam'] . " gekocht.");

    }
    // In de overige gevallen niet leveren.
    else die( $status );
}
// De reporturl wordt aangeroepen vanaf de targetpay server
elseif ( isset($_POST['trxid']) && isset($_POST['amount']) ){

    HandleReporturl( $_POST['trxid'], $_POST['amount'] );
} else{
    // Hier starten we met een redirect naar Paysafecard
    $redirecturl = StartTransaction( $rtlo, $description, $amount, $returnurl, $reporturl );
    header ("Location: ".$redirecturl);
    die();
}

// Opvragen redirecturl met transactienummer
function StartTransaction( $rtlo, $description,  $amount, $returnurl, $reporturl){
    $url= "https://www.targetpay.com/paysafecard/start?".
        "rtlo=".$rtlo.
        "&description=".urlencode(substr($description,0,32)).
        "&amount=".$amount.
        "&userip=".urlencode($_SERVER['REMOTE_ADDR']).
        "&returnurl=".urlencode($returnurl).
        "&reporturl=".urlencode($reporturl);

    $strResponse = httpGetRequest($url);
    $aResponse = explode('|', $strResponse );
    # Bad response
    if ( !isset ( $aResponse[1] ) ) die('Error' . $aResponse[0] );

    $responsetype = explode ( ' ', $aResponse[0] );

    $trxid = $responsetype[1];
    // Hier kunt u het transactienummer toevoegen aan uw order

    if( $responsetype[0] == "000000" ) return $aResponse[1];
    else die($aResponse[0]);
}

// Statusverzoek in de returnurl
function CheckReturnurl($rtlo, $trxid){
    $once=1;
    $test=0; // Set to 1 for testing as described in paragraph 1.3
    $url= "https://www.targetpay.com/paysafecard/check?".
        "rtlo=".$rtlo.
        "&trxid=".$trxid.
        "&once=".$once.
        "&test=".$test;
    return httpGetRequest($url);
}

// reporturl handler. Werk uw orderstatus bij naar succesvol.
// Deze aanroep komt van Targetpay.
// De consument heeft hier geen verbinding meer
function HandleReporturl($trxid, $amount ){
    if( substr($_SERVER['REMOTE_ADDR'],0,10) == "89.184.168" ||

        substr($_SERVER['REMOTE_ADDR'],0,9) == "78.152.58"
    ){
        // Werk hier uw status bij naar Succesvol.
        //reporturl hoort OK terug te geven naar Targetpay.
        die( "OK" );
    }else{
        die("IP address not correct... This call is not from Targetpay");
    }
}

function httpGetRequest($url){
    $ch = curl_init( $url );
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
    $strResponse = curl_exec($ch);
    curl_close($ch);
    if ( $strResponse === false )
        die("Could not fetch response " . $url );
    return $strResponse;
}
?>