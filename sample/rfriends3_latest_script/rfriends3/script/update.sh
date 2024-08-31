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
php -v | grep "PHP 7.3" > /dev/null
ans=`echo $?`
if [ $ans = "0" ]; then
	sudo apt-get -y install php7.3-zip
fi
# -----------------------------------------
#  php7.0 zip
# -----------------------------------------
php -v | grep "PHP 7.0"> /dev/null
ans=`echo $?`
if [ $ans = "0" ]; then
	sudo apt-get -y install php7.0-zip
fi
# -----------------------------------------
#  php7.1 zip
# -----------------------------------------
php -v | grep "PHP 7.1"> /dev/null
ans=`echo $?`
if [ $ans = "0" ]; then
	sudo apt-get -y install php7.1-zip
fi
# -----------------------------------------
#  php7.2 zip
# -----------------------------------------
php -v | grep "PHP 7.2"> /dev/null
ans=`echo $?`
if [ $ans = "0" ]; then
	sudo apt-get -y install php7.2-zip
fi
# -----------------------------------------
#echo
#echo finished
# -----------------------------------------
