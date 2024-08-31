<?php
 function rf_title_disp($lvl, $h1, $h2, $title,$gen) { $msg = ""; if ($h1 != "") { $msg .= "$h1 "; } if ($h2 != "") { $msg .= "$h2 "; } $msg .= $title; echo_prn($lvl, ""); echo_prn($lvl, str_repeat("=", 80)); echo_prn($lvl, $msg); $gen2 = trim($gen); if ($gen2 != ";" && $gen2 != "") { echo_prn($lvl, " (".$gen.")"); } echo_prn($lvl, str_repeat("=", 80)); } function rf_tail($fil) { $f = file($fil, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); $cnt = count_73($f); if ($cnt <= 0) return ""; $tail = $f[$cnt - 1]; unset($f); return $tail; } function rf_statistics($st_tm, $en_tm) { $st = date('Y/m/d H:i:s', $st_tm); $en = date('Y/m/d H:i:s', $en_tm); $dur = $en_tm - $st_tm; $hms = sec2hms($dur); echo_prn(1, "Start : $st  End : $en  Elapsed : $hms"); echo_prn(1, "[rfriends end]"); } function rf_network_check($url) { global $tmpdir; global $null_out; $fn = make_fn("network"); $output = $tmpdir."$fn.chk"; $out = $output." > $null_out 2>&1"; if (rf_wget($url, $out, "") === false) { fin_unlink($output); return false; } if (!file_exists($output)) { return false; } fin_unlink($output); return true; } function rf_pre_mg($ex_type, $mgn) { global $ex_radiko_pre_margin; global $radiru_pre_margin; global $ex_radiko; global $ex_radiru; global $ex_timefree; global $ex_radiru_vod; switch ($ex_type) { case $ex_radiko: case 3: $pre_mg = $ex_radiko_pre_margin - $mgn; break; case $ex_radiru: case 4: case 6: $pre_mg = $radiru_pre_margin - $mgn; break; case $ex_timefree: $pre_mg = $mgn; break; } return $pre_mg; } function rf_post_mg($ex_type, $mgn) { global $ex_radiko_post_margin; global $radiru_post_margin; global $ex_radiko; global $ex_radiru; global $ex_timefree; global $ex_radiru_vod; switch ($ex_type) { case $ex_radiko: case 3: $post_mg = $ex_radiko_post_margin + $mgn; break; case $ex_radiru: case 4: case 6: $post_mg = $radiru_post_margin + $mgn; break; case $ex_timefree: $post_mg = $mgn; break; } if ($post_mg < 0) { $post_mg = 0; } if ($post_mg > 59) { $post_mg = 59; } return $post_mg; } function rf_delay_mg($ex_type) { global $ex_radiko_delay; global $radiru_delay; global $ex_radiko; global $ex_radiru; global $ex_timefree; global $ex_radiru_vod; switch ($ex_type) { case $ex_radiko: case 3: $delay = $ex_radiko_delay; break; case $ex_radiru: case 4: case 6: $delay = $radiru_delay; break; case $ex_timefree: $delay = 0; break; } if ($delay < 0) { $delay = 0; } if ($delay > 59) { $delay = 59; } return $delay; } function dur_calc($ex_type, $duration, $mgn) { $pre_mg = rf_pre_mg($ex_type, $mgn); $post_mg = rf_post_mg($ex_type, $mgn); $reg_dur = $duration + ($pre_mg + $post_mg); return $reg_dur; } function recstime_calc($ex_type, $fromtime, $mgn) { $pre_mg = rf_pre_mg($ex_type, $mgn); $delay = rf_delay_mg($ex_type); $rec_stime = strtotime($fromtime) + $delay - $pre_mg; return $rec_stime; } function regtime_calc($ex_type, $fromtime, $mgn) { $pre_mg = rf_pre_mg($ex_type, $mgn); $delay = rf_delay_mg($ex_type); $fr = strtotime($fromtime); $diff = $delay - $pre_mg; if ($diff >= 0) { $reg_time = $fr; } else { $mg = floor((-$diff + 60)/60)*60; $reg_time = $fr - $mg; } return $reg_time; } function fr_calc($ex_type, $regtime, $mgn) { $pre_mg = rf_pre_mg($ex_type, $mgn); $delay = rf_delay_mg($ex_type); $diff = $delay - $pre_mg; if ($diff >= 0) { $fr = $regtime; } else { $mg = floor((-$diff + 60)/60)*60; $fr = $regtime + $mg; } return $fr; } function rf_play_file($fn) { global $edit_fn; global $edit_fnam; global $tmpdir; $ext = pathinfo($fn, PATHINFO_EXTENSION); switch ($ext) { case "dat": case "bat": case "sh": case "plist": case "log": case "txt": case "ini": $ret = rfgw_play_text($fn); break; case "m4a": case "mp3": case "aac": $ret = rfgw_play_snd($fn); break; default: $ret = 1; break; } return $ret; } function rf_get_editfn($no) { global $edit_fn; global $edit_fnam; global $tmpdir; $efn = ""; if ($no == 99) { $ppid = rfgw_get_ppid(); if ($ppid == -1) { return ""; } $fn = $tmpdir."edit_fnam_$ppid"; $efn = file_get_contents($fn); fin_unlink($fn); return $efn; } foreach ($edit_fn as $key => $val) { if ($val[0] == $no) { $efn = $val[1].$key; break; } } return $efn; } function slp_write_msg($fn, $nw, $msg) { global $rec_sleep_program; $dt = date("H:i:s", $nw); if ($rec_sleep_program == 1) { file_put_contents($fn, "$dt $msg".PHP_EOL, FILE_APPEND | LOCK_EX); } else { echo_prn(1, "$dt $msg"); } } function rf_rec_sleep($ex_type, $fnm, $ntp_mgn) { global $tmpdir; global $rfriends_ver; global $dont_sleep; global $dontsleep_timer; global $dontsleep_timer_tf; global $ex_radiko; global $ex_radiru; global $ex_timefree; global $ex_radiru_vod; $slp1 = $tmpdir."$fnm.slp"; $nw = time(); slp_write_msg($slp1, $nw, "rfriends_rec_sleep start"); $wdata = rf_get_wdata($fnm, $ex_type); $para = get_para($wdata, $ex_type); $fromtime = $para[0]; $totime = $para[1]; $duration = $para[2]; $recstime = recstime_calc($ex_type, $fromtime, 0); $mgn = 0; $reg_dur = dur_calc($ex_type, $duration, $mgn); if ($dont_sleep == 1) { $chk1 = time(); slp_write_msg($slp1, $chk1, "dontsleep start"); switch ($ex_type) { case $ex_timefree: $wtime = 0; $timer = $dontsleep_timer_tf; break; case $ex_radiru_vod: $wtime = 0; $timer = $dontsleep_timer_tf; break; case $ex_radiko: case $ex_radiru: default: $wtime = $recstime - $chk1; if ($wtime <= 0) { $wtime = 0; } $timer = $dontsleep_timer; break; } rfgw_start_dontsleep($ex_type, $fnm, $reg_dur, $timer, $wtime); $chk2 = time(); slp_write_msg($slp1, $chk2, "dontsleep started (wait time : $wtime)"); if (($chk2 - $chk1) > 5) { echo_msg(2, "dontsleep の起動に時間がかかっています。システムが不安定です。"); } } $mx = 70; $rmin = 10; $rmax = 30; $nw = time(); $reg_slp = $recstime - $nw; if ($reg_slp <= 0) { $nw = time(); slp_write_msg($slp1, $nw, "rfriends_rec_sleep end ($reg_slp) "); return 0; } if ($reg_slp >= $mx) { $tm = rand($rmin, $rmax); slp_write_msg($slp1, $nw, "sleep(random) $tm sec ..."); sleep($tm); $slp_flg = 20; while ($slp_flg > 0) { $nw = time(); $reg_slp = $recstime - $nw; if ($reg_slp < $mx) { break; } $reg_slp = $mx - 10; slp_write_msg($slp1, $nw, "sleep $reg_slp sec ..."); sleep($reg_slp); rfgw_rec_sleep($slp1); $slp_flg--; } } $mgn = 0; $nw = time(); $reg_slp = $recstime + $mgn - $nw; if ($reg_slp > 0) { slp_write_msg($slp1, $nw, "sleep $reg_slp sec ..."); sleep($reg_slp); } $nw = time(); slp_write_msg($slp1, $nw, "rfriends_rec_sleep end  "); return 0; } function rf_input_course($ityp, $pctl, $rmes, $nmax, $pmax, $mes, $course,$ttl0) { global $ui_mode; $nmax = 0; $flist = array(); foreach ($course as $cs) { $nmax++; $ttl = $cs -> title; if ($ui_mode == 2) { $flist[] = array('title' => $ttl,'val' => $nmax); } else { $tx = sprintf("%2d %s", $nmax, $ttl); echo_msg(2, $tx); } } if ($ui_mode == 2) { $opt = array( "title" => $ttl0, "mode" => 1, "multi" => 0, "confirm" => 0, "ht_selid" => "" ); ht_ask_list($flist,$opt); $no[0] = "r"; } else { echo_msg(2, ""); $no = rf_input($ityp, $pctl, $rmes, $nmax, $pmax, $mes); } return $no; } function rf_input($ityp, $pctl, $rmes, $nmax, $pmax, $mes) { global $scr_width; $lw = rf_calc_width(); $no = array(); $no2 = array(); $smes = "(1-$nmax)"; if ($ityp == 1) { if ($nmax == 1) { $smes = "(1)"; } if ($nmax == 2) { $smes = "(1,2 0:ALL)"; } if ($nmax > 2) { $smes = "(1,2,..,$nmax 0:ALL)"; } } $pmes = "[ret:$rmes] : "; if ($pctl == 1) { if ($pmax > 1) { $pmes = "[n:次 p:前 ret:$rmes] : "; } } $ln_mes = strlen($mes); $ln_smes = strlen($smes); $ln_pmes = strlen($pmes); echo_scr_n(2, $mes." ? "); $ln = $ln_mes + $ln_smes; if ($ln > $lw) { echo_scr(2, ""); $ln = $ln_smes; } echo_scr_n(2, $smes); if ($ln + $ln_pmes > $lw) { echo_scr(2, ""); } echo_scr_n(2, $pmes); $ans = read_stdin(); if ($ans == "") { $no[0] = "r"; return $no; } if ($pctl == 1 && $pmax > 1) { if ($ans == "p" || $ans == "n" || $ans == "t" || $ans == "b") { $no[0] = $ans; return $no; } } if ($ityp != 1) { if (!is_numeric($ans)) { $no2[0] = "e"; return $no2; } if ($ans < 1 || $ans > $nmax) { $no2[0] = "e"; return $no2; } $no2[0] = $ans; return $no2; } $no = rfmenu_check_digit($ans); if ($no == false) { $no2[0] = "e"; return $no2; } $cnt_no = count_73($no); if ($cnt_no < 1) { $no2[0] = "e"; return $no2; } if ($cnt_no == 1 && $no[0] == 0) { for ($i=0; $i<$nmax; $i++) { $no2[$i] = $i + 1; } return $no2; } $nox = array_unique($no); sort($nox); for ($i=0; $i<count_73($nox); $i++) { if ($nox[$i] < 1 || $nox[$i] > $nmax) { $no2[0] = "e"; return $no2; } } return $nox; } function rf_rmdir($dirn) { if (!is_dir($dirn)) return; $files = array_diff(scandir($dirn), array('.','..')); if (empty($files)) { echo_prn(1, "rmdir $dirn"); rmdir($dirn); } } function rf_get_csv($fl) { $db = array(); if (file_exists($fl)) { $lines = file($fl); foreach($lines as $line) { if (substr($line,0,1) == ";") continue; $dat = explode(",",$line); if (count_73($dat) < 2) continue; $key = (string)trim($dat[0]); $val = (string)trim($dat[1]); $db[$key] = $val; } } return $db; } function rf_batsh_rec($ex_type, $ex_subtype, $dt, $cnt, $wdat) { global $log_lifetime; global $ex_daily; global $ex_clean; global $ex_radiko; global $ex_radiru; global $ex_timefree; global $ex_radiru_vod; global $ex_radiru_gogaku; global $ex_podcast; global $tmpdir; global $scrdir; global $radiko_reserve_daily; global $radiru_reserve_daily; switch ($ex_type) { case $ex_daily: $v = explode(',',$ex_subtype); if (count_73($v) == 7) { $opt = "0 \"$ex_subtype\""; } else { $opt = ""; } break; case $ex_clean: $log_n = (string)$log_lifetime; $rsv_n = (string)$dt; $tmp_n = (string)$cnt; $opt = "1 \"0,1,$log_n,$rsv_n,$tmp_n\""; break; case $ex_radiko: $opt = "2 \"$ex_subtype,1,0,$radiko_reserve_daily,$radiru_reserve_daily\""; break; case $ex_radiru: $opt = "3 \"$ex_subtype,1,0,$radiko_reserve_daily,$radiru_reserve_daily\""; break; case $ex_timefree: switch ($ex_subtype) { case 0: $opt_fn = make_fn("timefree_kw"); $opt = "4 \"0,1,$opt_fn\""; rf_put_wdat_all_tmpdir($opt_fn, $wdat); break; case 1: $opt_fn = make_fn("timefree_kwdat"); $opt = "5 \"0,1,$dt,$cnt\""; break; } break; case $ex_radiru_vod: switch ($ex_subtype) { case 0: $opt_fn = make_fn("radiru_vod"); rf_put_wdat_all_tmpdir($opt_fn, $wdat); $nam = "rfriends_exec_radiru_vod_kw"; $opt = "9 \"$ex_type,0,0,0,$opt_fn,$nam\""; break; case 1: $opt_fn = ""; $nam = "rfriends_exec_radiru_vod"; $opt = "9 \"$ex_type,1,$dt,$cnt,$opt_fn,$nam\""; break; } break; case $ex_radiru_gogaku: switch ($ex_subtype) { case 0: $opt_fn = make_fn("radiru_vod"); rf_put_wdat_all_tmpdir($opt_fn, $wdat); $nam = "rfriends_exec_radiru_gogaku"; $opt = "10 \"$ex_type,0,0,0,$opt_fn,$nam\""; break; case 1: $opt_fn = ""; $nam = "rfriends_exec_radiru_gogaku"; $opt = "10 \"$ex_type,1,$dt,$cnt,$opt_fn,$nam\""; break; } break; case $ex_podcast: $opt = "12 \"\""; break; default: $opt = ""; return; break; } $ex = "ex_rfriends"; rfgw_batsh_sub($scrdir, $ex, $opt, 1, 1); } function rf_clear_stdin() { $in = fopen('php://stdin', 'r'); while (($ch = fgetc($in)) !== false) { } fclose($in); } function rf_compare_filesize($src,$dst) { $diff_size = 1000; $file_src_fs = filesize($src); $file_dst_fs = filesize($dst); $dif = abs($file_src_fs - $file_dst_fs)/1024; if ($dif > $diff_size) return false; return true; } function rf_move_delete_file($mvflg,$file_org,$move_dir,$fl_dst) { $fl_org = basename($file_org); if ($mvflg == 1) { rf_mkdir($move_dir); $ret = rename($file_org,$move_dir.$fl_dst); echo_msg(2,"move : $file_org"); return $ret; } else { $ret = unlink($file_org); echo_msg(2,"delete : $file_org"); return $ret; } } function rf_compare_delete_file($mvflg,$file_org,$dstdir) { if (!is_file($file_org)) return false; $fl_org = basename($file_org); $file_dst = $dstdir.$fl_org; if (!is_file($file_dst)) { return false; } if (rf_compare_filesize($file_org,$file_dst) === false) { echo_msg(2,"size not match : $file_dst"); return false; } $move_dir = $dstdir."same/"; $ret = rf_move_delete_file($mvflg,$file_dst,$move_dir,$fl_org); return $ret; } function rf_compare_delete_program($mvflg,$file_org,$orgdir,$dstdir,$ex_type) { if (!is_file($file_org)) return false; $fl_org = basename($file_org); $file_dst = rf_check_parts($ex_type,$dstdir, $fl_org); if (($file_dst !== false) && ($orgdir == $dstdir)) { $file_dst = rf_check_parts_self($file_dst,$file_org); } if ($file_dst === false) { return false; } if (count_73($file_dst) <= 0) return false; $move_dir = $dstdir."part/"; rf_mkdir($move_dir); $tcnt = 0; $fcnt = 0; foreach($file_dst as $fil) { if (rf_compare_filesize($file_org,$fil) === false) { echo_msg(2,"size not match : $fil"); $fcnt++; continue; } $fl_dst = basename($fil); $ret = rf_move_delete_file($mvflg,$fil,$move_dir,$fl_dst); if ($ret === false) $fcnt++; else $tcnt++; } if ($fcnt > 0) return false; return true; } function rf_compare_delete($mvflg,$orgdir,$dstdir,$flg,$ex_type) { global $rec_extension; $pat = "*"; $files_org = glob($orgdir.$pat); $cnt = 0; if ($flg == 0) { if ($orgdir == $dstdir) return 0; foreach($files_org as $file_org) { $ret = rf_compare_delete_file($mvflg,$file_org,$dstdir); if ($ret === true) $cnt++; } } else { foreach($files_org as $file_org) { $ret = rf_compare_delete_program($mvflg,$file_org,$orgdir,$dstdir,$ex_type); if ($ret === true) $cnt++; } } return $cnt; } 