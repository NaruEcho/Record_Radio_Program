<?php
 require_once("rf_inc.php"); require_once("rf_gdrive.php"); require_once("rf_menu_sub.php"); $rfriends_mes = "ラジオ録音ツール"; global $bindir; global $tmpdir; global $dir_log; global $dir_radiko; global $dir_radiru; global $dir_timefree; global $dir_radiru_vod; global $dir_radiru_gogaku; global $dir_kw; global $dir_backup; $gdcmd = "gdrive"; $ret = rfgw_gdrive_file($gdcmd); $fl = $ret[0]; $ot = $ret[1]; echo_msg(2, ""); echo_msg(2, "gdrive install utility ver. 1.00"); echo_msg(2, ""); if (!file_exists($ot)) { echo_msg(2, ""); echo_msg(2, "gdrive がインストールされていません。"); echo_msg(2, "gdrive をダウンロードします。"); echo_msg(2, ""); echo "実行しますか? (y/N): "; $ans = read_stdin(); echo_msg(2, ""); if ($ans != "y" && $ans != "Y") { echo_msg(2, "終了します。"); exit; } $ret = rfgw_gdrive_download($fl, $gdcmd); if ($ret === false) { echo_msg(2, "ダウンロードに失敗しました。"); echo_msg(2, "終了します。"); exit; } $ret = rfgw_gdrive_rename($tmpdir.$gdcmd, $ot); if ($ret === false) { echo_msg(2, "インストールに失敗しました。"); echo_msg(2, "終了します。"); exit; } echo_msg(2, "gdrive をダウンロード、インストールしました。"); } else { echo_msg(2, "gdrive はすでにインストールされています。"); } $ret = gdrive_check(); if ($ret === true) { echo_msg(2, ""); echo_msg(2, "gdrive はすでに認証されています。"); echo_msg(2, ""); echo "gdrive の認証を解除しますか? (y/N): "; $ans = read_stdin(); echo_msg(2, ""); if ($ans == "y" || $ans == "Y") { gdrive_cancel(); echo_msg(2, ""); echo_msg(2, "gdrive の認証解除に成功しました。"); echo_msg(2, "終了します。"); exit; } echo_msg(2, "gdrive は認証されたままの状態です。"); } else { echo_msg(2, ""); echo_msg(2, "gdrive の認証を行います。"); echo_msg(2, ""); echo "実行しますか? (y/N): "; $ans = read_stdin(); echo_msg(2, ""); if ($ans != "y" && $ans != "Y") { echo_msg(2, "終了します。"); exit; } $dir = $bindir; $exec_cmd = $dir."gdrive list -m 1"; $ret = passthru($exec_cmd); $ret = gdrive_check(); if ($ret === false) { echo_msg(2, ""); echo_msg(2, "gdrive の認証に失敗しました。"); exit; } echo_msg(2, ""); echo_msg(2, "gdrive の認証に成功しました。"); } echo_msg(2, ""); echo_msg(2, "gdrive にアップロード用ディレクトリを作成します。"); echo_msg(2, ""); echo "実行しますか? (y/N): "; $ans = read_stdin(); echo_msg(2, ""); if ($ans != "y" && $ans != "Y") { echo_msg(2, "終了します。"); exit; } echo_msg(2, ""); echo "ディレクトリ名 : "; $usr3 = read_stdin(); echo_msg(2, ""); $rid = gdrive_get_id("", $usr3); if ($rid != "") { echo_msg(2, "$usr3 はすでに存在します。"); echo_msg(2, "ディレクトリを確認してください。"); echo_msg(2, "終了します。"); exit; } echo_msg(2, "Goodle Drive に以下のディレクトリを作成します。"); echo_msg(2, ""); echo_msg(2, "$usr3"); echo_msg(2, "$usr3 > $dir_radiko"); echo_msg(2, "$usr3 > $dir_radiru"); echo_msg(2, "$usr3 > $dir_timefree"); echo_msg(2, "$usr3 > $dir_radiru_vod"); echo_msg(2, "$usr3 > $dir_radiru_gogaku"); echo_msg(2, ""); echo "実行しますか? (y/N): "; $ans = read_stdin(); echo_msg(2, ""); if ($ans != "y" && $ans != "Y") { echo_msg(2, "終了します。"); exit; } $ret = gdrive_make_dir("", $usr3); if ($ret === false) { echo_msg(2, "終了します。"); exit; } $rid = gdrive_get_id("", $usr3); if ($rid === false) { echo_msg(2, "終了します。"); exit; } $ret = gdrive_make_dir($rid, "$dir_radiko"); if ($ret === false) { echo_msg(2, "終了します。"); exit; } $ret = gdrive_make_dir($rid, "$dir_radiru"); if ($ret === false) { echo_msg(2, "終了します。"); exit; } $ret = gdrive_make_dir($rid, "$dir_timefree"); if ($ret === false) { echo_msg(2, "終了します。"); exit; } $ret = gdrive_make_dir($rid, "$dir_radiru_vod"); if ($ret === false) { echo_msg(2, "終了します。"); exit; } $ret = gdrive_make_dir($rid, "$dir_radiru_gogaku"); if ($ret === false) { echo_msg(2, "終了します。"); exit; } echo_msg(2, ""); echo_msg(2, "gdrive にアップロード用ディレクトリを作成しました。"); echo_msg(2, ""); echo_msg(2, "ディレクトリが重複していないか確認してください。"); echo_msg(2, "rftrans パラメータを設定してください。"); echo_msg(2, ""); 