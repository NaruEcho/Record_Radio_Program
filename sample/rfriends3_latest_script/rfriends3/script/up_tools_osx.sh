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

cat $ver
echo ベースディレクトリは　$base です
echo OSは $bit bitsバージョンです

# -----------------------------------------
# プログラムのインストール
# -----------------------------------------
#
echo 時間がかかるかもしれません。
brew update
echo
brew upgrade
echo

which pidof >/dev/null 2>&1
if [ $? = 0 ] ; then
  echo すでにpidof がインストールされています。
else
  echo pidof をインストールします。
  sudo apt-get install atomicparsley
fi
# -----------------------------------------
echo
echo finished
# -----------------------------------------
