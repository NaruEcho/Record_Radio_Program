<?php
 $ex_type = $ex_timefree; ht_subtitle($subno,""); switch ($subno) { case "0301": $val0 = ht_set_val($val); $wdat = $val0[0]; if ($sel == 1) { ht_rec_start("rec",$val,$ex_type); } else if ($sel == 2) { ht_live_timefree($wdat,$ex_type); } else if ($sel == 3) { ht_live_server($wdat,$ex_type); } break; case "0302": $val0 = ht_set_val($val); $wdat = $val0[0]; if ($sel == 1) { ht_rec_start("rec",$val,$ex_type); } else if ($sel == 2) { ht_live_timefree($wdat,$ex_type); } else if ($sel == 3) { ht_live_server($wdat,$ex_type); } break; case "0304": ht_play_abort_server("再生"); break; default: ht_development($subno,$val,2); break; } 