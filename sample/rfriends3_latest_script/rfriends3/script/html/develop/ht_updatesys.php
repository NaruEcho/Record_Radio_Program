<?php
 $val = $_GET['val']; $url = rfmenu_sitecheck(); if ($url == false) { echo_msg(2,"サイトにアクセスできません。"); exit; } $url0 = $url."update3/"; $updb = rfmenu_update_db($url0,0); $upmax = count_73($updb); if ($upmax == 0) { echo_msg(2,"更新できません。"); exit; } $i = $val - 1; if ($i < 0 || $i >= $upmax) { echo_msg(2,"更新できません。"); exit; } $title = $updb[$i]['title']; $upval = $updb[$i]['val']; $upname = $updb[$i]['upname']; $rf_fl = $updb[$i]['rf_fl']; $up_fl = $updb[$i]['up_fl']; $up_fln = $updb[$i]['up_fln']; $upflg = $updb[$i]['upflg']; $update_ver = $updb[$i]['update_ver']; $update_dat = $updb[$i]['update_dat']; echo_msg(2,"$upname : $update_ver に更新します。"); echo_msg(2,""); if ($upflg == 0) { echo_msg(2,"更新ファイルがありません。（ $i ）"); exit; } $rpath = realpath($base."../"); $ftpass = ""; $up_fl = $updb[$i]['up_fl']; $update_dat = $updb[$i]['update_dat']; if ($svcmode["service_mode"] == 1 && $svcmode["service_update_beta"] == 1) { $ftpass = $svcmode["service_update_beta_mgc"]; } if ($ftpass != "") { $up_fl = $updb[$i]['up_fln']; } $ret = rf_update_script($url0, $update_dat, $up_fl, $rpath, $ftpass, 0); $ret0 = rfmenu_update_sys_ret($ret); rf_clear(); 