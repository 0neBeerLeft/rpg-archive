<?
$host = "localhost";
$username = "";
$password = "";
$dbname = "";

$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
try { $db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options); }
catch(PDOException $ex){ die("Failed to connect to the database: " . $ex->getMessage());}
$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
header('Content-Type: text/html; charset=utf-8');

$connection = mysql_connect($host, $username, $password);
@mysql_select_db($dbname);

date_default_timezone_set('Europe/Amsterdam');