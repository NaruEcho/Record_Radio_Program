<?php
 function echo_debug($ty,$p) { global $tmpdir; if ($ty != 1) return; $fl = $tmpdir."echo_debug.dat"; $dt = "$p \n"; file_put_contents($fl, $dt, FILE_APPEND | LOCK_EX); } function echo_level_check($m_level) { global $msg_level; global $debug; $ret = false; if ($debug != 0) { switch ($msg_level) { case 0: $ret = true; break; case 1: if ($m_level >= 1) { $ret = true; } break; case 2: if ($m_level >= 2) { $ret = true; } break; } } return $ret; } function echo_make_mes1($msg) { global $ui_mode; global $rfsubtitle; global $rfmenu; $mes1 = ""; foreach($rfsubtitle as $m) { $mes1 .= "$m \n"; } if ($msg != "") { $mes1 .= "$msg \n"; } return $mes1; } function echo_make_mes2() { global $ui_mode; global $rfsubtitle; global $rfmenu; if (count_73($rfmenu) == 0) { echo_menu_ret_wt(); } $mes2 = array(); foreach($rfmenu as $m) { $p = explode(".",$m,2); $m1 = trim($p[0]); $m2 = trim($p[1]); $mes2[$m1] = $m2; } return $mes2; } function start_prn($m_level, $msg) { $tm = time(); $msg2 = "------- ".date('Y/m/d H:i:s', $tm). " : $msg"; echo_prn($m_level, ""); echo_prn($m_level, $msg2); return $tm; } function time_prn($m_level, $msg) { $tm = time(); $msg2 = date('H:i:s', $tm). " $msg"; echo_prn($m_level, $msg2); return $tm; } function echo_prn($m_level, $msg) { global $msg_level; global $debug; if (echo_level_check($m_level) === false) return; $dbg = ""; $sp = " "; echo $dbg.$sp.$msg.PHP_EOL; } function stat_prn($m_level, $msg) { $exeos = get_rfriends_exeos(); switch ($exeos) { case "LNX": $exec_cmd = "vmstat"; break; default: return; break; } exec($exec_cmd, $out, $ret); $tm = time(); $msg2 = date('H:i:s', $tm). " $msg"; echo_prn($m_level, $msg2); foreach ($out as $val) { echo_prn($m_level, $val); } return $tm; } function freem_prn($m_level, $msg) { $exeos = get_rfriends_exeos(); switch ($exeos) { case "LNX": $exec_cmd = "free -k"; break; default: return; break; } exec($exec_cmd, $out, $ret); $tm = time(); $msg2 = date('H:i:s', $tm). " $msg"; echo_prn($m_level, $msg2); foreach ($out as $val) { echo_prn($m_level, $val); } return $tm; } function cmd_prn($m_level, $msg, $exec_cmd) { $exeos = get_rfriends_exeos(); switch ($exeos) { case "LNX": break; default: return ""; break; } exec($exec_cmd, $out, $ret); if ($ret != 0) return false; $allout = ""; foreach ($out as $val) { $allout .= "\n".$val; } return $allout; } function echo_scr($m_level, $msg) { global $ui_mode; if (echo_level_check($m_level) === false) return; $dbg = ""; $sp = " "; switch($ui_mode) { case 0: echo $sp.$msg.PHP_EOL; break; case 2: echo "<li>$msg&nbsp;</li>".PHP_EOL; break; default: echo $sp.$msg.PHP_EOL; break; } } function echo_scr_n($m_level, $msg) { global $ui_mode; if (echo_level_check($m_level) === false) return; $dbg = ""; $sp = " "; switch($ui_mode) { case 0: echo $sp.$msg; break; case 2: echo "$msg"; break; default: echo $sp.$msg; break; } } function echo_wait($m_level, $msg) { global $rfsubtitle; global $rfmenu; echo_scr($m_level, ""); echo_scr_n($m_level, $msg); $ans = read_stdin(); $rfsubtitle = array(); $rfmenu = array(); return; } function rf_ppause($dat) { print_r($dat); rf_pause(); } function rf_pause() { global $ui_mode; global $rfsubtitle; global $rfmenu; if ($ui_mode == 0) { echo_wait(2, "Press ret key "); return; } echo_msgbox(2,""); } function echo_menu_all_wt() { global $rfmenu; global $ui_mode; if ($ui_mode == 0) { return; } if ($ui_mode == 2) { return; } $rfmenu[] = " 0. 全て"; } function echo_menu_ret_wt() { global $rfmenu; global $ui_mode; if ($ui_mode == 0) { return; } if ($ui_mode == 2) { return; } $rfmenu[] = " R. 戻る"; } function echo_menu_wt($msg) { global $rfmenu; global $ui_mode; if ($ui_mode == 0) { return; } if ($ui_mode == 2) { return; } $rfmenu[] = $msg; } function echo_menu($m_level, $msg,$nl) { global $rfmenu; global $ui_mode; if (echo_level_check($m_level) === false) return; $dbg = ""; $sp = " "; $newline = PHP_EOL; if ($nl == 0 ) { $newline = ""; } if ($ui_mode == 0) { echo $sp.$msg.$newline; } else if ($ui_mode == 2) { if ($msg != "") { $rfmenu[] = $msg; } } else { if ($msg != "") { $rfmenu[] = $dbg.$msg; } } } function start_msg($m_level, $msg) { $tm = time(); $msg2 = PHP_EOL."------- ".date('Y/m/d H:i:s', $tm). " : $msg"; echo_msg($m_level, $msg2); return $tm; } function time_msg($m_level, $msg) { $tm = time(); $msg2 = date('H:i:s', $tm). " $msg"; echo_msg($m_level, $msg2); return $tm; } function msgx($msg) { if ($msg != "") { echo $msg; } } function echo_msg($m_level, $msg) { global $rfsubtitle; global $ui_mode; if (echo_level_check($m_level) === false) return; $dbg = ""; $sp = " "; if ($ui_mode == 0) { echo $sp.$msg.PHP_EOL; } else if ($ui_mode == 2) { $msg2 = str_replace(" ", "&nbsp", $msg); echo "<li>".$msg2."&nbsp;</li>".PHP_EOL; } else { $rfsubtitle[] = $dbg.$msg; } } function echo_msg_temp($m_level, $msg) { global $rfsubtitle; global $ui_mode; if (echo_level_check($m_level) === false) return; $dbg = ""; $sp = " "; if ($ui_mode == 0) { echo $sp.$msg.PHP_EOL; } else if ($ui_mode == 2) { ; } else { $rfsubtitle[] = $dbg.$msg; } } function echo_fin($rf_stp) { global $msg_level; global $debug; global $rfsubtitle; global $rfmenu; global $ui_mode; global $rftitle; if ($rf_stp != 1) { return; } if ($ui_mode == 0) { return; } if ($ui_mode == 2) { return; } $mes1 = ""; foreach($rfsubtitle as $m) { $mes1 .= "$m \n"; } echo_msgbox(2, ""); return; } function echo_msgbox($m_level, $msg) { global $msg_level; global $debug; global $rfsubtitle; global $rfmenu; global $ui_mode; global $rftitle; global $wt_xmax; global $wt_xadd; global $wt_ymax; global $wt_yadd; if ($ui_mode == 0) { echo_scr($m_level,$msg); rf_pause(); return; } if ($ui_mode == 2) { echo_scr($m_level,$msg); return; } if (echo_level_check($m_level) === false) return; $dbg = ""; $sp = " "; $mes1 = ""; foreach($rfsubtitle as $m) { $mes1 .= "$m \n"; } $mes1 .= "$msg \n"; $y = count_73($rfsubtitle) + 1 + $wt_yadd; $scroll = ""; if ($y > $wt_ymax) { $y = $wt_ymax; $scroll = "--scrolltext"; } $x = $wt_xmax; $tcmd = "--title"; $ttl = $rftitle; $cmd = $scroll." --msgbox"; $ans0 = rfgw_whiptail($tcmd,$ttl,$cmd,$mes1,$y,$x,0,""); $rfsubtitle = array(); $rfmenu = array(); return; } function debug_msg($msg) { global $debug; if ($debug == 2) { echo "$msg".PHP_EOL; } } function echo_yesno($m_level, $msg) { global $rfsubtitle; global $rfmenu; global $ui_mode; global $rftitle; global $ht_jump_addr; global $ht_jump_no; global $wt_xmax; global $wt_xadd; global $wt_ymax; global $wt_yadd; if (echo_level_check($m_level) === false) return ""; $dbg = ""; $sp = " "; if ($ui_mode == 0) { echo $sp."$msg"; $ans = read_stdin(); $rfsubtitle = array(); $rfmenu = array(); return $ans; } if ($ui_mode == 2) { foreach($rfmenu as $m) { echo_msg(2,"rfmenu:$m"); } ht_yesno($msg); $ans = "n"; $rfsubtitle = array(); $rfmenu = array(); return $ans; } $mes1 = echo_make_mes1($msg); $n1 = count_73($rfsubtitle); if ($msg != "") $n1++; $scroll = ""; $y = $n1 + $wt_yadd; if ($y > $wt_ymax) { $y = $wt_ymax; $scroll = "--scrolltext"; } $x = $wt_xmax; $tcmd = "--title"; $ttl = $rftitle; $cmd = $scroll." --defaultno --yesno"; $ans0 = rfgw_whiptail($tcmd,$ttl,$cmd,$mes1,$y,$x,0,""); $rfsubtitle = array(); $rfmenu = array(); if ($ans0[0] == 0) { $ans = "y"; } else { $ans = "n"; } return $ans; } function echo_input($m_level, $msg) { global $rfsubtitle; global $rfmenu; global $ui_mode; global $rftitle; global $wt_xmax; global $wt_xadd; global $wt_ymax; global $wt_yadd; if (echo_level_check($m_level) === false) return ""; $dbg = ""; $sp = " "; if ($ui_mode == 0) { echo $sp."$msg"; $ans = read_stdin_k(); $rfsubtitle = array(); $rfmenu = array(); return $ans; } if ($ui_mode == 2) { ht_input($msg,0); $ans = ""; $rfsubtitle = array(); $rfmenu = array(); exit; return $ans; } $mes1 = echo_make_mes1(""); $n1 = count_73($rfsubtitle); $y = $n1 + $wt_yadd; if ($y > $wt_ymax) $y = $wt_ymax; $x = $wt_xmax; $cmd = "whiptail --title \"$rftitle\" --nocancel --inputbox \"$mes1\" $y $x 3>&1 1>&2 2>&3"; $ans = exec($cmd,$st); $rfsubtitle = array(); $rfmenu = array(); return $ans; } function echo_ask_sub($m_level, $msg) { global $rfsubtitle; global $rfmenu; global $ui_mode; global $rftitle; global $wt_xmax; global $wt_xadd; global $wt_ymax; global $wt_yadd; $mes1 = echo_make_mes1($msg); $mes2 = echo_make_mes2(); $n1 = count_73($rfsubtitle); if ($msg != "") $n1++; $n2 = count_73($rfmenu); $scroll = ""; $y = $n1 + $n2 + $wt_yadd; if ($y > $wt_ymax) { $y = $wt_ymax; $n2 = $y - $n1 - $wt_yadd; $scroll = "--scrolltext"; } $x = $wt_xmax; $tcmd = "--title"; $ttl = $rftitle; $cmd = $scroll." --menu"; $ans0 = rfgw_whiptail($tcmd,$ttl,$cmd,$mes1,$y,$x,$n2,$mes2); $rfsubtitle = array(); $rfmenu = array(); if ($ans0[0] == 0) { $ans = $ans0[1]; } else { $ans =""; } return $ans; } function echo_select($m_level, $msg) { global $msg_level; global $debug; global $ui_mode; if (echo_level_check($m_level) === false) return ""; $dbg = ""; $sp = " "; if ($ui_mode == 0) { echo $sp."$msg"; $ans = read_stdin(); $rfsubtitle = array(); $rfmenu = array(); } else if ($ui_mode == 2) { echo "<li>".$msg."</li>".PHP_EOL; $ans = "error"; $rfsubtitle = array(); $rfmenu = array(); } else { $ans = echo_ask_sub($m_level, ""); } return $ans; } function echo_ask($m_level, $msg) { global $msg_level; global $debug; global $ui_mode; global $wt_ymax; global $wt_xmax; global $wt_xadd; global $rfmenu; if (echo_level_check($m_level) === false) return; $dbg = ""; $sp = " "; if ($ui_mode == 0) { echo $sp."$msg"; $ans = read_stdin(); $rfsubtitle = array(); $rfmenu = array(); return $ans; } if ($ui_mode == 2) { $mode = 0; $nmax = count_73($rfmenu); if ($nmax == 0) { $no[0] = "z"; return $no; } if ($nmax == 1) { $no[0] = 1; return $no; } $fnn = strlen($nmax); $fmt = "%".$fnn."d "; $lw2 = $wt_xmax - $fnn - 1 - $wt_xadd; $lw2 = 80; $sz = 10; if ($sz > $nmax) $sz = $nmax; msgx("<select name='flist' size=$sz>".PHP_EOL); for ($i=1; $i<=$nmax; $i++) { $n = sprintf($fmt, $i); if ($mode == 0) { $f = $rfmenu[$i - 1]; } else { $f = $rfmenu[$i - 1]['title']; } $f = rf_strimwidth($f, 0, $lw2); msgx('<option value="$n">'."$f".'</option>'.PHP_EOL); } msgx('</select>'.PHP_EOL); echo "<li>".$msg."</li>".PHP_EOL; $ans = "r"; $rfsubtitle = array(); $rfmenu = array(); return $ans; } $ans = echo_ask_sub($m_level, $msg); return $ans; } function echo_ask_list($m_level, $msg, $msg2, $btn, $multi, $mode, $flist) { global $msg_level; global $debug; global $ui_mode; global $wt_ymax; global $wt_xmax; global $wt_xadd; global $rfmenu; global $ht_jump_addr; global $ht_jump_no; $n = count_73($flist); if ($n < 1) { return false; } if (echo_level_check($m_level) === false) return; $dbg = ""; $sp = " "; if ($ui_mode == 0) { echo_msg(2,""); echo $sp."$msg"; $ans = read_stdin(); $rfsubtitle = array(); $rfmenu = array(); return $ans; } if ($ui_mode == 2) { $opt = array( "title" => $msg2, "mode" => $mode, "multi" => $multi, "confirm" => 0, "ht_selid" => "" ); ht_ask_list($flist,$opt); $rfsubtitle = array(); $rfmenu = array(); return ""; } $ans = echo_ask_sub($m_level, $msg); return $ans; } 