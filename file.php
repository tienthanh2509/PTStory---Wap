<?php
//Get data
include('config.php');
$getid=$_GET['id'];
$act=$_GET['act'];
$getkey=$_GET['key'];
if(strlen($getkey) ==32){$key=$_GET['key'];}
$sql = mysql_query("SELECT * FROM mobileshare WHERE id = '$getid' ");

while($row = mysql_fetch_array($sql))
{
$filename = $row['file'];
$user = $row['user'];
$pass = $row['pass'];
$desc = $row['description'];
$sz = $row['size'];
$id = $row['id'];
$ftype = $row['ftype'];
$banned = $row['banned'];
$dload = $row['download'];
$views = $row['view']+1;
$abuse = $row['abuse']+1;
$Browser = $row['browse'];
$ip = $row['ip'];
$uploaded = $row['uploaded'];
$lastaccess = $row['lastaccess'];

//Header
title_header('Thông tin tệp: '.$filename.' | '.$sitename);

echo "<div class='hl'><img src=\"images/info.gif\" alt =\"Thông tin\"/> Thông tin tệp: ".$filename."</div>";

if ($sz<1024) {$sz='$sz bytes';}
elseif (($sz>=1024)&&($sz<1048576)) {$sz=strtr(round($sz/1024,2),'.',',').' KB';}
elseif ($sz>=1048576) {$sz=strtr(round($sz/1024/1024,2),".",",").' MB';}

if(strlen($lastaccess)<5) $lastaccess=false;

if($desc=='') {$desc='Không có mô tả!';}

if(!$key){$timestamp=time(); $key=md5(rand(1,32768));}

if(!$act){mysql_query("INSERT INTO `keys` SET timestamp='$timestamp',word='$key',fisier='$filename' ");}

if($act=='cut'){echo "<img src=\"images/dir.gif\" alt =\"Tên tệp gốc\"/><b><font color=\"red\">Tệp tin gốc:</font></b><br/>";}

if(in_array($ftype,array('gif','jpg','jpeg','png'))) {echo "<p align='left'><img src=\"preview.php?id=$id\" alt=\"Xem trước ảnh $filename\"/><br/></p>";}

echo "<img src=\"images/dir.gif\" alt =\"Tên tệp\"/><b><font color=\"green\">Tên tệp tin:</font></b> $filename<br />";

//Check cache folder
if(!is_dir('cache/')){mkdir('cache/');chmod('cache/',0777);}

if($ftype=='jar') {echo "<a href=\"jad.php?id=$id\">[+JAD]</a><br />";}


//More info
if($act !=='more')
{
	echo "<img src=\"images/dir.gif\" alt =\"Kích thước tệp\"/><b><font color=\"green\">Kích thước tệp tin:</font></b> $sz<br />";
	
		if(in_array($ftype,array('zip','jar')))
		{
			include_once 'includes/pclzip.lib.php';
			if($ftype=='jar')
			{
				//Make cache
				$dir_temp = md5(rand(1,32768));
				$dir_temp_1 = 'cache/'.$dir_temp;
				mkdir($dir_temp_1);
				chmod($dir_temp_1,0777);
				copy('uploads/'.$ftype.'/'.$filename,$dir_temp_1.'/'.$filename.'.zip');
				$zip_file = $dir_temp_1.'/'.$filename.'.zip';
			}
			if($ftype=='zip'){$zip_file = 'uploads/'.$ftype.'/'.$filename;}
			$zip=new PclZip($zip_file);
			$list=$zip->listContent();
			$list=count($list);
			//Remove cache
			if($ftype=='jar'){unlink($zip_file);rmdir($dir_temp_1);}
			echo "<img src=\"images/dir.gif\" alt =\"Tệp tin nén\"/><b><font color=\"green\">Tệp tin nén:</font></b> $list [<a href=\"viewarchive.php?id=$id\">xem bên trong</a>]<br/>";
		}

		//GET size image
		if(in_array($ftype,array('gif','jpg','jpeg','png','bmp','wbmp','exif','tiff')))
		{
			$jpg=htmlspecialchars('uploads/'.$ftype.'/'.$filename);
			list($width,$height) = getimagesize($jpg);
			if($width > 1)
			{echo "<img src=\"images/dir.gif\" alt =\"Chiều rộng của ảnh\"/><b><font color=\"green\">Chiều rộng của ảnh: </font></b>$width pixels<br/>";}
			if($height > 1)
			{echo "<img src=\"images/dir.gif\" alt =\"Chiều cao của ảnh\"/><b><font color=\"green\">Chiều cao của ảnh: </font></b>$height pixels<br/>";}
		}

		if($banned !=='no')
		{
			echo "<font color=\"red\">Tập tin này đã bị cấm do vi phạm các <a href=\"terms.php\">điều khoản</a> .</font>";
			title_footer();
			die;
		}


		if($ftype=='mp3')
		{
			require_once('includes/id.php');
			$mp3=htmlspecialchars('uploads/'.$ftype.'/'.$filename);
			$id3 = new MP3_Id();
			$result = $id3->read($mp3);
			$result = $id3->study();
			print "<img src=\"images/dir.gif\" alt =\".\"/><b><font color=\"green\">Kiểu:</font></b> ".
			$id3->getTag('mode')."<br/>
			<img src=\"images/dir.gif\" alt =\".\"/><b><font color=\"green\">Chất lượng:</font></b> ".$id3->getTag('bitrate')." kbps<br/>
			<img src=\"images/dir.gif\" alt =\".\"/><b><font color=\"green\">Thời gian:</font></b> ".$id3->getTag('length')."<br/>
			<img src=\"images/dir.gif\" alt =\".\"/><b><font color=\"green\">Tần số:</font></b> ".$id3->getTag('frequency')." Hz<br/>";}
			echo "<img src=\"images/dir.gif\" alt =\".\"/><b><font color=\"green\">Mô tả:</font></b> $desc<br />";
			echo "<img src=\"images/user.gif\" alt =\".\"/><b><font color=\"green\">Tải lên bởi:</font></b> $user<br />";
			echo "<img src=\"images/dir.gif\" alt =\".\"/><b><font color=\"green\">Lượt tải về:</font></b> $dload<br />";
			echo "<img src=\"images/dir.gif\" alt =\".\"/><b><font color=\"green\">Lượt xem:</font></b> $views [<a href=\"file.php?act=more&amp;id=$id&amp;key=$key\">chi tiết</a>]<br/>";
			if($lastaccess) echo "<img src=\"images/dir.gif\" alt =\".\"/><b><font color=\"green\">Mới tải về:</font></b> $lastaccess<br/>";
		
}
}
		
		if($act=='more'){
		echo "<img src=\"images/dir.gif\" alt =\".\"/><b><font color=\"green\">Kích thước tệp tin:</font></b> $sz<br />";
		echo "<img src=\"images/dir.gif\" alt =\".\"/><b><font color=\"green\">Trình duyệt:</font></b> $Browser<br />";
		$real=htmlspecialchars('uploads/'.$ftype.'/'.$filename);
		$tc=filesize($real) * $dload;
		if ($tc<1024) {$tc='$tc bytes';}
		elseif (($tc>=1024)&&($tc<1048576)) {$tc=strtr(round($tc/1024,2),'.',',').' KB';}
		elseif ($tc>=1048576) {$tc=strtr(round($tc/1024/1024,2),'.',',').' MB';}
		echo "<img src=\"images/dir.gif\" alt =\".\"/><b><font color=\"green\">Băng thông đã dùng:</font></b> $tc<br />";
		echo "<a href=\"search.php?key=$user&amp;act=search&amp;type=user\">Xem tất cả các tệp tin được tải lên bởi $user</a><br/>";
		echo "<a href=\"search.php?key=$id&amp;act=search&amp;type=browser\">Xem tất cả tệp tin được tải lên bằng trình duyệt tương tự.</a><br/>";}

