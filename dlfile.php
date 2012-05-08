<?php
//Get data
date_default_timezone_set('Asia/Ho_Chi_Minh');
$time = date("d.m.Y, H:i:s",time());
include('config.php');
$id=$_POST['id'];
$userkey=$_POST['key'];

if(!$id){
	//Header
	title_header('Tải xuống | '.$sitename);
	echo "<div class='hl'><img src=\"images/down.gif\" alt =\".\"/> Tải xuống tệp tin</div>";
	echo "<font color=\"red\">Mã tải xuống không hợp lệ.</font><br/>Bạn đã không yêu cầu bất kỳ tệp tin nào để tải xuống!<br/>";
	echo "Đường dẫn không được hỗ trợ.";
	//Footer
	title_footer();
	die();
}
$lenght=strlen($userkey);
if($lenght = 32){ $abc=true;}else{$abc=false;}

if(!$abc){
	//Header
	title_header('Tải xuống | '.$sitename);
	echo "<div class='hl'><img src=\"images/down.gif\" alt =\".\"/> Tải xuống tệp tin</div>";
	echo "<font color=\"red\">Mã tải xuống không hợp lệ.</font><br/>Hãy <a href=\"file.php?id=".$id."\">nhấn vào đây</a> để bắt đầu một phiên làm việc mới. Mã tải xuông không quá 32 ký tự.<br/>";
	//Footer
	title_footer();
	die();
}
$sql_key = mysql_query("SELECT * FROM `keys` WHERE `word` = '$userkey' "); 
while($row_key = mysql_fetch_array($sql_key)){
	$fisier=$row_key['fisier'];
	$timestamp=$row_key['timestamp'];
	$expired=$row_key['expired'];
	$key=$row_key['word'];
	}

if($expired !=='no'){
	//Header
	title_header('Tải xuống | '.$sitename);
	echo "<div class='hl'><img src=\"images/down.gif\" alt =\".\"/> Tải xuống tệp tin</div>";
	echo "<font color=\"red\">Phiên làm việc này đã hết hạn.</font><br/>Hãy <a href=\"file.php?id=".$id."\">nhấn vào đây</a> để bắt đầu một phiên làm việc mới.<br/>";
	//Footer
	title_footer();
	die();
}

$now=time();
$sql = mysql_query("SELECT * FROM `mobileshare` WHERE `id` = '$id' "); 
while($row = mysql_fetch_array($sql)){ 
	$file = $row['file'];
	$dload = $row['download'] + 1;
	$ftype = $row['ftype'];
}

if($timestamp){
		if(($timestamp - $now) > 60*15){
			$yes="yes";
			mysql_query("UPDATE `keys` SET `expired` = '$yes' WHERE `word` = '$userkey' ");
			//Header
			title_header('Tải xuống | '.$sitename);
			echo "<div class='hl'><img src=\"images/down.gif\" alt =\".\"/> Tải xuống tệp tin</div>";
			echo "<font color=\"red\">Phiên làm việc đã hết hạn.</font><br/>Hãy <a href=\"file.php?id=".$id."\">nhấn vào đây</a> để bắt đầu một phiên làm việc mới.<br/>";
			//Footer
			title_footer();
			die();
		}
	}

if(!$userkey){
	//Header
	title_header('Tải xuống | '.$sitename);
	echo "<div class='hl'><img src=\"images/down.gif\" alt =\".\"/> Tải xuống tệp tin</div>
	<font color=\"red\">Mã tải xuống không hợp lệ.</font><br/>Hãy <a href=\"file.php?id=".$id."\">nhấn vào đây</a> để bắt đầu một phiên làm việc mới.<br/>";
	//Footer
	title_footer();
	die();
}

if($key){
	$dir='cache/'.md5(rand(1,32768));
	$ext=strrchr($fisier,'.');
	$ext=str_replace('.','',$ext);
	$ext=strtolower($ext);
	$path=htmlspecialchars('uploads/'.$ext.'/'.$fisier);
	if(!is_file($path)){
		//Header
		title_header('Tải xuống | '.$sitename);
		echo "<div class='hl'><img src=\"images/down.gif\" alt =\".\"/> Tải xuống tệp tin</div>";
		echo "<font color=\"red\">Tệp tin mà bạn yêu cầu không tồn tại hoặc đã bị xóa.</font><br/>";
		//Footer
		title_footer();
		die();
	}

if(!is_dir($dir)){$newpath=htmlspecialchars($dir.'/'.$fisier); mkdir($dir); chmod($dir,0777); copy($path,$newpath);}
mysql_query("UPDATE `mobileshare` SET `download` = '$dload' WHERE `id` = '$id' ");
mysql_query("UPDATE `mobileshare` SET `lastaccess` = '$time' WHERE `id` = '$id' ");
mysql_query("UPDATE `keys` SET `expired` = '$time' WHERE `word` = '$key' ");
header('Location: '.$newpath);
}
?>