<?php
error_reporting(0);
if(!($dp = opendir("./"))) die ("Không thể mở ./");
$file_array = array();
while ($file = readdir ($dp)){if(substr($file,0,1) != '.' and $file != "index.php"){$file_array[] =  $file;}}
$file_count = count ($file_array);
sort ($file_array);
if ($file_count > 0)
{
while (list($fileIndexValue, $file_name) = each ($file_array))
{
$lenght=strlen($file_name);
$mtime=filemtime($file_name);
$now=time();
if(is_dir($file_name)){
if($lenght== 32){
if(!($dpa = opendir("$file_name"))) die ("Không thể mở ./");
$fd_array = array();
while ($fd = readdir ($dpa)){if(substr($fd,0,1) != '.' and $fd != "index.php"){$fd_array[] =  $fd;}}
$fd_count = count ($fd_array);
sort ($fd_array);
if ($fd_count > 0){while (list($fdIndexValue, $fd_name) = each ($fd_array)){$realpath=htmlspecialchars("$file_name/$fd_name"); $m=filemtime($realpath); if(($now - $m) > 60*15){unlink($realpath);} closedir($dpa);}}
if($lenght == 32){if(($now - $mtime) > 60*15){rmdir($file_name);}}}
}
}
}
closedir($dp);
?>
