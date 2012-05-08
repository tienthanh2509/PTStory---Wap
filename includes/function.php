<?php
//Tạo header
function title_header($text)
{
header('Cache-Control: no-cache, must-revalidate');
header('Content-type: text/html; charset=UTF-8');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>".$text."</title>
<link rel='stylesheet' href='style.css' type='text/css'/>
</head>
<body>
<table bgcolor=\"#B0DBED\" width=\"100%\">
<tr><td>
<font color='green' size='5'>PT</font><font color='blue' size='5'>Upload</font>
</td></tr>
</table>";
}

//Tạo Footer
function title_footer()
{
GLOBAL $homepage_url, $admin, $sitename;
echo "<div class='hl2'>
<img src=\"images/home.gif\" alt =\"Trang chủ ".$sitename."\"/> <a href=\"index.php\"><b>[Trang chủ]</b></a>
<img src=\"images/up.gif\" alt =\"Khu vực tải lên ".$sitename."\"/><a href=\"upload.php\"><b>[Tải lên]</b></a>
<div>
<table bgcolor=\"B0DBED\" width=\"100%\"><tr><td>Powered by <a href=\"".$homepage_url."\">".$admin."</a></td></tr></table>
</body>
</html>";
}

function random_file() 
{
echo "******<br/><strong><font color='black' size='5'>Tệp tin ngẫu nhiên</font></strong><br/>";
$query = "SELECT * from mobileshare ORDER BY RAND(" . time() . " * " . time() . ") LIMIT 1";
$result = mysql_query($query);
$num_rows = mysql_num_rows($result);
$row = mysql_fetch_array($result);
$file = $row['file'];
$user = $row['user'];
$filename = $row['file'];
$desc = $row['description'];
$size = $row['size'];
$id = $row['id'];
$ftype = $row['ftype'];
if ($size<1024) {$size="$size bytes";}
elseif (($size>=1024)&&($size<1048576)) {$size=strtr(round($size/1024,2),".",",")." KB";}
elseif ($size>=1048576) {$size=strtr(round($size/1024/1024,2),".",",")." MB";}
echo "<img src=\"images/dir.gif\" alt =\".\"/><a href=\"file.php?id=".$id."\">".$filename."</a>(".$size.")<br/>******<br/>";
}

function menu_wap()
{
echo "<img src=\"images/up.gif\" alt =\"Tải lên\"/><a href=\"upload.php\">Tải lên</a><br/>";
echo "<img src=\"images/new.gif\" alt =\"Mới\"/><a href=\"download.php\">Mới tải lên</a><br/>";
echo "<img src=\"images/topdow.gif\" alt =\"Top tải về\"/><a href=\"top.php\">Top tải về</a><br/>";
echo "<img src=\"images/search.gif\" alt =\"Tìm kiếm\"/><a href=\"search.php\">Tìm kiếm</a><br/>";
}

//Bộ lọc dấu tiếng việt
function loc_dau_vn($value)
{
$locdau_in = array ('#(A|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ|á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ)#','#(B)#','#(C)#','#(D|Đ|đ)#','#(E|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ|é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ)#','#(F)#','#(G)#','#(H)#','#(I|Í|Ì|Ỉ|Ĩ|Ị|í|ì|ỉ|ĩ|ị)#','#(J)#','#(K)#','#(L)#','#(M)#','#(N)#','#(O|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ|ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ)#','#(P)#','#(Q)#','#(R)#','#(S)#','#(T)#','#(U|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự|ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự)#','#(V)#','#(W)#','#(X)#','#(Ý|Ỳ|Ỷ|Ỹ|Ỵ|Y|ý|ỳ|ỷ|ỹ|ỵ|y)#','#(Z)#',); 
$locdau_out = array ('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',) ; 
$value = preg_replace($locdau_in, $locdau_out, $value); 
return $value;
}

function total_file()
{
$total = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM mobileshare"),0);
echo "<img src=\"images/tick.gif\" alt =\".\"/>Tổng số tệp tin: ".$total."<br/>";
}

function total_size()
{

$array=array();
$que=mysql_query("SELECT * FROM mobileshare ");
while($row=mysql_fetch_array($que)){$array[]=$row['size'];
$sz=array_sum($array);
if ($sz<1024) {$sz="$sz bytes";}
elseif (($sz>=1024)&&($sz<1048576)) {$sz=strtr(round($sz/1024,2),".",",")." KB";}
elseif ($sz>=1048576) {$sz=strtr(round($sz/1024/1024,2),".",",")." MB";}
}
echo "<img src=\"images/tick.gif\" alt =\".\"/>Tổng dung lượng: ".$sz."</i>";
}

function view_limitedext()
{
GLOBAL $limitedext,$show_extlimit;
	if ($show_extlimit == 'yes')
	{
		echo "Định dạng cấm: <b>\"".join(", ",$limitedext)."\"</b><br />";
	}
}
?>