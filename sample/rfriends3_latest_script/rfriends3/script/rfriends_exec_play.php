<?php
 require_once("rf_inc.php"); $rfriends_mes = "ラジオ録音ツール"; global $base; $fl = file_get_contents($base."_Rfriends3"); echo_prn(2, $fl); echo_prn(2, ""); $st_tm = start_prn(1, "Rfriends Play Start (rfriends_exec_play)"); echo_prn(2, ""); if ($argc != 2) { echo_prn(2, "rfriends_exec_play parameter error"); exit(8); } $opt = $tmpdir.$argv[1].".dat"; if (!file_exists($opt)) { echo_prn(2, "rfriends_exec_play not found $opt"); exit(8); } $fl = file($opt); fin_unlink($opt); if (count_73($fl) < 1) { echo_prn(2, "rfriends_exec_play data error $opt"); exit(8); } $opt_fn = $fl[0]; if (!file_exists($opt_fn)) { echo_prn(2, "rfriends_exec_play not found $opt_fn"); exit(8); } echo_prn(2, "userbuf : $ffplay_userbuf sec"); if ($ffplay_userbuf == 0) { $rtbuf = ""; } else { $rtbuf = sprintf("-rtbufsize %4d",$ffplay_userbuf * 50)."K"; } $optx = "$rtbuf $ffplay_useropt"; $pl = "ffplay $optx -window_title rfriends_playfile"; $expgm = $pl.' "'.$opt_fn.'"'; echo_prn(2, $expgm); $ret = external_program($expgm); echo_prn(2, "ret : $ret"); $en_tm = start_prn(1, "Rfriends Play End"); rf_statistics($st_tm, $en_tm); exit(0); 