<?php
require_once('config.php');

//Header
title_header('Trang chủ | '.$sitename);

echo "<div class='hl'>Danh mục chính</div>";

random_file();
view_limitedext();
menu_wap();
total_file();
total_size();

//Footer
title_footer();
?>