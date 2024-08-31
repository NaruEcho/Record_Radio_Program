<?php
 $nmax = 80; switch ($sno) { case "s01a": ht_subtitle("070101",""); $ht_jump_btn1_label = "選択"; echo_msg(2,"データは[9-4]各種データ設定で編集してください。"); $pcastfile = $cfgdir.$podcastdat; $ans = rf_pcast_get_preset($pcastfile,1); break; case "s01b": ht_subtitle("070102",""); $fn = $podcastdat; $fl = $cfgdir.$fn; ht_textedit($fl,0,0); break; case "s01c": ht_subtitle("070103",""); echo_msg(2,"ユーザプリセットが出荷時の状態になります。"); echo_msg(2,""); $ans = echo_yesno(2, "実行しますか?"); break; case "s01d": ht_subtitle("070104",""); echo_msg(2,"ユーザプリセットの最新エピソードを一括録音します。"); echo_msg(2,""); $ans = echo_yesno(2, "実行しますか?"); break; case "s02a": ht_subtitle("070201",""); $ht_jump_btn1_label = "選択"; $lists = rf_radiko_station_list(0); $opt = array( "title" => 'Google Podcast(area)', "mode" => 1, "multi" => 0, "confirm" => 0, "ht_selid" => "" ); ht_ask_list($lists,$opt); break; case "s02b": ht_subtitle("070202",""); ht_input("キーワードを入力してください",0); break; case "s02c": ht_subtitle("070203",""); $ht_jump_btn2 = 1; $ht_jump_btn1_label = "選択"; $ht_jump_btn2_label = "preset登録"; $ht_jump_addr = "menu_ss.html"; $pgm = ht_menu_brand(4); $url = $pgm['home']; ht_google_top('Google Podcasts [top] ',0,$url); break; case "s03a": ht_subtitle("070301",""); $ht_jump_btn1_label = "選択"; $pgm = ht_menu_brand(5); $program_list = array(); rf_pcast_get_pgm($program_list,$pgm); break; case "s03b": ht_subtitle("070302",""); if ($headless_browser != 'on') { echo_msg(2,"ヘッドレスブラウザが動作していないので検索できません。(1)"); echo_msg(2,""); break; } if (rfgw_headless_examine() == 0) { echo_msg(2,"ヘッドレスブラウザが動作していないので検索できません。(2)"); echo_msg(2,""); break; } ht_input("キーワードを入力してください",0); break; case "s03c": ht_subtitle("070303",""); $ht_jump_btn1_label = "選択"; $category = array( "ランキング", "音楽・エンタメ", "アニメ・コミック・声優", "ゲーム・動画クリエイター", "ドラマ・朗読", "恋愛", "お笑い", "ライフスタイル", "ブック・カルチャー" ); $lists = array(); foreach($category as $cat) { $lists[] = array('title'=>$cat,'val'=>"$cat"); } $opt = array( "title" => "Audee Podcasts(おすすめ番組)", "mode" => 1, "multi" => 0, "confirm" => 0, "ht_selid" => "" ); ht_ask_list($lists,$opt); break; case "s04": ht_subtitle("0704",""); $ht_jump_btn1_label = "選択"; $pgm = ht_menu_brand(7); $ret = rf_podcast_lfrradio($pgm,$nmax); break; case "s05": ht_subtitle("0705",""); $ht_jump_btn1_label = "選択"; $flists = array(); $flists['user（プリセット）'] = 'user'; $flists['google'] = 'google'; $flists['audee'] = 'audee'; $flists['lfr（ニッポン放送）'] = 'lfr'; $cnt = count_73($flists); ht_sel_menu("Podcast録音データ（$cnt 件）",$flists,0,0); default: $ret = false; break; } 