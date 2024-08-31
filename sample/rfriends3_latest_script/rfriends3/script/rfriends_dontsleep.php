<?php
 function ds_wakeup() { global $base; $exeos = get_rfriends_exeos(); switch ($exeos) { case "WIN": $binpath = $base . "bin\\"; shell_exec($binpath . "rfdontsleep.exe"); break; case "OSX": break; case "LNX": break; default: echo "--- rfriends_exeos is not defined.\n"; exit(1); break; } return; } function ds_kill($pid) { $exeos = get_rfriends_exeos(); switch ($exeos) { case "WIN": $ex = "taskkill /f /pid $pid"; break; case "OSX": case "LNX": $ex = "kill -s 9 $pid"; break; default: echo "--- rfriends_exeos is not defined.\n"; exit(1); break; } return $ex; } function ds_ffmpeg_pid() { global $bindir; global $tmpdir; global $pid_ex_win; global $pid_dlm_win; global $pid_start_win; global $pid_cline_win; global $pid_pid_win; global $pid_cdate_win; $exeos = get_rfriends_exeos(); switch ($exeos) { case "WIN": $ex = $pid_ex_win; $ffmpeg_path = $bindir."ffmpeg"; $dlm = $pid_dlm_win; $start_text = $pid_start_win; $cline_text = $pid_cline_win; $pid_text = $pid_pid_win; $cdate_text = $pid_cdate_win; break; case "OSX": case "LNX": $ex = "ps x -o uid,pid,time,command"; $ffmpeg_path = "ffmpeg"; $dlm = " "; $start_text = "UID"; $cline_text = "COMMAND"; $pid_text = "PID"; $cdate_text = "TIME"; break; default: echo "--- rfriends_exeos is not defined.\n"; exit(1); break; } $pid_data = array(); exec($ex, $data, $ret); $data_n = count_73($data); if ($data_n < 2) { return $pid_data; } $cline_no = -1; $pid_no = -1; $cdate_n = -1; $dat_n = 0; for ($i=0; $i<2; $i++) { $val = $data[$i]; $val = preg_replace('/\s+/', ' ', trim($val)); $dat = explode($dlm, $val); if (count_73($dat) < 3) { continue; } if ($dat[0] != $start_text) { continue; } $n = array_search($cline_text, $dat); if ($n !== false) { $cline_no = $n; } $n = array_search($pid_text, $dat); if ($n !== false) { $pid_no = $n; } $n = array_search($cdate_text, $dat); if ($n !== false) { $cdate_no = $n; } $dat_n = count_73($dat); break; } if ($cline_no == -1 || $pid_no == -1 || $cdate_no == -1) { return $pid_data; } $exeos = get_rfriends_exeos(); $para = array(); foreach ($data as $val) { $val = preg_replace('/\s+/', ' ', trim($val)); $dat = explode($dlm, $val); if (count_73($dat) < $dat_n) { continue; } $pid = $dat[$pid_no]; switch ($exeos) { case "WIN": $cdate = substr($dat[$cdate_no], 0, 14); $cline = $dat[$cline_no]; $cline = preg_replace('/\s+/', ' ', trim($cline)); $para = explode(" ", $cline); break; case "OSX": case "LNX": $cline = $dat[$cline_no]; if ($cline != "ffmpeg") { continue; } $cdate = $dat[$cdate_no]; $para = $dat; break; default: echo "--- rfriends_exeos is not defined.\n"; exit(1); break; } if (count_73($para) < 2) { continue; } $ret = ds_val_search($para, $ffmpeg_path); if ($ret === false) { continue; } $ret = ds_val_search($para, $tmpdir); if ($ret === false) { continue; } $fil = $para[$ret]; $encoder = ds_get_encoder($para); $fmt = "$pid,$encoder,$cdate,$fil"; $pid_data[] = $fmt; } return $pid_data; } function ds_get_encoder($para) { $ret = ds_val_search($para, "encoder"); if ($ret === false) { $encoder = "unknown"; } else { $encoder = $para[$ret]; } return $encoder; } function ds_val_search($data, $val) { for ($i=0; $i<count_73($data); $i++) { $n = strpos($data[$i], $val); if ($n === false) { continue; } return $i; } return false; } function ds_kill_hungup($tim1, $fn, $ex_type) { global $rfriends_task_kill; global $tmpdir; global $DS; global $ex_radiko; global $ex_radiru; global $ex_timefree; global $ex_radiru_vod; global $ex_radiru_gogaku; $pid = -1; $pid_data = ds_ffmpeg_pid(); if (count_73($pid_data) <1) { return $pid; } $fmt_data = array(); foreach ($pid_data as $val) { $task = explode(",", $val); $pi = pathinfo($task[3]); $dir = $pi['dirname']; $fnam = $pi['filename']; if ($dir.$DS != $tmpdir) { continue; } if ($fnam != $fn) { continue; } $pid = $task[0]; ds_write_msg($tim1, "$fnam $pid"); break; } if ($pid != -1) { $ex = ds_kill($pid); ds_write_msg($tim1, "$ex"); if ($rfriends_task_kill == "task_kill") { ; system($ex, $ret); ds_write_msg($tim1, "強制終了しました。 $pid"); if ($ex_type != $ex_timefree && $ex_type != $ex_radiru_vod) { } return; } else { ds_write_msg($tim1, "オプションが有効ではないので強制終了しませんでした。 $pid"); return; } } ds_write_msg($tim1, "対象プロセスが見つかりませんでした。"); } function ds_unlink($src) { if (file_exists($src)) { $ret = @unlink($src); if (!$ret) { echo_prn(2, "unlink error $src"); } } } function ds_rename($src, $dst) { if (file_exists($dst)) { unlink($dst); } if (file_exists($src)) { rf_copy($src, $dst); unlink($src); } } function ds_write_msg($fn, $msg) { $dt = date("Y/m/d H:i:s"); file_put_contents($fn, "$dt $msg".PHP_EOL, FILE_APPEND | LOCK_EX); } function ds_runcheck($run, $fin) { if (file_exists($fin)) { return 2; } if (file_exists($run)) { return 1; } return 0; } require_once("rf_inc.php"); $rfriends_mes = "ラジオ録音ツール"; rf_mkdir($tmpdir); if ($dont_sleep != 1) { exit; } if ($argc != 2) { exit(1); } $p = explode(",", $argv[1]); $ex_type = $p[0]; $fnm = $p[1]; $dur = $p[2]; $tm = $p[3]; $wtime = $p[4]; $tim1 = $tmpdir."$fnm.tim"; $tim2 = $logdir."$fnm"."_tim.log"; $fn = $tmpdir."$fnm.$rec_extension"; $run = $tmpdir."$fnm.run"; $fin = $tmpdir."$fnm.end"; $fn_e = $tmpdir."$fnm.err"; $lmt = $dur + 300; ds_write_msg($tim1, "dontsleep start"); ds_write_msg($tim1, "duration : $dur  watch : $lmt  cycle : $tm"); ds_write_msg($tim1, "standby_time : $standby_time min. standby_time_m : $standby_time_m min. wait_time : $wtime sec"); ds_write_msg($tim1, "$fn"); ds_write_msg($tim1, "$fn_e"); ds_write_msg($tim1, "-----"); if ($ex_type == $ex_timefree || $ex_type == $ex_radiru_vod) { $wtm = $tm; $wcnt = 20; } else { $wtm = rand(10, 15); ds_write_msg($tim1, "sleep $wtm"); sleep($wtm); $wtm = 60; $wmax = $standby_time + 4; $wcnt = (int)floor(($wtime + 30) / 60) + 3; if ($wcnt > $wmax) { $wcnt = $wmax; } } $condition = 0; for ($i=0; $i<$wcnt; $i++) { $condition = ds_runcheck($run, $fin); if ($condition != 0) { break; } ds_wakeup(); ds_write_msg($tim1, "sleep $wtm ($i)"); sleep($wtm); } switch ($condition) { case 2: ds_write_msg($tim1, "already ended"); ds_unlink($tim1); ds_unlink($run); ds_unlink($fin); exit; case 1: ds_write_msg($tim1, "founded $run"); break; case 0: default: ds_write_msg($tim1, "abnormal end (not found $run)"); if ($rfriends_task_kill == "task_kill") { ds_kill_hungup($tim1, $fnm, $ex_type); } ds_rename($tim1, $tim2); ds_unlink($run); ds_unlink($fin); exit; } $oldsz = -1; $oldsz_e = -1; $rty = 0; $condition = 1; while ($lmt >= $tm) { clearstatcache(); $condition = ds_runcheck($run, $fin); if ($condition != 1) { break; } if (file_exists($fn)) { $sz = filesize($fn); } else { $sz = 0; } if (file_exists($fn_e)) { $sz_e = filesize($fn_e); } else { $sz_e = 0; } $fmt = sprintf("%1d %10d %10d", $rty, $sz, $sz_e); ds_write_msg($tim1, $fmt); if ($sz <= $oldsz && $sz_e <= $oldsz_e) { $rty++; if ($rty > 3) { $condition = 3; break; } } else { $rty = 0; } $oldsz = $sz; $oldsz_e = $sz_e; clearstatcache(); ds_wakeup(); sleep(1); ds_wakeup(); sleep($tm); $lmt = $lmt - $tm - 1; } switch ($condition) { case 3: ds_write_msg($tim1, "abnormal end (hungup?)"); if ($rfriends_task_kill == "task_kill") { ds_kill_hungup($tim1, $fnm, $ex_type); } break; case 2: ds_write_msg($tim1, "already ended"); ds_unlink($tim1); break; case 1: ds_write_msg($tim1, "abnormal end (time over)"); if ($rfriends_task_kill == "task_kill") { ds_kill_hungup($tim1, $fnm, $ex_type); } break; case 0: ds_write_msg($tim1, "normal end"); ds_unlink($tim1); break; default: ds_write_msg($tim1, "abnormal end (unknown)"); break; } ds_rename($tim1, $tim2); ds_unlink($run); ds_unlink($fin); exit; 