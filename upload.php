<?php
include('config.php');

$time = date("d.m.Y, H:i:s");
$Browser =  $_SERVER[HTTP_USER_AGENT];
$ip=$_SERVER[REMOTE_ADDR];
$desc=trim($_POST['desc']);
$user=trim($_POST['user']);
if(!$desc) $desc="Không có mô tả.";
if(!$user) $user="Khách";
// apare input-ul atata timp cat nu e Opera MIni 3.
if (preg_match("/Opera Mini/i", $Browser) && !preg_match("/Opera Mini\/1/i", $Browser) && !preg_match("/Opera Mini\/2/i", $Browser) && !preg_match("/Opera Mini\/3/i", $Browser) && !preg_match("/Opera Mini\/4/i", $Browser) && !preg_match("/Opera Mini\/5/i", $Browser)) $OperaMini = TRUE;
$no='no';


//Header
title_header('Tải lên | '.$sitename);

echo "<div class='hl'><img src=\"images/up.gif\" alt =\".\"/> Tải lên tệp tin</div>";

if($_GET[page] !=='add'){
echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"upload.php?page=add\">";
if ($OperaMini) {
echo "<input type='text' name='file1' size='30'/><a href='op:fileselect'>Browse files</a><br/>";
}
else {
echo "<input type=\"file\" name=\"filetoupload\"><br>";
}
echo "<input type=\"Submit\" name=\"uploadform\" value=\"Tải lên\">"; 
echo "(max. <b>".$mb."</b> MB)</div>";
echo "<div class=\"hl\">Thông tin</div>";
echo "<div class=\"txt\"><img src=\"images/user.gif\" alt =\".\"/> <b><font color=\"green\">Tên bạn:</font></b>";
echo "<br/><input type=\"text\" value=\"".$COOKIE["user"]."\" name=\"user\" size=\"30\" /><br/>";
echo "<div class=\"txt\"><img src=\"images/info.gif\" alt =\".\"/> <b><font color=\"green\">Miêu tả:</font></b>";
echo "<br/><textarea name=\"desc\" rows=\"4\" cols=\"40\"></textarea><br/>";
echo "<small><img src=\"images/warning.gif\" alt =\".\"/> Lưu ý: một khi bạn tải lên các tập tin, nó sẽ được công khai!!!</small>";
echo "<br/><b>Xin vui lòng chờ trong khi tệp tin được tải lên, có thể mất vài phút điều này tùy thuộc vào kích cỡ tệp tin và tốc độ đường truyền Internet của bạn.</b>";
echo "</div>";
echo "</form>";

}



if ($_REQUEST[file1]) {
$file1 = $_REQUEST[file1];
$file1 = explode('=', $file1);
$file_content = $file1[1];
$filename = str_replace('file','', $file1[0]);

$size = strlen($file_content);


}
else {

$filename = $_FILES['filetoupload']['name'];
$size = $_FILES['filetoupload']['size'];
}

$filename=str_replace(' ','-',$filename);
$filename=str_replace('`','',$filename);
$filename=str_replace(']','',$filename);
$filename=str_replace('[','',$filename);
$filename=str_replace('~','',$filename);
$filename=str_replace('@','',$filename);
$filename=str_replace('#','',$filename);
$filename=str_replace('%','',$filename);
$filename=str_replace('^','',$filename);
$filename=str_replace('*','',$filename);
$filename=str_replace('|','',$filename);
$filename=str_replace('$','',$filename);
$filename=str_replace('&lt;','',$filename);
$filename=str_replace('<','',$filename);
$filename=str_replace('>','',$filename);
$filename=str_replace('&gt;','',$filename);
$filename=str_replace('\'','',$filename);
$filename=str_replace("'",'',$filename);
$filename=str_replace("\\",'',$filename);
$filename=loc_dau_vn($filename);

//Check True Ext
$ext = strrchr($filename,'.');
$truext=str_replace('.','',$ext);
$truext=strtolower($truext);
$ext=strtolower($ext);
$upload_dir=htmlspecialchars('uploads/'.$truext.'/');


if($_GET['page']=='add'){

if (!$filename)
{
echo "<b><img src=\"images/warning.gif\" alt =\".\"/> Vui lòng chọn tệp tin tải lên</b><br />";
//Footer
title_footer();
die();
}


if (($extlimit == 'yes') && (in_array($ext,$limitedext)))
{
echo("<img src=\"images/warning.gif\" alt =\".\"/> Định dạng <b>$ext</b> bị tắt do lý do bảo mật. Các tập tin loại này KHÔNG ĐƯỢC tải lên!<br />");
view_limitedext();
//Footer
title_footer();
die();
}


if(strlen($filename)>$lungime_nume)
{
echo "<img src=\"images/warning.gif\" alt =\".\"/> <b><font color=\"red\">Tên tệp tin của bạn quá dài. Hãy đổi lại tên tệp tin!</font></b><br />";
//Footer
title_footer();
die();
}



if ($size > $size_bytes)
{
echo "<img src=\"images/warning.gif\" alt =\".\"/> <b><font color=\"red\">Kích thước tệp tin của bạn quá lớn. Tối đa được phép là $mb MB.</font></b><br />";
//Footer
title_footer();
die();
}


if (file_exists($upload_dir.'/'.$filename))
{
echo("<img src=\"images/warning.gif\" alt =\".\"/> <b><font color=\"red\">Tệp tin đã tồn tại. Hãy đổi tên tệp tin của bạn!</font></b><br />");
//Footer
title_footer();
die();
}

if ($file1) {

if(!is_dir($upload_dir)){mkdir($upload_dir); chmod($upload_dir,0777); copy('htaccess.txt',$upload_dir.'.htaccess');}

$fp = fopen($upload_dir.$filename, 'w') or die("<img src=\"images/warning.gif\" alt =\".\"/> Tệp tin không thể lưu lại vì máy chủ chưa cho phép");

fwrite($fp, base64_decode($file_content));
if (fclose($fp)) $Uploaded = TRUE;


}
else {
if(!is_dir($upload_dir)){mkdir($upload_dir); chmod($upload_dir,0777); copy('htaccess.txt',$upload_dir.'.htaccess');}
move_uploaded_file($_FILES['filetoupload']['tmp_name'], $upload_dir.$filename) or die("<img src=\"images/warning.gif\" alt =\".\"/> Không thể di chuyển tệp tin tải lên");
$Uploaded = TRUE;
}

if ($Uploaded)
{
mysql_query("INSERT INTO mobileshare (banned,file,user,browse,ip,ftype,uploaded,description,size) VALUES ('$no','$filename','$user','$Browser','$ip','$truext','$time','$desc','$size')");
$sql = mysql_query("SELECT * FROM mobileshare WHERE file = '$filename' ");

while($row = mysql_fetch_array($sql)){$id = $row['id'];}
echo ("
<img src=\"images/finish.gif\" alt =\".\"/><b><font color=\"red\">Tệp tin của bạn đã được tải lên. </font><a href=\"file.php?id=$id\">Tải về</a></b><img src=\"images/down.gif\" alt =\".\"/><br />
<u>Đường dẫn đến tệp tin:</u><br /><textarea name=\"link\" rows=\"5\" cols=\"40\">".$url."/file.php?id=".$id."</textarea><br />");
//Footer
title_footer();die();
} else {
echo "<b><font color=\"red\"><img src=\"images/warning.gif\" alt =\".\"/> Lỗi. Hãy thử lại.</font></b><br />";
//Footer
title_footer();
die();
}


}
//Footer
title_footer();
?>