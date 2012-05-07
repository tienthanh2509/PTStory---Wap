<?php
include('config.php');
$id=$_GET['id'];
$act=$_GET['act'];
$q="select * from mobileshare where id='$id';";
$q=mysql_query($q);
$q=@mysql_fetch_array($q);
$file=$q['file'];
$ftype=$q['ftype'];
if (!$file) {title_header('404');echo "<div class='hl'>404 Tệp không tồn tại</div>";title_footer();die();}

title_header('Xem tập tin nén: '.$file.' | '.$sitename);

echo "<div class='hl'>Xem tệp tin nén</div>";

if(!in_array($ftype,array("zip","jar"))){
echo "<i>TTệp tin này không phải là tệp tin nén chuẩn định dạng .zip, .jar!</i><br/>";
echo "Nhấn <a href=\"file.php?id=$id\">vào đây </a> để quay lại.";
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

include_once "includes/pclzip.lib.php";
//error_reporting(E_ALL);
$zip=new PclZip($dir_temp_1.'/'.$file);
$list=$zip->listContent();
$cnt=count($list);
$p=$_REQUEST['pg'];

if (($cnt<>NULL)&&($cnt<>1)) {$bln=true;
$v=15; $allp=ceil($cnt/$v);
if (($p==NULL)||($p==0)) {$p=1;} elseif ($p>$allp) {$p=$allp;}
$begin=$p*$v-$v; if ($begin>$cnt) {$begin=0;}
$end=$begin+$v; if ($end>$cnt) {$end=$cnt;}
for($i=$begin;$i<$end;$i++){
$fnm=$list[$i]['stored_filename'];
$fd=$list[$i]['folder'];
$size=$list[$i]['size'];
if ($size<1024) {$size="$size bytes";}
elseif (($size>=1024)&&($size<1048576)) {$size=strtr(round($size/1024,2),".",",")." KB";}
elseif ($size>=1048576) {$size=strtr(round($size/1024/1024,2),".",",")." MB";}
$csize=$list[$i]['compressed_size'];
if ($csize<1024) {$csize="$csize bytes";}
elseif (($csize>=1024)&&($csize<1048576)) {$csize=strtr(round($csize/1024,2),".",",")." KB";}
elseif ($csize>=1048576) {$csize=strtr(round($csize/1024/1024,2),".",",")." MB";}
if($fd<>1){$fd=false;}else{$fd=true;}
$mtime=$list[$i]['mtime'];
if($fd){$type="directory";}else{$type="file";}
$a=$i + 1;
echo "<b><font color='green'>Mục lục: </font></b> <i>#$a</i><br/>";
if($type=="file"){
$html=rawurlencode($fnm);
echo "<b><font color='green'>Tên: </font></b> <a href=\"extract.php?id=$id&amp;file=$html\">$fnm</a><br/>";}else{echo "<b><font color='green'>Name: </font></b> $fnm<br/>";}
echo "<b><font color='green'>Loại: </font></b> $type<br/>";
if(!$fd)echo "<b><font color='green'>Kích cỡ: </font></b> $size<br/>";
if(!$fd)echo "<b><font color='green'>Kích cỡ nén: </font></b> $csize<br/>";
echo "<b><font color='green'>Thời gian: </font></b> ".date("d-m-Y, H:i:s",$mtime)."<br/>";
echo "<hr size='1'>";
}
//navigation links
$bl="";
if ($p>1) {$v=$p-1; $bl.="<a href=\"viewarchive.php?pg=$v&amp;id=$id\">&lt;&lt;</a> | ";} elseif ($allp>$p) {$bl.="&lt;&lt; | ";}
 if ($allp>$p) {$v=$p+1; $bl.="<a href=\"viewarchive.php?pg=$v&amp;id=$id\">&gt;&gt;</a><br/>\r\n";} elseif ($p>1) {$bl.="&gt;&gt;<br/>\r\n";}
 if ($bl<>NULL) {$bl="Tổng số trang: $p/$allp<br/>$bl<form action=\"viewarchive.php?id=$id\" method=\"post\">Tới trang:<input name=\"pg\" type=\"text\" value=\"$p\" size=\"3\" style='-wap-input-format: \"5N\" '/><input type=\"submit\" value=\"Đi\"</a><br/>\r\n";}
echo $bl;
}//footer
echo "Nhấn <a href=\"file.php?id=$id\">vào đây</a> để trở lại thông tin tệp tin.";
//Footer
title_footer();

if($ftype=="jar"){unlink($dir_temp_1.'/'.$file); rmdir($dir_temp_1);}
}
?>