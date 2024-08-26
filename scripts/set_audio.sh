#!/bin/bash
set -xeo pipefail

# パッケージの更新とインストール
sudo apt update
sudo apt install -y pulseaudio libportaudio2 dbus-x11 libasound-dev

# PulseAudioサービスの再起動
pulseaudio --kill || true
pulseaudio --start

# 再起動後の遅延を追加
sleep 10

# 仮想オーディオデバイスの設定
pactl load-module module-null-sink sink_name=auto_null sink_properties=device.description="Dummy Output"
pactl load-module module-remap-sink sink_name=auto_null_remap master=auto_null

# デバイスのリスト表示（デバッグ用）
pactl list
