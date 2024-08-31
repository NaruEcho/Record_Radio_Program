#!/bin/sh
# Rfriends (radiko radiru録音ツール)
cd `dirname $0`
base=`cd ../;pwd`/

ex=rfriends_exec
exno=9
opt="9,2,-15,15,"
exnam="radiru_gogaku"
# ------------------------------------ exec
php ${base}script/$ex.php "$exno" "$opt" "$exnam"
# ------------------------------------ 
