<?php 
include('config.php');
//Header
title_header('Top tải xuống| '.$sitename);
echo "<div class='hl'><img src=\"images/topdow.gif\" alt =\"Top tải về\"/>TOP ".$max_results."</div>";

$sql = mysql_query("SELECT * FROM mobileshare WHERE download ORDER BY download desc LIMIT ".$max_results.""); 

while($row = mysql_fetch_array($sql))
{ 
// Build your formatted results here.
$filename = $row['file'];
/*$user = $row['user'];*/
/*$desc = $row['description'];*/
$size = $row['size'];
$id = $row['id'];
/*$ftype = $row['ftype'];*/
$from = $from+1;
$dload = $row['download'];
if ($size<1024) {$size="$size bytes";}
elseif (($size>=1024)&&($size<1048576)) {$size=strtr(round($size/1024,2),".",",")." KB";}
elseif ($size>=1048576) {$size=strtr(round($size/1024/1024,2),".",",")." MB";}


echo "<img src=\"images/dir.gif\" alt =\"Tập tin ".$filename."\"/> $from.<a href=\"file.php?id=".$id."\"> $filename</a> ".$size." (<b>".$dload." lần</b>)<br />\n";

} 

//Footer
title_footer();
?>
