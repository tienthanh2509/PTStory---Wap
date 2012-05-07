<?php
include('config.php');
//Header
title_header('Tìm kiếm tệp | '.$sitename);
echo "<div class='hl'><img src=\"images/search.gif\" alt =\".\"/>Tìm kiếm</div>";

$mode=$_GET['mode'];
$type=$_GET['type'];
$key=$_REQUEST['key'];

if($mode==1){
	$fil=$_POST['select'];
	if($fil =="file"){$type="file";}else{$type="description";}
	}
if($type=="browser"){
	$bw="select browse from mobileshare where id='$key'";
	$b=mysql_query($bw);
	$b=mysql_fetch_array($b);
	$key=$b[0];
	$type="browse";
}
if(($_GET['act']) !=="search")
{
	echo "<form action='search.php?act=search&amp;mode=1' method='post'><input name='key' type='text' size='25'/><br/>";
	echo "Kiểu tìm kiếm:<br/>";
	echo "<select name=\"select\"><option value=\"file\">trong tên tệp</option><option value=\"desc\">trong miêu tả</option></select><br/>";
	echo "<input type='submit' value='Tìm!'/></form>";

}
if($_GET['act']=="search"){

	if (!$key)
	{
	echo "<b><img src=\"images/warning.gif\" alt =\".\"/>Hãy sử dụng từ khóa tốt hơn.</b>";
	echo "<form action='search.php?act=search&amp;mode=1' method='post'><input name='key' type='text' size='25'/><br/>";
	echo "Kiểu tìm kiếm:<br/>";
	echo "<select name=\"select\"><option value=\"file\">Trong tên tệp</option><option value=\"desc\">Trong miêu tả</option></select><br/>";
	echo "<input type='submit' value='Tìm!'/></form>";
	}
	else
	{
	// If current page number, use it
	// if not, set one!

	$pg=$_POST['pg'];
	if(!$pg){
	if(!isset($_GET['page'])){
	$page = 1;
	} else {
	$page = $_GET['page'];
	}
	}
	if($pg)$page=$pg;

	// Figure out the limit for the query based
	// on the current page number.
	$from = (($page * $max_results) - $max_results);

	// Perform MySQL query on only the current page number's results
	$sql = mysql_query("SELECT *  FROM `mobileshare` WHERE `$type` LIKE '%".$key."%' ORDER BY `id` desc LIMIT $from,$max_results;");
	echo "<b>Key: $key</b><br/>";
	while($row = mysql_fetch_array($sql)){
	// Build your formatted results here.
	$file = $row['file'];
	$user = $row['user'];
	$filename = $row['file'];
	$desc = $row['description'];
	$sz = $row['size'];
	$id = $row['id'];
	$ftype = $row['ftype'];
	$from = $from+1;
	if ($sz<1024) {$sz="$sz bytes";}
	elseif (($sz>=1024)&&($sz<1048576)) {$sz=strtr(round($sz/1024,2),".",",")." KB";}
	elseif ($sz>=1048576) {$sz=strtr(round($sz/1024/1024,2),".",",")." MB";}
	echo "<img src=\"images/dir.gif\" alt =\".\"/> $from.<a href=\"file.php?id=".$id."\">$filename</a>($sz)<br/>";

}

// Figure out the total number of results in DB:
$total_results=mysql_num_rows($sql);
$t=mysql_query("select count(*) as cn from mobileshare where $type like '%$key%'");
$t=mysql_fetch_array($t);
$total_results=$t['cn'];
if($total_results < 1){echo "<img src=\"images/warning.gif\" alt =\".\"/><font color=\"red\"><b>Không tìm thấy \"$key\" trong tài liệu nào. Hãy sử dụng từ khóa tốt hơn!</b></font>";}
if($total_results >= 1) echo "Tổng số kết quả: <b>$total_results</b>";
// Figure out the total number of pages. Always round up using ceil()
$total_pages = ceil($total_results / $max_results);
echo "<div class='hl'>Kết quả</div>";
// Build Previous Link
if($page > 1){
$prev = ($page - 1);
echo "<a href=\"search.php?act=search&amp;key=$key&amp;type=$type&amp;page=$prev\">&lt;&lt;Trước</a>"; }
// Build Next Link
if($page < $total_pages){
$next = ($page + 1);
echo "<a href=\"search.php?act=search&amp;key=$key&amp;type=$type&amp;page=$next\"> Next&gt;&gt;</a>";
}
echo "<br/>Trang $page của $total_pages<br/>";
if ($total_pages>2)
{
echo "<form method=\"post\" action=\"search.php?act=search&amp;key=$key&amp;type=$type\">Tới trang: <input type=\"text\" name=\"pg\" style='-wap-input-format: \"5N\" ' size=\"3\" value=\"$page\"/>";
echo "<input type=\"submit\" name=\"p\" value=\"Đi\"></form>";
}
//Footer
title_footer();
}
exit();
}

//Footer
title_footer();

?>
