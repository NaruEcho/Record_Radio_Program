<?php
 function rf_clear() { global $base; global $scrdir; global $htmldir; $clist = array( $htmldir."phpinfo.html", $scrdir."config_sys_07.php", $scrdir."config_sys_08.php" ); foreach ($clist as $cl) { if (file_exists($cl)) { unlink($cl); } } $files = $scrdir."*.bak"; foreach (glob($files) as $val ) { unlink($val); } } 