<?php
 function rf_factory_reset($testmode) { global $base; global $tmpdir; global $ex_radiko; global $ex_radiru; echo_msg(2,""); echo_msg(2,"・設定データをバックアップします。"); if ($testmode == 0) { $ret = rf_setting_backup(); if ($ret === false) { echo_msg(2,"設定データのバックアップに失敗しました。"); echo_msg(2, "done"); return; } } echo_msg(2,""); echo_msg(2,"・デイリー処理自動化を解除します。"); if ($testmode == 0) { rfgw_reset_cron(); } echo_msg(2,""); echo_msg(2,"・ラジコの予約を削除します。"); if ($testmode == 0) { rfmenu_rec_dsp($ex_radiko,1); } echo_msg(2,""); echo_msg(2,"・らじるの予約を削除します。"); if ($testmode == 0) { rfmenu_rec_dsp($ex_radiru,1); } echo_msg(2,""); echo_msg(2,"・実行中の録音をキャンセルします。"); if ($testmode == 0) { $n = rfmenu_rec_abort_all(); if ($n > 0) { echo_msg(2,""); echo_msg(2,"・3秒間待機します。"); sleep(3); } } echo_msg(2,""); echo_msg(2,"・各種一時データを削除します。"); $dirs = [ "tmp", "rsv", "rsv/sch", "ext", "etc", "config", ]; echo_msg(2,""); foreach ($dirs as $dir) { $dirx = $base.$dir; echo_msg(2,"$dirx"); if (is_dir($dirx)) { if ($testmode == 0) { rf_remove_dir_files("$dirx"); } } else { } } } function rf_setting_backup() { global $cfgdir; global $tmpdir; global $kwbackupdir; global $setting_name_dat; $setting = array(); foreach($setting_name_dat as $key => $setting_name) { $setting[] = $cfgdir.$key; } $st = time(); $dt = date("Ymd_His",$st); $src = "cfg_auto_".$dt.".zip"; $fl = $tmpdir.$src; $ret = rf_zip($setting,"cfg",$fl); if ($ret !== false) { $ret = rf_move($fl,$kwbackupdir.$src); echo_msg(2,$kwbackupdir.$src); } return $ret; } function rf_remove_dir_files($dir) { if (!is_dir($dir)) return; $files = array_diff(scandir($dir), array('.','..')); foreach ($files as $file) { if (is_dir("$dir/$file")) { rf_remove_dir_files("$dir/$file"); } else { unlink("$dir/$file"); } } } function rf_remove_dir($dir) { if (!is_dir($dir)) return; $files = array_diff(scandir($dir), array('.','..')); foreach ($files as $file) { if (is_dir("$dir/$file")) { rf_remove_dir("$dir/$file"); } else { unlink("$dir/$file"); } } rmdir($dir); } function rf_get_ver($dr) { global $rfriends; $rf_fl = $dr.$rfriends; if (!file_exists($rf_fl)) { retrun(""); } $rf = file_get_contents($rf_fl); $ver = explode(" ", trim($rf)); return ($ver[3]); } function rf_update_fin_sys() { global $tmpdir; $rf_fl = "_Rfriends3"; $up_fl = "update.zip"; $tmpdir_rf = $tmpdir."rfriends3"; fin_unlink($tmpdir.$up_fl); rm_dir($tmpdir_rf); return; } function rf_update_script($url,$update_dat, $up_fl, $rpath, $pn,$ty) { global $tmpdir; global $base; global $rfriends; global $DS; $tmpdir_rf = $tmpdir."rfriends3"; fin_unlink($tmpdir.$up_fl); $ret = rf_update_get_sys($url,$up_fl, $tmpdir, $ty); if ($ret === false) { return(2); } rm_dir($tmpdir_rf); $ret = rf_update_unzip($tmpdir.$up_fl, $tmpdir, $pn, 0); fin_unlink($tmpdir.$up_fl); if ($ret !== true) { rm_dir($tmpdir_rf); return(3); } $fl = $tmpdir_rf.$DS.$rfriends; if (!file_exists($fl)) { rm_dir($tmpdir_rf); return(4); } $update_dat2 = file_get_contents($fl); if ($update_dat != $update_dat2) { rm_dir($tmpdir_rf); return(5); } $ret = rf_update_copy("rfriends3", $tmpdir, $rpath); if ($ret == 0) { } else { $ret = 1; } rm_dir($tmpdir_rf); return($ret); } function rfmenu_sitecheck() { global $tmpdir; global $rfriends; global $ui_mode; $ty = 0; $rf = $rfriends.".flg"; $url = rf_get_down_url(0,$ty); if ($url === false) { echo_msg(2, "サイトが不明です。"); rf_pause(); return false; } $ret = rf_update_get_sys($url."update3/",$rf, $tmpdir, $ty); if ($ret !== false) { return $url; } $url = rf_get_down_url(1,$ty); if ($url === false) { echo_msg(2, "サイトが不明です。"); rf_pause(); return false; } $ret = rf_update_get_sys($url."update3/",$rf, $tmpdir, $ty); if ($ret !== false) { return $url; } echo_msg(2,"サイトがダウンしているか、変更になっているようです。"); echo_msg(2,"しばらくしてから、再度アクセスしてみてください。"); echo_msg(2, ""); if ($ui_mode == 2) return false; $ans = echo_yesno(2, "中止しますか？(Y/n): "); if ($ans != "n" && $ans != "N") { return false; } echo_msg(2, ""); $ans = echo_yesno(2, "新しいサイトを知っていますか？(y/N): "); if ($ans != "y" && $ans != "Y") { return false; } $url = echo_input(2, "新しいサイトのurlを入力してください : "); if ($url == "") { return false; } if (substr($url,-1) != "/") { $url .= "/"; } $ret = rf_update_get_sys($url."update3/",$rf, $tmpdir, $ty); if ($ret !== false) { return $url; } echo_msg(2,"新しいサイトのurlが間違っています。"); rf_pause(); return false; } function rfmenu_update_db($url,$ty) { global $rfriends; global $tmpdir; global $svcmode; $updb = array(); $updb[0] = array( 'upname' => "1.安定版　", 'rf_fl' => $rfriends."_0", 'up_fl' => "update_0.zip", 'up_fln' => "update_0n.zip", 'upflg' => 0, 'update_ver' => "", 'update_dat' =>"" ); $updb[1] = array( 'upname' => "2.開発版　", 'rf_fl' => $rfriends."_1", 'up_fl' => "update_1.zip", 'up_fln' => "update_1n.zip", 'upflg' => 0, 'update_ver' => "", 'update_dat' =>"" ); $updb[2] = array( 'upname' => "3.旧安定版", 'rf_fl' => $rfriends."_0x", 'up_fl' => "update_0x.zip", 'up_fln' => "update_0xn.zip", 'upflg' => 0, 'update_ver' => "", 'update_dat' =>"" ); $updb[3] = array( 'upname' => "4.旧開発版", 'rf_fl' => $rfriends."_1x", 'up_fl' => "update_1x.zip", 'up_fln' => "update_1xn.zip", 'upflg' => 0, 'update_ver' => "", 'update_dat' =>"" ); if ($svcmode["service_mode"] == 1) { if ($svcmode["service_update_beta"] == 1) { $updb[4] = array( 'upname' => "5.ベータ版", 'rf_fl' => $rfriends."_2", 'up_fl' => "update_2.zip", 'up_fln' => "update_2n.zip", 'upflg' => 0, 'update_ver' => "", 'update_dat' =>"" ); } } $imax = count_73($updb); for ($i=0; $i<$imax; $i++) { fin_unlink($tmpdir.$updb[$i]['up_fl']); fin_unlink($tmpdir.$updb[$i]['up_fln']); $ret = rf_update_get_sys($url,$updb[$i]['rf_fl'], $tmpdir, $ty); if ($ret === true) { $updb[$i]['upflg'] = 1; $updb[$i]['update_dat'] = file_get_contents($tmpdir.$updb[$i]['rf_fl']); $v = explode(" ", trim($updb[$i]['update_dat'])); $updb[$i]['update_ver'] = $v[3]; } else { $updb[$i]['upflg'] = 0; } } if ($svcmode["service_mode"] == 1) { if ($svcmode["service_update_forbid"] == 1) { for ($i=0; $i<$imax; $i++) { $updb[$i]['upflg'] = 0; } } } for ($i=0; $i<$imax; $i++) { $updb[$i]['upstr'] = $updb[$i]['upname']." : ".$updb[$i]['update_ver']; if ($updb[$i]['upflg'] == 1) { $updb[$i]['title'] = $updb[$i]['upstr'].""; $updb[$i]['val'] = $i+1; } else { $updb[$i]['title'] = $updb[$i]['upstr']."(停止中)"; $updb[$i]['val'] = 0; } } return $updb; } function rfmenu_update_sys_ret($ret) { global $base; switch ($ret) { case 0: echo_msg(2, "アップデートに成功しました。"); break; case 1: echo_msg(2, "アップデートに失敗しました。"); break; case 2: echo_msg(2, "アップデートファイルがありません。"); break; case 3: echo_msg(2, "アップデートファイルが異常です。"); break; case 4: echo_msg(2, "アップデートファイルの内容が正しくありません。"); break; case 5: echo_msg(2, "アップデートファイルのバージョンが一致しません。"); break; case 8: echo_msg(2, "アップデートがありません。"); break; case 9: echo_msg(2, "入力が間違っています。"); break; case 10: echo_msg(2, "初期化が終了しました。"); echo_msg(2, ""); echo_msg(2, "一旦終了します。"); return(1); break; case 11: echo_msg(2, "初期化が異常終了しました。"); echo_msg(2, ""); echo_msg(2, "一旦終了します。"); return(1); break; case 99: return 0; break; default: break; } return 0; } function rfmenu_update_sub($ty) { global $usrdir; global $tmpdir; global $cfgdir; global $base; global $rfriends; global $DS; global $os_s; global $svcmode; global $ui_mode; global $ht_jump_addr; global $ht_jump_val2; global $ht_jump_confirm; $rfriends_ver = trim(file_get_contents($base.$rfriends)); $ver = explode(" ", $rfriends_ver); $dmode = 0; $rpath = realpath($base."../"); if (substr($rpath,-1) != $DS) { $rpath .= $DS; } echo_msg(2, "システム更新(SYS) Ver.1.20"); $fr = disk_free_space($base); $fr = floor($fr / (1024*1024)); echo_msg(2,""); echo_msg(2, "free space : $fr MB"); if ($fr < 30) { echo_msg(2, "十分な空き容量がありません。"); rf_pause(); return 0; } $url = rfmenu_sitecheck(); if ($url == false) { return 0; } $url0 = $url."update3/"; $ht_jump_val2 = $url0; $ret = phpzip(); if ($ret == 1) { return($ret); } $tmpdir_rf = $tmpdir."rfriends3"; $ty = 0; $updb = rfmenu_update_db($url0,$ty); $upmax = count_73($updb); if ($upmax == 0) { return 0; } echo_scr(2, ""); echo_msg(2, "番組録音中のアップデートは失敗する可能性があります。"); echo_scr(2, ""); echo_msg(2, " OS  : $os_s"); echo_msg(2, " 現在: $ver[3]"); echo_msg(2, " site: $url0"); if ($ui_mode == 2) { $ht_jump_confirm = "アップデートしますか？"; echo_msg(2, ""); $opt = array( "title" => "アップデートを選択", "mode" => 1, "multi" => 0, "confirm" => 1, "ht_selid" => "" ); ht_ask_list($updb,$opt); return 0; } echo_scr(2, ""); for ($i=0;$i<$upmax;$i++) { if ($i == 2) echo_msg(2,""); echo_menu(2, $updb[$i]['title'],1); } echo_menu_ret_wt(); echo_msg(2, ""); $ans0 = echo_ask(2, "アップデートを選択(1-$upmax): "); if ($ans0 == "" || $ans0 == "R" || $ans0 == "r") { return 0; } echo_scr(2, ""); $nox = rfmenu_check_range($ans0,1,$upmax); if ($nox === false) { return 0; } $i = $nox - 1; $upflg = $updb[$i]['upflg']; if ($upflg != 1) { $ret0 = rfmenu_update_sys_ret(8); rf_pause(); return $ret0; } $ftpass = ""; $up_fl = $updb[$i]['up_fl']; $update_dat = $updb[$i]['update_dat']; if ($svcmode["service_mode"] == 1 && $svcmode["service_update_beta"] == 1) { $ftpass = $svcmode["service_update_beta_mgc"]; } if ($ftpass != "") { $up_fl = $updb[$i]['up_fln']; } echo_msg(2,$rpath); $ret = rf_update_script($url0, $update_dat, $up_fl, $rpath, $ftpass, $ty); $ret0 = rfmenu_update_sys_ret($ret); if ($ret == 0) { fin_unlink($base."skipfile"); $mes = rfmenu_update_para_all_auto(); } rf_pause(); return $ret0; } function rf_update_inifile($mode,$fl_sys,$fl_usr,$ini_sys,$ini_usr) { global $cfgdir; $ini_dat = array_merge($ini_sys, $ini_usr); if ($mode == 1) { $ini_dat["ini_version"] = $ini_sys["ini_version"]; } if ($mode == 2) { $ini_dat["tag_ini_version"] = $ini_sys["tag_ini_version"]; } $lines = file($fl_sys); $lines2 = array(); foreach($lines as $line) { $parts = parse_ini_string($line); if (empty($parts)) { $lines2 [] = $line; } else { $keys = array_keys($parts); $key = $keys[0]; $val = $ini_dat[$key]; if (!is_numeric($val)) { $val = '"'.$val.'"'; } $lines2[] = $key . " = " . $val. "\n"; } } $ret = file_put_contents($fl_usr.".new",$lines2); if ($ret === false ) return false; $ret = rf_copy($fl_usr,$fl_usr.".bak"); if ($ret === false ) return false; $ret = rf_move($fl_usr.".new",$fl_usr); if ($ret === false ) { $ret = rf_copy($fl_usr.".bak",$fl_usr); return false; } return true; } function rfmenu_update_para($mode,$file) { global $scrdir; global $defdir; global $cfgdir; global $DS; global $ui_mode; $fl_sys = $scrdir.$file; $fl_usr = $cfgdir.$file; $fl_dif = $cfgdir.$file.".dif"; if (($ini_sys = @parse_ini_file($fl_sys)) === false) { if ($ui_mode == 2) return false; echo_msg(2, "$fl_sys parse error"); echo_msg(2, "システム設定に誤りがあります。"); return false; } if (($ini_usr = @parse_ini_file($fl_usr)) === false) { if ($ui_mode == 2) return false; echo_msg(2, "$fl_usr parse error"); echo_msg(2, "ユーザ設定に誤りがあります。"); echo_msg(2, "該当ファイルを修正または削除してください。"); return false; } $ret = rf_update_inifile($mode,$fl_sys,$fl_usr,$ini_sys,$ini_usr); return $ret; } function rfmenu_update_para_all() { global $defkwdir; global $kwdir; global $program_kw; $lists = [ [1,"rfriends.ini", "パラメータ"], [2,"rfriends_tag.ini","タグ" ], [3,"usrdir.ini", "ユーザdir" ], [4,"premium.ini", "プレミアム"], [5,"sendmail.ini", "メール" ], [6,"rfplay.ini", "パラメータ"] ]; echo_scr(2,""); echo_scr(2, "設定ファイルを最新にします。"); echo_scr(2, "ただし、現在のユーザ設定値は保持します。"); echo_scr(2, ""); foreach($lists as $list) { $no = $list[0]; $file = $list[1]; $name = rf_strimwidth($list[2] . str_repeat(" ",12),0,12); $txt = sprintf("・ %s(%s)",$name,$file); echo_scr(2,$txt); } echo_scr(2, ""); $ans = echo_yesno(2, "実行しますか? (y/N): "); if ($ans == "y" || $ans == "Y") { echo_scr(2, ""); foreach($lists as $list) { $no = $list[0]; $file = $list[1]; $name = rf_strimwidth($list[2] . str_repeat(" ",12),0,12); $txt = sprintf("・ %s(%s)",$name,$file); echo_scr(2,$txt); $ret = rfmenu_update_para($no,$file); if ($ret === false) { echo_scr(2,"　　更新に失敗しました。"); } else{ echo_scr(2,"　　更新しました。"); } } } $defpgm = $defkwdir.$program_kw; $pgm = $kwdir.$program_kw; $ft_defpgm = filemtime($defpgm); $ft_pgm = filemtime($pgm); if ($ft_defpgm > $ft_pgm) { echo_scr(2, ""); echo_scr(2, "重複番組設定の更新データがあります。"); echo_scr(2, "現在のデータ : ".date ("Y/m/d H:i:s", $ft_pgm)); echo_scr(2, "更新データ   : ".date ("Y/m/d H:i:s", $ft_defpgm)); echo_scr(2, ""); $ans = echo_yesno(2, "更新しますか? (y/N): "); if ($ans == "y" || $ans == "Y") { echo_scr(2, ""); $ret = rf_move($pgm,$pgm.".bak"); if ($ret === false) { echo_scr(2,"　　更新に失敗しました。"); } else{ echo_scr(2,"　　更新しました。"); } } } } function rfmenu_update_para_all_auto() { global $defkwdir; global $kwdir; global $program_kw; $lists = [ [1,"rfriends.ini", "パラメータ"], [2,"rfriends_tag.ini","タグ" ], [3,"usrdir.ini", "ユーザdir" ], [4,"premium.ini", "プレミアム"], [5,"sendmail.ini", "メール" ], [6,"rfplay.ini", "パラメータ"] ]; echo_scr(2,""); echo_scr(2, "設定ファイルを最新にします。"); echo_scr(2, "ただし、現在のユーザ設定値は保持します。"); echo_scr(2, ""); foreach($lists as $list) { $no = $list[0]; $file = $list[1]; $name = rf_strimwidth($list[2] . str_repeat(" ",12),0,12); $txt = sprintf("%s(%s)",$name,$file); $ret = rfmenu_update_para($no,$file); if ($ret === false) { echo_scr(2,"・失敗 : ".$txt); } else{ echo_scr(2,"・成功 : ".$txt); } } $defpgm = $defkwdir.$program_kw; $pgm = $kwdir.$program_kw; $ft_defpgm = filemtime($defpgm); $ft_pgm = filemtime($pgm); if ($ft_defpgm > $ft_pgm) { echo_scr(2, ""); echo_scr(2, "重複番組設定の更新データがあります。"); echo_scr(2, "現在のデータ : ".date ("Y/m/d H:i:s", $ft_pgm)); echo_scr(2, "更新データ   : ".date ("Y/m/d H:i:s", $ft_defpgm)); echo_scr(2, ""); $ret = rf_move($pgm,$pgm.".bak"); if ($ret === false) { echo_scr(2,"更新に失敗しました。"); } else{ echo_scr(2,"更新しました。"); } } } 