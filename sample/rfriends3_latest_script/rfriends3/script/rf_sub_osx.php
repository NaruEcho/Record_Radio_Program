<?php
 function rf_ps_osx($dat) { global $tmpdir; $op1 = strtolower("uid,pid,start,command"); $ex = "ps x -o $op1"; exec($ex." 2>/dev/null", $data, $ret); if ($ret != 0) { return false; } foreach ($data as $val) { if (strpos($val, $dat) !== false) { return $val; } } return false; } function rf_ffmpeg_pid_osx($mode) { global $tmpdir; $op1 = strtolower("uid,pid,start,command"); $ex = "ps x -o $op1"; exec($ex." 2>/dev/null", $data, $ret); $pid_data = array(); foreach ($data as $val) { $val2 = preg_replace('/\s+/', ' ', trim($val)); $dat = explode(" ", $val2); if (count_73($dat) < 4) { continue; } if (strpos($dat[3], "ffmpeg") === false) { continue; } $ret1 = rf_val_search($dat, $tmpdir); if ($ret1 === false) { continue; } $t = strtotime($dat[2]); $dat[2] = date("YmdHis", $t); $ret2 = rf_val_search($dat, "encoder"); if ($ret2 === false) { $encoder = "unknown"; } else { $encoder = $dat[$ret2]; } $fmt = "$dat[1],$encoder,$dat[2],$dat[$ret1]"; $pid_data[] = $fmt; } if ($mode != 1) { return $pid_data; } return $pid_data; } function rf_ffplay_pid_osx() { global $tmpdir; $op1 = strtolower("uid,pid,command"); $ex = "ps x -o $op1"; exec($ex." 2>/dev/null", $data, $ret); $pid_data = array(); foreach ($data as $val) { $val2 = preg_replace('/\s+/', ' ', trim($val)); $dat = explode(" ", $val2); $n = count_73($dat); if ($n < 3) { continue; } if (strpos($dat[2], "ffplay") === false) { continue; } if (($p = strpos($val2, "-window_title")) === false) { continue; } $val2 = substr($val2,$p); if (($p = strpos($val2, "rfriends")) === false) { continue; } $val3 = substr($val2,$p); $val4 = explode(" ",$val3,2); $ch = str_replace("rfriends_","",$val4[0]); $fil = $dat[$n-1]; $fmt = "$dat[1],$ch,$fil"; $pid_data[] = $fmt; } return $pid_data; } function rfmenu_config_osx() { global $ttl_no; global $ttl_mes; global $cfgdir; global $rf_stp; echo_msg(2, "毎日自動で行う処理の設定を行います。"); echo_msg(2, ""); $st = get_plist_crontab_osx(); if ($st === false) { echo_msg(2, "現在、デイリー処理は未登録です。"); } else { echo_msg(2, "現在、デイリー処理は $st[0]:$st[1] で登録済です。"); } echo_msg(2, ""); $mnu = array("登録","取消"); $msel = rf_sel_menu($mnu,1); if ($msel < 0) { return; } $ttl_no[0] = 3; $ttl_no[3] = $msel; $ttl_mes[3] = $mnu[$msel-1]; if ($msel == 1) { echo_msg(2, "デイリー処理(radiko,radiru,timefree)を登録します。"); echo_msg(2, "登録すると毎日自動で予約処理(radiko,radiru)と録音(timefree)を行います。"); echo_msg(2, ""); $ans = echo_yesno(2, "実行しますか? (y/N): "); if ($ans == "y" || $ans == "Y") { crontab_reserve_osx("on"); rf_pause(); } } if ($msel == 2) { if ($st === false) { rf_pause(); return; } echo_msg(2, "デイリー処理の登録を取り消します。"); echo_msg(2, ""); $ans = echo_yesno(2, "実行しますか? (y/N): "); if ($ans == "y" || $ans == "Y") { crontab_reserve_osx("off"); rf_pause(); } } } function at_reserve_osx($atqueno, $sttime, $bsn, $bs) { global $tmpdir; global $scrdir; global $DS; global $launchdir; global $launch_at_head; global $launch_at_template; global $macos_launch_type; $st = date("YmdHi", $sttime); $no = trim($atqueno); $fn = $launch_at_head."_".$no."_".$bsn; $fnm = $launchdir.$fn.".plist"; echo_prn(1, ""); if (file_exists($fnm)) { echo_prn(1,"already exist $fnm"); return 4; } $m = substr($st, 4, 2); $d = substr($st, 6, 2); $h = substr($st, 8, 2); $i = substr($st, 10, 2); switch ($macos_launch_type) { case 1: $sh = $scrdir."rfsh.app"; $str = sprintf($launch_at_template, $fn, $m, $d, $h, $i, "open", "-a", $sh, $bs); break; case 2: $sh = $scrdir."rfas.app"; $str = sprintf($launch_at_template, $fn, $m, $d, $h, $i, "open", "-a", $sh, $bs); break; default: $sh = "/bin/bash"; $str = sprintf($launch_at_template, $fn, $m, $d, $h, $i, $sh, $bs, "", ""); break; } file_put_contents($fnm, $str, LOCK_EX); $cmd = "launchctl   load ".$fnm; echo_prn(1, $cmd); system($cmd, $ret); if ($ret != 0) { $ret = 5; } return $ret; } function crontab_reserve_osx($ex) { global $tmpdir; global $scrdir; global $DS; global $sch_daily_h; global $sch_daily_m; global $sch_daily2; global $sch_daily_h2; global $sch_daily_m2; global $launchdir; global $launch_crontab_head; global $launch_crontab_template; global $launch_crontab_template2; $fn = $launch_crontab_head; $fnm = $launchdir.$fn.".plist"; $cmd_load = "launchctl load ".$fnm; $cmd_unload = "launchctl unload ".$fnm; if (file_exists($fnm)) { echo_prn(1, $cmd_unload); $ret = system($cmd_unload); fin_unlink($fnm); } if ($ex != "on") { return 0; } $bs = $scrdir."ex_rfriends.sh"; if ($sch_daily2 == "") { $str = sprintf($launch_crontab_template, $fn, $sch_daily_h, $sch_daily_m, $bs); } else { $str = sprintf($launch_crontab_template2, $fn, $sch_daily_h, $sch_daily_m, $sch_daily_h2, $sch_daily_m2, $bs); } file_put_contents($fnm, $str, LOCK_EX); echo_prn(1, $cmd_load); $ret = system($cmd_load); return $ret; } function get_plist_crontab_osx() { global $launchdir; global $launch_crontab_head; $flm = $launchdir.$launch_crontab_head.".plist"; if (!file_exists($flm)) { return false; } $plistxml = simplexml_load_file($flm); if ($plistxml === false) { return null; } $query = '/plist/dict/key[text()="StartCalendarInterval"]/following-sibling::*[1]'; $results = $plistxml->xpath($query); $query2 = '//key[text()="Hour"]/following-sibling::*[1]'; $results2 = $results[0]->xpath($query2); $st[0] = $results2[0]; $query3 = '//key[text()="Minute"]/following-sibling::*[1]'; $results3 = $results[0]->xpath($query3); $st[1] = $results3[0]; return $st; } function ifexist_plist_osx($atqueno, $typ, $flm) { global $launch_at_head; $n = strpos($flm, $launch_at_head); if ($n === false) { return false; } $fl = substr($flm, $n); $plist = get_plist_osx($atqueno, $typ); foreach ($plist as $pl) { if ($pl.".plist" == $fl) { return true; } } return false; } function get_plist_dir_osx($atqueno, $typ) { global $launchdir; global $launch_at_head; $head = $launch_at_head."_".$atqueno."_"; if (($flist = glob($launchdir.$head."*.plist")) === false) { $flist = array(); } if ($typ != 1) { return $flist; } $nw = time(); $time = 10*60; $flist2 = array(); foreach ($flist as $flm) { $ret = rf_sch_check_osx($flm, $nw, $tim); if ($ret == 0) { $flist2[] = $flm; } } return $flist; } function dispsch_osx($atqueno, $typ) { $n = 0; return $n; } function get_plist_osx($atqueno, $typ) { global $launchdir; global $launch_at_head; $head = $launch_at_head."_".$atqueno."_"; $ex_cmd = "launchctl list | grep $head"; $plist = external_exec($ex_cmd); if ($plist == null) { return array(); } $plist2 = array(); foreach ($plist as $val) { $val = preg_replace('/\s+/', ' ', trim($val)); $fl = explode(" ", $val); $fl2 = $fl[2]; $n = strpos($fl2, $head); if ($n === false) { continue; } if ($n != 0) { continue; } $con = trim($fl[0]); switch ($typ) { case 1: if ($con == "-") { $plist2[] = $fl2; } break; case 2: if ($con != "-") { $plist2[] = $fl2; } break; case 0: default: $plist2[] = $fl2; break; } } return $plist2; } function rf_shdatdel_osx($flm) { $shdata = rfplist_get_sh_osx($flm); if ($shdata != null) { $datdata = str_replace(".sh", ".dat", $shdata); fin_unlink($shdata); fin_unlink($datdata); } } function rf_launchdel_osx($atqueno, $flm) { global $launchdir; global $launch_at_head; $n = strpos($flm, $launch_at_head); if ($n === false) { return; } if (!file_exists($flm)) { echo_msg(2, "not found $flm"); return; } if (ifexist_plist_osx($atqueno, 1, $flm) === true) { $cmd_unload = "launchctl unload ".$flm; $str = system($cmd_unload, $ret); } rf_shdatdel_osx($flm); fin_unlink($flm); } function remove_illegal_launch_osx($atqueno) { global $launchdir; global $launch_at_head; $plist = get_plist_osx($atqueno, 1); if ($plist == null) { return; } foreach ($plist as $pl) { $flm = $launchdir.$pl.".plist"; if (!file_exists($flm)) { $exe_cmd = "launchctl remove $pl"; system($exe_cmd); } } } function rf_schdel_expire_osx($atqueno) { global $launchdir; global $launch_at_head; remove_illegal_launch_osx($atqueno); $flist = get_plist_dir_osx($atqueno, 0); $nw = time(); $tim = 10*60; $m = 0; foreach ($flist as $flm) { $n = strpos($flm, $launch_at_head); if ($n === false) { continue; } $ret = rf_sch_check_osx($flm, $nw, $tim); switch ($ret) { case 0: $wdata = rfplist_get_wdata_osx($flm); if ($wdata == null) { rf_launchdel_osx($atqueno, $flm); } break; case 1: rf_launchdel_osx($atqueno, $flm); $m++; break; case 2: break; default: break; } } return $m; } function rf_sch_check_osx($flm, $nw, $tim) { global $launchdir; global $launch_at_head; $parts = explode("_", $flm); $cnt = count_73($parts); if (($cnt != 6) && ($cnt != 7)) { return -1; } $t0 = $parts[2].$parts[3]; if (strlen($t0) != 14) { return -1; } $tm0 = strtotime($t0)+ $tim; $t1 = $parts[2].$parts[4]; if (strlen($t1) != 14) { return -1; } $tm1 = strtotime($t1)+ $tim; if ($tm0 > $tm1) { $tm1 += (60*60*24); } if ($tm1 < $nw) { return 1; } $t2 = $parts[2].$parts[3]; if (strlen($t2) != 14) { return -1; } $tm2 = strtotime($t2)- $tim; if ($tm2 < $nw) { return 2; } return 0; } function rfplist_get_sh_osx($flm) { global $launch_at_head; if (!file_exists($flm)) { return null; } $plistxml = simplexml_load_file($flm); if ($plistxml === false) { return null; } $query = '/plist/dict/key[text()="ProgramArguments"]/following-sibling::*[1]'; $results = $plistxml->xpath($query); $shdata = $results[0]->string[1]; return $shdata; } function rfplist_get_wdata_osx($flm) { $shdata = rfplist_get_sh_osx($flm); if ($shdata == null) { return null; } $datdata = str_replace(".sh", ".dat", $shdata); if (!file_exists($shdata) || !file_exists($datdata)) { return null; } $wdata = file_get_contents($datdata); return $wdata; } function get_schdata_osx($atqueno, $ex_type, $typ) { global $launchdir; global $launch_at_head; $plist = get_plist_osx($atqueno, $typ); $schdata = array(); foreach ($plist as $fl) { $flm = $launchdir.$fl.".plist"; $wdata = rfplist_get_wdata_osx($flm); if ($wdata != null) { $schdata[] = $wdata; } } return $schdata; } function get_schdata_cnt_osx_simple($atqueno) { global $launchdir; global $launch_at_head; $plist = get_plist_osx($atqueno, 1); $cnt = count_73($plist); return $cnt; } function rf_play_text_osx($fn, $flg) { global $tmpdir; global $editor; global $editor_cui; global $editor_gui; global $snd_player; global $editor_cui_osx; global $editor_gui_osx; global $snd_player_osx; if (!file_exists($fn)) { echo_msg(2, "file not found $fn"); return 1; } if ($flg == 0) { $pl = $editor_gui_osx; if ($pl == "") { $pl = $editor_gui; } rfgw_ret_extsys($pl, $fn); } else { $ppid = rfgw_get_ppid(); if ($ppid != -1) { $pl = $editor_cui_osx; if ($pl == "") { $pl = $editor_cui; } file_put_contents($editor, $pl, LOCK_EX); file_put_contents($tmpdir."edit_fnam_$ppid", $fn, LOCK_EX); $rf_stp = 99; exit($rf_stp); } } return 0; } function rf_play_snd_osx($fn, $flg) { global $editor_cui; global $editor_gui; global $snd_player; global $editor_cui_osx; global $editor_gui_osx; global $snd_player_osx; if (!file_exists($fn)) { echo_msg(2, "file not found $fn"); return 1; } if ($flg == 0) { $pl = $snd_player_osx; if ($pl == "") { $pl = $snd_player; } rfgw_ret_extsys($pl, $fn); } else { echo_msg(2, "This file cannot be played in this mode."); } return 0; } function rf_update_sub_tool_osx($rpath, $tmpdir_rf, $upbtxt_fl, $ty) { global $usrdir; global $tmpdir; echo_msg(2, ""); echo_msg(2, "番組録音中の更新は不可。"); echo_msg(2, ""); $ans = echo_yesno(2, "TOOL(osx)を更新しますか? (y/N): "); echo_msg(2, ""); if ($ans == "y" || $ans == "Y") { $ret = rfgw_update_bin($rpath, $ty); if ($ret == 0) { echo_msg(2, "更新成功"); } else { echo_msg(2, "更新失敗"); } rf_update_fin_tool(); echo_msg(2, ""); echo_msg(2, "一旦終了します。"); return(1); } else { return(0); } } function rf_update_bin_osx($rpath, $ty) { global $tmpdir; global $scrdir; global $base; global $rfriends; global $DS; $upbtxt_fl = "update_bin.txt"; $upbin_fl = "update_bin.zip"; $tmpdir_rf = $tmpdir."rfriends3"; $fl = $scrdir."up_tools_osx.sh"; if (file_exists($fl)) { echo_msg(2, "アップデート(bin)....."); $expgm = "sh $fl"; external_sys($expgm); } echo_msg(2, "アップデート(bin)完了"); return(0); } 