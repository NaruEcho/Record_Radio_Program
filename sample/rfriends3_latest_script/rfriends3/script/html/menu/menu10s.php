<?php
 $ex_type = ""; ht_subtitle($subno,""); switch ($subno) { case "1001": echo_msg(2,"システムの更新を行います。"); echo_msg(2,""); rf_clear(); if ($val == 0) { echo_msg(2,"更新停止中です。"); break; } $rst = 1; $ret = ht_update("systemupdate",0,$rst,$val,""); break; case "1002": $rpath = $val; $ty = $val2; echo_msg(2,"rpath : $rpath   ty : $ty"); $ret = rfgw_update_bin($rpath, $ty); if ($ret == 0) { echo_msg(2, "更新成功"); } else { echo_msg(2, "更新失敗"); } rf_update_fin_tool(); ht_restart(); break; case "1003": rf_factory_reset(0); ht_restart(); break; default: ht_development($subno,$val,2); break; } 