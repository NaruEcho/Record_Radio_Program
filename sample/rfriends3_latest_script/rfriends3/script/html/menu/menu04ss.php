<?php
 $ex_type = $ex_radiru; ht_subtitle($subno,""); switch ($subno) { case "0403": if ($sel == 1) { ht_rec_start("rsv",$val,$ex_type); } else { $val0 = ht_set_val($val); ht_live($val0[0],$ex_type); } break; case "0404": if ($sel == 1) { ht_rec_start("rsv",$val,$ex_type); } else { $val0 = ht_set_val($val); ht_live($val0[0],$ex_type); } break; case "0408": ht_play_abort_server("聴取"); break; case "0409": ht_play_abort_server("再生"); break; default: ht_development($subno,$val,2); break; } 