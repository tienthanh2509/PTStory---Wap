<?php
if(!($dp = opendir('cache/'))) die ('Không thể mở ./');
$file_array = array();

while ($file = readdir ($dp)){if(substr($file,0,1) != '.' and $file != 'index.php'){$file_array[] =  $file;}}

$file_count = count ($file_array);
sort ($file_array);

if ($file_count > 0)
{
while (list($fileIndexValue, $filename) = each ($file_array))
{
$lenght=strlen('cache/'.$filename);
$mtime=filemtime('cache/'.$filename);
$now=time();
if(is_dir('cache/'.$filename)){
if($lenght > 10){
if(!($dpa = opendir('cache/'.$filename))) die ('Không thể mở ./');
$fd_array = array();
while ($fd = readdir ($dpa)){if(substr($fd,0,1) != '.' and $fd != 'index.php'){$fd_array[] =  $fd;}}
$fd_count = count ($fd_array);
sort ($fd_array);

if ($fd_count > 0){while (list($fdIndexValue, $fd_name) = each ($fd_array)){$realpath=htmlspecialchars('cache/'.$filename.'/'.$fd_name); $m=filemtime($realpath); if(($now - $m) > 60*1){unlink($realpath);} closedir($dpa);}}

if($lenght > 10){if(($now - $mtime) > 60*1){rmdir('cache/'.$filename);}}}
}
}
}
closedir($dp);
?>
