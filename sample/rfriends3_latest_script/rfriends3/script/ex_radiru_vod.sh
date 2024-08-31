#!/bin/sh
# Rfriends (radiko radiru録音ツール)
cd `dirname $0`
base=`cd ../;pwd`/

ex=rfriends_exec
exno=9
opt="8,2,-1,2,"
exnam="radiru_vod"
# ------------------------------------ exec
php ${base}script/$ex.php "$exno" "$opt" "$exnam"
# ------------------------------------ 