if($ftype=='mp3'){
echo "<div class='hl'><img src=\"images/good.gif\" alt =\".\"/>Cắt nhạc chuông MP3</div>";
$a=$_POST['a'];
$s=$_POST['s'];
$p=$_POST['p'];
$way=$_POST['way'];
require_once 'includes/id.php';
if(!isset($a)||empty($a)){
print"
<form action=\"file.php?id=$id&amp;key=$key&amp;act=cut\" align=\"center\" method=\"post\">
<img src=\"images/dir.gif\" alt =\".\"/>Kiểu cắt:<br/>
<select name=\"way\">
<option value=\"size\">theo kb</option>
<option value=\"time\">theo giây</option>
</select><br/>
<img src=\"images/dir.gif\" alt =\".\"/>Bắt đầu từ (kb hoặc giây):<br/>
<input type=\"text\" name=\"s\" style='-wap-input-format: \"5N\" ' /><br/>
<img src=\"images/dir.gif\" alt =\".\"/>Kết thúc tại (kb hoặc giây):<br/>
<input type=\"text\" name=\"p\" style='-wap-input-format: \"5N\" ' /><br/>
<input type=\"submit\" name=\"a\" value=\"Cắt\"/>
</form>";
}else{
$error = 0;

if($a='Cut'){
if(!isset($s)||empty($s)){print "<img src=\"images/warning.gif\" alt =\".\"/>Bạn chưa chọn giá trị bắt đầu!<br/>"; $error = 1;}
if(!isset($p)||empty($p)){print "<img src=\"images/warning.gif\" alt =\".\"/>Bạn chưa chọn giá trị kết thúc!<br/>"; $error = 1;}}
if($error==1){
print"
<form action=\"file.php?id=$id&amp;key=$key&amp;act=cut\" align=\"center\" method=\"post\">
<img src=\"images/dir.gif\" alt =\".\"/>Kiểu cắt:<br/>
<select name=\"way\">
<option value=\"size\">theo kb</option>
<option value=\"time\">theo giây</option>
</select><br/>
<img src=\"images/dir.gif\" alt =\".\"/>Bắt đầu từ (kb hoặc giây):<br/>
<input type=\"text\" name=\"s\" value=\"$s\" style='-wap-input-format: \"5N\" ' /><br/>
<img src=\"images/dir.gif\" alt =\".\"/>Kết thúc tại (kb hoặc giây):<br/>
<input type=\"text\" name=\"p\" value=\"$p\" style='-wap-input-format: \"5N\" ' /><br/>
<input type=\"submit\" name=\"a\" value=\"Cắt\"/>
</form>";}

if($error==0){
$path= 'uploads/mp3/'.$filename;
$randintval = rand(10000000,99999999).'.mp3';
$randintval= 'cache/cutter/'.$randintval;
$htaccess=htmlspecialchars('cache/cutter/.htaccess');
if(!is_dir('cache/cutter/')){mkdir('cache/cutter/'); chmod('cache/cutter/',0777); copy('htaccess.txt',$htaccess);}
copy($path,$randintval);
$fp = fopen($randintval, 'rb');
$raz = filesize($randintval);
$s = intval($s);
$p = intval($p);
if($way=='size'){
$s = $s*1024;
$p = $p*1024;
if($s>$raz||$s<0){$s = 0;}
if($p>$raz||$p<$s){$p = $raz;}}
else{
$id3 = new MP3_Id();
$result = $id3->read($randintval);
$result = $id3->study();
$byterate = $id3->getTag('bitrate')/8;
$secbit = $raz/1024/$byterate;
if($s>$secbit||$s<0){$s = 0;}
if($p>$secbit||$p<$s){$p = $secbit;}
$s = $s*$byterate*1024;
$p = $p*$byterate*1024;}
$p = $p-$s;
fseek($fp, $s);
$filefp = fread($fp, $p);
fclose($fp);
unlink($randintval);
$fp = fopen($randintval, 'xb');
if(!fwrite($fp, $filefp) === FALSE){
print "<b><img src=\"images/finish.gif\" alt =\".\"/>Thực hiện thành công!</b><br/>";
$sz=filesize($randintval);
if ($sz<1024) {$sz='$sz bytes';}
elseif (($sz>=1024)&&($sz<1048576)) {$sz=strtr(round($sz/1024,2),".",",")." KB";}
elseif ($sz>=1048576) {$sz=strtr(round($sz/1024/1024,2),".",",")." MB";}
echo "<img src=\"images/dir.gif\" alt =\".\"/><b><font color=\"green\">Kích thước tệp tin:</font></b> $sz<br/>";
$id2 = new MP3_Id();
$results = $id2->read($randintval);
$results = $id2->study();
print "<img src=\"images/dir.gif\" alt =\".\"/><b><font color=\"green\">Kiểu:</font></b> ".$id2->getTag('mode')."<br />
<img src=\"images/dir.gif\" alt =\".\"/><b><font color=\"green\">Chất lượng:</font></b> ".$id2->getTag('bitrate')." kbps<br/>
<img src=\"images/dir.gif\" alt =\".\"/><b><font color=\"green\">Thời gian:</font></b> ".$id2->getTag('length')."<br/>
<img src=\"images/dir.gif\" alt =\".\"/><b><font color=\"green\">Tần số:</font></b> ".$id2->getTag('frequency')." Hz<br/>";
$key=$_REQUEST['key'];
$ex=strrchr($randintval,'/');
$ex=str_replace('/','',$ex);
if(!is_dir('cache/'.$key)){mkdir('cache/'.$key); chmod('cache/'.$key,0777);} copy($randintval,'cache/'.$key.'/'.$ex); echo 'Tải xuống tệp tin đã cắt bằng cách <a href=\''.$url.'/cache/'.$key.'/'.$ex.'\'>nhấn vào đây</a> .<br/>Hãy nhớ, tệp tin chỉ tồn tại trong vòng 15 phút hoặc cho đến khi bạn download xong. Cảm ơn!<br/>';
}else{print "<img src=\"images/warning.gif\" alt =\".\"/>Lỗi: Tệp tin phải là định dạng mp3 <b>hợp lệ</b><br/>";}
fclose($fp); unlink($randintval);
}}}


if($filename==''){title_header('404 Page not found! | '.$sitename); echo "<img src=\"images/warning.gif\" alt =\".\"/><b>Tập tin không hợp lệ. Hãy kiểm tra địa chỉ cẩn thận, hoặc có thể tệp tin đã bị xóa vì vi phạm <a href=\"terms.php\" style=\"color:red\">điều khoản dịch vụ</a></b>"; title_footer(); die();}

if(!$act){mysql_query("UPDATE mobileshare SET view='$views' WHERE id='$id'");}

echo "<div class='hl'><img src=\"images/dir.gif\" alt =\"Share link\"/>Chia sẻ liên kết</div>";
echo "<a href=\"$url/file.php?id=$id\" style=\"color:red\">$url/file.php?id=$id</a><br/>\n";
echo "<input type=\"text\" size=\"35\" value=\"$url/file.php?id=$id\"/>";


echo "<div class='hl'><img src=\"images/down.gif\" alt =\"Tải xuống\"/>Tải xuống tệp tin</div>
<b><font color=\"red\"><img src=\"images/warning.gif\" alt =\".\"/> Cảnh báo: </font></b>
Link trực tiếp chỉ tồn tại trong 1 khoảng thời gian xác định!<br/>";

//Password system
if ($pass){
	if (!$_GET['pass'])
	{
echo "<font color=\"red\">Tệp tin này đã được bảo vệ bằng mật khẩu.</font><br />Hãy nhập mật khẩu để tải về:<br />
<form align=\"left\" action=\"".$_SERVER['PHP_SELF']."\" method=\"get\"><input type=\"hidden\" name=\"id\" value=\"$id\"><input type=\"password\" name=\"pass\" value=\"\">
<img src=\"images/down.gif\" alt =\"Mở khóa\"/><input type=\"submit\" name=\"post\" value=\"Mở khóa\"></form>";
}
else {
if ($_GET['pass'] == $pass) 
{
echo "Để tải xuống tệp tin, bạn vui lòng nhấn vào nút Tải về bên dưới.<br/><form align=\"left\" action=\"dlfile.php\" method=\"post\"><input type=\"hidden\" name=\"id\" value=\"$id\"><input type=\"hidden\" name=\"key\" value=\"$key\">
<img src=\"images/down.gif\" alt =\".\"/> <input type=\"submit\" name=\"dwn\" value=\"Tải về\"></form>";
} else	{
echo "<font color=\"red\">Bạn nhập sai mật khẩu.</font><br />Hãy nhập mật khẩu để tải về:<br />
<form align=\"left\" action=\"".$_SERVER['PHP_SELF']."\" method=\"get\"><input type=\"hidden\" name=\"id\" value=\"$id\"><input type=\"password\" name=\"pass\" value=\"\">
<img src=\"images/down.gif\" alt =\"Mở khóa\"/><input type=\"submit\" name=\"unlock\" value=\"Mở khóa\"></form>";
}
}
} else	{
echo "Để tải xuống tệp tin, bạn vui lòng nhấn vào nút Tải về bên dưới.<br/><form align=\"left\" action=\"dlfile.php\" method=\"post\"><input type=\"hidden\" name=\"id\" value=\"$id\"><input type=\"hidden\" name=\"key\" value=\"$key\">
<img src=\"images/down.gif\" alt =\".\"/> <input type=\"submit\" name=\"dwn\" value=\"Tải về\"></form>";
}

//Abuse System
echo "\r\n<div class='hl'><img src=\"images/warning.gif\" alt =\".\"/> Thông báo vi phạm</div>";
if($act !=="abuse"){echo "<b>Bạn có nghĩ rằng tập tin này là bất hợp pháp hay cần được loại bỏ? Nếu có, hãy <a href=\"file.php?id=$id&amp;act=abuse\">nhấn vào đây</a> để tự động thông báo cho quản trị viên. Cảm ơn!</b><br/>";}
if($act=="abuse"){
mysql_query("UPDATE mobileshare SET abuse='$abuse' WHERE id='$id' "); echo "<img src=\"images/finish.gif\" alt =\".\"/>Yêu cầu của bạn đã được ghi lại. Cảm ơn!";}
//Footer
title_footer();
?>