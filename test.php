<?php
/*echo phpinfo();*/

$file = fopen("sonam.txt", "a+");
fwrite($file, "trigger-".date('Y-m-d H:i:s')."\n");
fclose($file);
?>
