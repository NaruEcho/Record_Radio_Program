<?php
 function fin_debug($ty,$p) { global $tmpdir; if ($ty != 1) return; $fl = $tmpdir."fin_debug.dat"; $dt = "$p \n"; file_put_contents($fl, $dt, FILE_APPEND | LOCK_EX); } require_once("rf_inc.php"); $rfriends_mes = "ラジオ録音ツール"; if ($argc != 4) { echo_prn(2, "rfriends_exec_fin parameter error"); exit(8); } $exno = $argv[1]; $fn1 = $argv[2]; $fn2 = $argv[3]; $ty = 0; fin_debug($ty,"----- $exno ".date('YmdHis')); fin_debug($ty,$fn1); fin_debug($ty,$fn2); if (!file_exists($fn1)) { exit(1); } rf_copy($fn1, $fn2); fin_wait_unlink($fn1); fin_mail($exno, 0, 0, "", $fn2, $fn2); fin_notify($exno, 0, 0, "", $fn2, $fn2); $pat = "{*.aac,*.err,*.flg,*.out,*.slp,*.tim,*.m4a,*.dat,*.can,*.end,*.skp,*.run}"; $tmp_n = 1; $ret = clear_log($tmp_n, $tmpdir, $pat, 1, 0, 0); $url = file($scrdir."update"); if ($url === false) { fin_debug($ty,"update error"); return; } $src = trim($url[0])."update/$rfriends".".flg"; $dst = $tmpdir.$rfriends; fin_unlink($dst); $exec_cmd = "wget --inet4-only -q -t 1 -T 5 $src -O $dst"; $ret = external_program($exec_cmd); if (file_exists($dst)) { fin_debug($ty,"ok"); } exit(0); 