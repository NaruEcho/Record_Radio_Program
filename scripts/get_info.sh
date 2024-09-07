#!/bin/bash

# 外部IPアドレスの取得
IP=$(curl -s http://ifconfig.me)
if [ -z "$IP" ]; then
  echo "Failed to retrieve external IP address."
  exit 1
fi
echo "IP Address: $IP"

# IPアドレスに関連する詳細情報の取得
INFO=$(curl -s https://ipinfo.io/${IP}/json)
if [ -z "$INFO" ]; then
  echo "Failed to retrieve IP information."
  exit 1
fi

# 情報のパースと表示
echo "IP Information:"
echo "Country: $(echo "$INFO" | jq -r '.country')"
echo "Region: $(echo "$INFO" | jq -r '.region')"
echo "City: $(echo "$INFO" | jq -r '.city')"
echo "ISP: $(echo "$INFO" | jq -r '.org')"
echo "Location: $(echo "$INFO" | jq -r '.loc')"

# セキュリティ関連情報の確認
echo "Checking security settings..."

# GitHub Actionsでのセキュリティチェックを追加
# 例: 環境変数のセキュリティチェック（環境変数が漏れていないか）
if [ -z "$SECRET_ENV_VARIABLE" ]; then
  echo "No sensitive environment variables found."
else
  echo "Sensitive environment variables detected!"
fi

# IPv6設定確認（VPN利用時に必要な場合）
if curl -s https://api64.ipify.org?format=json | jq -r '.ip' | grep -q ":"; then
  echo "IPv6 is enabled. Consider disabling IPv6 for VPN."
else
  echo "IPv6 is disabled."
fi

exit 0
