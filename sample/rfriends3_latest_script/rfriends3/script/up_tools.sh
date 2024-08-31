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
#sudo apt-get update
echo
which AtomicParsley >/dev/null 2>&1
if [ $? = 0 ] ; then
  echo すでにAtomicParsley がインストールされています。
else
  echo AtomicParsley をインストールします。
  sudo apt-get install atomicparsley
fi
# -----------------------------------------
echo
echo finished
# -----------------------------------------
