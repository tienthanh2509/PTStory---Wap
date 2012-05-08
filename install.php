<?php
include('config.php');
error_reporting(E_ALL);
//Header
title_header("Thuật sĩ cài đặt | $sitename");

$lock_file = 'install.ptstory.lock';
if(file_exists($lock_file)) {
	echo 'Đầu tiên hãy xóa file "install.ptstory.lock" để tiếp tục install.';
	//Footer
	title_footer();
	die();
}

mysql_query("CREATE TABLE `keys` (
  `nr` int(10) NOT NULL auto_increment,
  `timestamp` int(20) NOT NULL default '0',
  `word` varchar(200) NOT NULL default 'no key',
  `fisier` varchar(200) NOT NULL default '',
  `expired` varchar(50) NOT NULL default 'no',
  PRIMARY KEY  (`nr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
echo "<div class='hl'>Lỗi xuất hiện ở đây:</div> ".mysql_error()."<br/>";

mysql_query("CREATE TABLE `mobileshare` (
  `id` int(10) NOT NULL auto_increment,
  `file` varchar(200) NOT NULL default '',
  `user` varchar(200) NOT NULL default 'guest',
  `description` varchar(250) NOT NULL default '',
  `size` int(50) NOT NULL default '0',
  `ftype` varchar(10) NOT NULL default '',
  `view` int(20) NOT NULL default '0',
  `download` int(20) NOT NULL default '0',
  `browse` varchar(200) NOT NULL default '',
  `uploaded` varchar(200) NOT NULL default '',
  `lastaccess` varchar(200) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  `pass` varchar(50) NOT NULL default '',
  `banned` varchar(50) NOT NULL default 'nu',
  `abuse` int(20) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
echo mysql_error()."<br />";
mysql_close($dbc);


echo "<div class='hl'>Tips:</div>";
echo "Nếu không có lỗi xuất hiện trên màn hình, cơ sở dữ liệu đã được cài đặt thành công<br/>Hãy xoá \"install.php\" ngay.";
$ghi =fopen($lock_file, 'w');
fwrite($ghi,'Đã cài thành công!');
fclose($ghi);
//Footer
title_footer();
?>