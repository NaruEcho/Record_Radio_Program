<?php
 require_once("rf_inc.php"); require_once("rf_reserve.php"); require_once("rf_radiko.php"); require_once("rf_radiko2.php"); require_once("rf_radiko3.php"); require_once("rf_timefree.php"); require_once("rf_downloader.php"); require_once("rf_gdrive.php"); $rfriends_mes = "ラジオ録音ツール"; $test_mode = 0; $msg_level = 1; $kwdat = ""; if ($argc == 2) { $p = explode(",", $argv[1]); if (count_73($p) == 3) { $test_mode = $p[0]; $msg_level = $p[1]; $kwdat = $p[2]; } else { echo_prn(1, "parameter error"); exit; } } else { exit; } fr_system_info($ex_timefree); $st_tm = start_prn(1, "rfriends timefree KW Start"); echo_prn(1, " "); $timefreenw_dat = $tmpdir.$kwdat.".dat"; echo_prn(1, $timefreenw_dat); $i2 = timefree_disp($timefreenw_dat); $ret = timefree_kwrec_ex($timefreenw_dat, 0); echo_prn(2, " "); echo_prn(2, "[ 異常終了分を再実行します。]"); $i2 = timefree_disp($timefreenw_dat); $ret = timefree_kwrec_ex($timefreenw_dat, 1); fin_unlink($timefreenw_dat); $en_tm = start_prn(1, "rfriends timefree KW End"); rf_statistics($st_tm, $en_tm); exit; 