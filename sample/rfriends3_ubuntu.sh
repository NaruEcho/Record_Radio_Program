#!/bin/sh
# -----------------------------------------
# install rfriends for ubuntu
# -----------------------------------------
# 3.0 2023/06/23
# 3.1 2023/07/10 remove chromium-browser
# 3.2 2023/07/12 renew
# 3.3 2023/08/04 add p7zip-full
# -----------------------------------------
echo
echo rfriends3 for ubuntu 3.2
echo
# -----------------------------------------
ar=`dpkg --print-architecture`
bit=`getconf LONG_BIT`
echo
echo アーキテクチャは、$ar $bit bits です。
# -----------------------------------------
echo
echo ツールをインストール
echo
#
sudo apt update && sudo apt -y install \
unzip p7zip-full nano vim dnsutils iproute2 tzdata \
at cron wget curl atomicparsley \
php-cli php-xml php-zip php-mbstring php-json php-curl php-intl \
ffmpeg
sudo apt -y install chromium-browser
# -----------------------------------------
#
# 以下はインストールしません
#

#php
#php-openssl
#pulseaudio
#pulseaudio-module-bluetooth
#apache2
#libapache2-mod-php
#samba
# -----------------------------------------
echo
echo rfriendsをインストール
echo
cd ~/

if [ -d ./rfriends3 ]; then
	read -p "すでにrfriendsがインストールされていますが、削除しますか？　(y/N) " ans
	case "$ans" in
  		"y" | "Y" )
			rm -r ./rfriends3
			echo "rfriendsを削除しました。"
			echo 
    			;;
  		* )
			echo 
    			;;
	esac
fi
# -----------------------------------------
echo
echo rfriends3をインストールします。
echo
rm rfriends3_latest_script.zip
wget http://rfriends.s1009.xrea.com/files3/rfriends3_latest_script.zip
unzip -q -o rfriends3_latest_script.zip
# -----------------------------------------
#echo
#echo rfriends3_server起動します。
#echo
#sh rfriends3/rfriends3_server.sh
# -----------------------------------------
# 終了
# -----------------------------------------
echo
echo finished
# -----------------------------------------
