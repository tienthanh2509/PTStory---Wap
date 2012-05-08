<?php
include('config.php');
//Header
title_header('Mới tải lên | '.$sitename);

echo "<div class='hl'><img src=\"images/new.gif\" alt =\".\"/> Mới tải lên</div>";
$pg=$_POST['pg'];
if(!$pg){
if(!isset($_GET['page'])){ 
    $page = 1; 
} else { 
    $page = $_GET['page']; 
} 
}
if($pg){$page = $pg;}
// Figure out the limit for the query based 
// on the current page number. 
$from = (($page * $max_results) - $max_results); 
$sql = mysql_query("SELECT * FROM mobileshare ORDER BY id desc LIMIT $from,$max_results"); 
if(!$sql){ echo "Chưa có tệp tin nào được tải lên.<br/>";}
while($row = mysql_fetch_array($sql)){ 
// Build your formatted results here.
$filename = $row['file'];
$size = $row['size'];
$id = $row['id'];
$from = $from+1;

if ($size<1024) {$size="$size bytes";}
elseif (($size>=1024)&&($size<1048576)) {$size=strtr(round($size/1024,2),".",",")." KB";}
elseif ($size>=1048576) {$size=strtr(round($size/1024/1024,2),".",",")." MB";}

echo "<img src=\"images/dir.gif\" alt =\"Tập tin ".$filename."\"/> $from.<a href=\"file.php?id=".$id."\">$filename</a>(".$size.")<br/>\n";


    
    
} 
// Figure out the total number of results in DB: 
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM mobileshare"),0); 

// Figure out the total number of pages. Always round up using ceil() 
$total_pages = ceil($total_results / $max_results); 
if($page > $total_pages){echo "<img src=\"images/warning.gif\" alt =\".\"/> <b><font color=\"red\">Trang bạn yêu cầu không tồn tại.</font></b><br/><a href=\"uploads.php?pg=1\">Trở lại trang trước</a><br/><a href=\"uploads.php?pg=$total_pages\">Đến trang cuối($total_pages)</a>";

//Footer
title_footer();
die;
}
echo "<div class='hl'>Danh mục chính</div>";

// Build Previous Link 
if($page > 1){ 
    $prev = ($page - 1);
    echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$prev\">&#171; Trước</a>|"; 
} 
// Build Next Link 
if($page < $total_pages){ 
    $next = ($page + 1); 
    echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$next\">Sau &#187;</a><br/>"; 
} 

$i = 1; $i <= $total_pages; $i++ ;
echo "Trang $page của $total_pages<br/>";
if ($total_pages>2)
{
echo "<form method=\"POST\" action=\"".$_SERVER['PHP_SELF']."\">Tới trang: <input type=\"text\" name=\"pg\" style='-wap-input-format: \"5N\" ' size=\"3\" value=\"$page\"/>";
echo "<input type=\"submit\" name=\"p\" value=\"Đi\"></form>";
}
title_footer();

?>