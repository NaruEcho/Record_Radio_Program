#!/bin/sh
# -----------------------------------------
# Rfriends (radiko radiru録音ツール)
# -----------------------------------------
cd `dirname $0`
base=`pwd`/../
ver=$base/_Rfriends2
bit=`getconf LONG_BIT`

if [ ! -f $ver ]; then
	echo $ver ファイルがありません
	echo ディレクトリ構成が間違っています。
	echo
	exit
fi

#cat $ver
#echo ベースディレクトリは　$base です
#echo OSは $bit bitsバージョンです
# -----------------------------------------
#  php7.3 zip
# -----------------------------------------
brew update
echo 時間がかかるので終了までしばらくお待ちください...
brew install php@7.4
brew link php@7.4
brew install atomicparsley
# -----------------------------------------
#echo
#echo finished
# -----------------------------------------
