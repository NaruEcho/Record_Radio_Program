<?php
 function rf_update_copy($dir, $src, $dst) { global $DS; global $base; global $base0; $ipath = realpath($base."../"); if (substr($ipath,-1) != $DS) { $ipath .= $DS; } if (substr($dst,-1) != $DS) { $dst .= $DS; } $ipath_name = str_replace($ipath,"",$base0); $sd = $src.$dir; $dd = $dst.$ipath_name; $exeos = get_rfriends_exeos(); switch ($exeos) { case "WIN": $exec_cmd = "xcopy /E /R /Y /I $sd $dd > nul "; break; case "OSX": case "LNX": $exec_cmd = "cp -rf $sd/* $dd > /dev/null "; break; default: echo "--- rfriends_exeos is not defined.\n"; exit(1); break; } system($exec_cmd, $ret); return($ret); } function rfgw_update_sub_tool($rpath, $tmpdir_rf, $upbtxt_fl, $ty) { global $ht_jump_val; global $ht_jump_val2; $ht_jump_val = $rpath; $ht_jump_val2 = $ty; $exeos = get_rfriends_exeos(); switch ($exeos) { case "WIN": $ret = rf_update_sub_tool_win($rpath, $tmpdir_rf, $upbtxt_fl, $ty); break; case "OSX": $ret = rf_update_sub_tool_osx($rpath, $tmpdir_rf, $upbtxt_fl, $ty); break; case "LNX": $ret = rf_update_sub_tool_lnx($rpath, $tmpdir_rf, $upbtxt_fl, $ty); break; default: echo "--- rfriends_exeos is not defined.\n"; exit(1); break; } return $ret; } function rfgw_update_bin($rpath, $ty) { $exeos = get_rfriends_exeos(); switch ($exeos) { case "WIN": $ret = rf_update_bin_win($rpath, $ty); break; case "OSX": $ret = rf_update_bin_osx($rpath, $ty); break; case "LNX": $ret = rf_update_bin_lnx($rpath, $ty); break; default: echo "--- rfriends_exeos is not defined.\n"; exit(1); break; } return $ret; } function rf_update_get_sys($url,$fl, $dir, $ty) { global $rfriends; $src = $url.$fl; $dst = $dir.$fl; fin_unlink($dst); $exeos = get_rfriends_exeos(); switch ($exeos) { case "WIN": $ret = rf_wget($src, $dst, ""); break; case "OSX": case "LNX": $ret = rf_wget($src, $dst, ""); break; default: echo "--- rfriends_exeos is not defined.\n"; exit(1); break; } return $ret; } function rfmenu_update_sys() { global $scrdir; $exeos = get_rfriends_exeos(); switch ($exeos) { case "WIN": $fl = $scrdir."update.bat"; if (file_exists($fl)) { $expgm = "$fl > NUL"; external_sys($expgm); } break; case "OSX": $fl = $scrdir."update_osx.sh"; if (file_exists($fl)) { $expgm = "sh $fl > /dev/null"; external_sys($expgm); } break; case "LNX": $fl = $scrdir."update.sh"; if (file_exists($fl)) { $expgm = "sh $fl > /dev/null"; external_sys($expgm); } break; default: echo "--- rfriends_exeos is not defined.\n"; exit(1); break; } } 