<?php
include 'config.php';
$id=$_GET['id'];
$extracted=rawurldecode($_GET['file']);
$q="select * from mobileshare where id='$id';";
$que=mysql_query($q);
$qu=@mysql_fetch_array($que);
$file=$qu['file'];
$ftype=$qu['ftype'];

if (!$file) {title_header('404');echo "<div class='hl'>404 Tệp không tồn tại</div>";title_footer();die();}

//Header
title_header('Trích xuất tệp tin: '.$file.' | '.$sitename);

echo "<div class='hl'>Trích xuất tập tin: ".$extracted."</div>";
if(!in_array($ftype,array('zip','jar'))){
echo "<i>Tệp tin bạn muốn giải nén không phải định dạng .Zip hoặc .Jar!</i><br/>
Ấn vào <a href=\"file.php?id=$id\">đây </a> để quay lại.";
//Footer
title_footer();
} else {

//Check cache folder
if(!is_dir('cache/')){mkdir('cache/');chmod('cache/',0777);}

//Make cache
$dir_temp = md5(rand(1,32768));
$dir_temp_1 = 'cache/'.$dir_temp;
mkdir($dir_temp_1);
chmod($dir_temp_1,0777);

if($ftype=="jar"){copy('uploads/'.$ftype.'/'.$file,$dir_temp_1.'/'.$file.'.zip'); $file.='.zip';}
else {copy('uploads/'.$ftype.'/'.$file,$dir_temp_1.'/'.$file);}

include 'includes/pclzip.lib.php';
//error_reporting(E_ALL);
$zip=new PclZip($dir_temp_1.'/'.$file);
$time=time();
if(!is_dir('cache/extract/')){mkdir('cache/extract/');chmod('cache/extract/',0777);}
if(!is_dir('cache/extract/'.$time)){mkdir('cache/extract/'.$time);chmod('cache/extract/'.$time,0777);}
if($zip->extract(PCLZIP_OPT_BY_NAME,$extracted,PCLZIP_OPT_PATH,'cache/extract/'.$time.'/',PCLZIP_OPT_REMOVE_ALL_PATH)<>0){

$path=str_replace('/','',strrchr($extracted,'/'));
if ($path =='') {$path=$extracted;}

if(file_exists('cache/extract/'.$time.'/'.$path)){
$ex=strtolower(strrchr('extract/'.$time.'/'.$path,'.'));


if(in_array($ex,$limitedext)){copy('cache/extract/'.$time.'/'.$path,'cache/extract/'.$time.'/'.$path.'.txt');unlink('cache/extract/'.$time.'/'.$path);echo "File trích xuất thành công <a href=\"".$url."/cache/extract/".$time."/".$path.".txt\">ở đây</a> với tên và định dạng là: $path.txt.<br/>";}
else {echo "File trích xuất thành công <a href=\"".$url."/cache/extract/".$time."/".$path."\">ở đây</a> .<br/>";}

}}

//Delete cache file
unlink($dir_temp_1.'/'.$file);rmdir($dir_temp_1);

echo "Ấn vào <a href=\"file.php?id=$id\">đây</a> để quay lại.";
//Footer
title_footer();
}
?>