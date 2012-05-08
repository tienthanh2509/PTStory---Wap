<?php
require_once('config.php');
$id=$_GET['id'];
$q="select * from mobileshare where id='$id';";
$que=mysql_query($q);
$qu=@mysql_fetch_array($que);

$jar = array
(
	'file' => $qu['file'],
	'ftype' => $qu['ftype'],
	'size' => $qu['size'],
);


if(!$jar['ftype']=='jar '){
//Header
title_header("Tạo Jad cho tệp: $file | $sitename");
echo "<div class='hl'>Tạo Jad cho tệp: $file</div>
<i>Tệp tin bạn muốn giải nén không phải định dạng .Jar hoặc _jar!</i><br/>
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

copy('uploads/jar/'.$jar['file'],$dir_temp_1.'/'.$jar[file].'.zip');

$jar_new = $dir_temp_1."/".$jar[file].".zip";

	include_once 'includes/pclzip.lib.php';
    $archive = new PclZip($jar_new);
    $rule_list[0] = 'META-INF/MANIFEST.MF';
    $list = $archive->extract(PCLZIP_OPT_PATH, $dir_temp_1, PCLZIP_OPT_BY_NAME, $rule_list);
    if ($list == 0) 
	{
	//Header
	header("Cache-Control: no-cache, must-revalidate");
	title_header("Tạo Jad cho tệp: $file | $sitename");
	echo "<div class='hl'>Tạo Jad cho tệp: $file</div>";
	echo "Lỗi rồi, tập tin không đúng định dạng: ".$archive->errorInfo(true);
	////Footer
	title_footer();
	exit;
    }

//Doi ten tep jar.zip thanh .jar
rename($jar_new,$dir_temp_1.'/'.$jar['file']);

//Doc & xoa tep MANIFEST.MF
$MANIFEST = $dir_temp_1.'/META-INF/MANIFEST.MF';
$content = file_get_contents($dir_temp_1.'/META-INF/MANIFEST.MF');
unlink ($MANIFEST);
rmdir ($dir_temp_1.'/META-INF');

$jadfile = $dir_temp_1.'/'.basename($jar['file'],'.jar').'.jad';
$fb = fopen($jadfile,'w');
$jad_info = $content.'
MIDlet-Jar-URL: '.$url.'/'.$dir_temp_1.'/'.$jar['file'].'
MIDlet-Jar-Size: '.$jar['size'].'';
fwrite($fb,$jad_info);fclose($fp);
//Dua ra KQ
header("Location: $jadfile");
}
?>