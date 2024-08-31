#!/bin/sh
# Rfriends (radiko radiru録音ツール)
cd `dirname $0`
base=`cd ../;pwd`/

ex=rfriends_exec
exno=2
opt=""
exnam="reserve-radiko"
# ------------------------------------ exec
php ${base}script/$ex.php "$exno" "$opt" "$exnam"
# ------------------------------------ 
