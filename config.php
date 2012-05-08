<?php
error_reporting(0);
//include function
include('includes/function.php');
//START CONFIGURATION
$lungime_nume = 80;//max length of a file

$size_bytes = 15360000;//max size,in bytes

$show_extlimit = 'yes'; //Show Ext limit yes or no 
$extlimit = 'yes';//allow all extensions?('yes' OR 'no')
$limitedext = array('.phtml', '.php', '.php3', '.php4', '.php5', '.php6', '.phps', '.cgi', '.pl', '.asp', '.aspx', '.shtml', '.shtm', '.fcgi', '.fpl', '.jsp', '.htm', '.html', '.wml', '.bat', '.sh');//list with banned extensions,with 'DOT' and in lowercase

$max_results = 20; //Max result in TOP, Download, Search

$db_host = 'localhost';  // DB Host
$db_user = 'root';  // DB User
$db_pass = '';  // DB Pass
$db_name = 'wap';  // DB Name
$dbc = @mysql_connect($db_host, $db_user, $db_pass) or die(mysql_error());
$dbs = @mysql_select_db($db_name) or die(mysql_error());
mysql_query("SET NAMES 'utf8'", $dbc);

$kb = $size_bytes / 1024;
$mb = $size_bytes / 1024000;

if(file_exists('remove.php')) include('remove.php');// del cache file

$homepage_url='http://localhost/public_html';
$url='http://localhost/server/public_html_/';//Full URL to your folder,no trailing slash
$admin='Tiến Thành'; //Admin names
$sitename = 'PTStory';
?>