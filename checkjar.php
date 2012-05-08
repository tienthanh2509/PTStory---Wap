<?
require_once('config.php');
$id=$_GET['id'];
$q="select * from mobileshare where id='$id';";
$que=mysql_query($q);
$qu=@mysql_fetch_array($que);
$file=$qu['file'];
$ftype=$qu['ftype'];

//Header
title_header('Kiểm tra tệp Jar: '.$file.' | '.$sitename);

echo "<div class='hl'>Kiểm tra tập tin java: ".$file." </div>";

if(!$ftype == 'jar'){
	echo "<i>Tệp tin bạn muốn giải nén không phải định dạng .Jar!</i><br/>";
	echo "Ấn vào <a href=\"file.php?id=$id\">đây </a> để quay lại.";
	//Footer
	title_footer();
	die;
	} else {
	//tao cache
	$dir_temp = md5(rand(1,32768));
	$dir_temp = 'cache/'.$dir_temp;
	mkdir($dir_temp);
	chmod($dir_temp,0777);

	if($ftype=="jar"){copy('uploads/'.$ftype.'/'.$file,$dir_temp.'/'.$file.'.zip'); $file.='.zip';}
	$file_temp = $dir_temp.'/'.$file;
	include_once "includes/pclzip.lib.php";

	$archive = new PclZip($file_temp);
	$rule_list[0] = 'META-INF/MANIFEST.MF';
	$list = $archive->extract(PCLZIP_OPT_PATH, $dir_temp,PCLZIP_OPT_BY_NAME, $rule_list);
	if ($list == 0) {
		echo "Lỗi rồi, tập tin không đúng định dạng<br>Hoặc tập tin đã bị hỏng!"/*.$archive->errorInfo(true)*/;
		//Footer
		title_footer();
		exit;
		}
	$content = file_get_contents($dir_temp.'/META-INF/MANIFEST.MF');

	echo 'Bung nén...<br />';

	if (preg_match('|SMSNum|',$content))
		{
			$x = explode('SMSNum',$content);
			$c_12121 = str_replace($x[0],'',$content);
			echo '<font color=red>=> Phát hiện SMS nguy hiểm, hãy cẩn thận</font>';
			echo '<br>Em nó nè: ';
			echo '<font color=blue>'.$c_12121.'</font>';
		} else {
			echo '<font color=green>Không phát hiện đối tượng khả nghi!</font>';
		}
			//Delete cache
			unlink($dir_temp.'/'.$file);
			unlink($dir_temp.'/META-INF/MANIFEST.MF');
			rmdir($dir_temp.'/META-INF/');
			rmdir($dir_temp);
		
		}
echo '<br /><font color=orange>Chú ý: Code không thể check được tuyệt đối, nên bạn hãy cẩn thận với những yêu cầu SMS!</font>';
//Footer
title_footer();
?>