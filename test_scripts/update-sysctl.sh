#!/bin/bash

# sysctl.confに設定を追加する
echo "net.ipv6.conf.all.disable_ipv6 = 1" >> /etc/sysctl.conf
echo "net.ipv6.conf.default.disable_ipv6 = 1" >> /etc/sysctl.conf
echo "net.ipv6.conf.lo.disable_ipv6 = 1" >> /etc/sysctl.conf
echo "net.ipv6.conf.tun0.disable_ipv6 = 1" >> /etc/sysctl.conf

# 設定をリロードする
sysctl -p
