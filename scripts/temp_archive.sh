#!/bin/bash

# 圧縮するフォルダのパス
SOURCE_DIR="temp"
# 圧縮後のファイル名（現在時刻とランダムなUUIDを組み合わせる）
ARCHIVE_NAME="archive_$(date +%Y%m%d_%H%M%S)_$(uuidgen).tar.gz"

# tempフォルダ内のすべてのファイルを圧縮
tar -czf "$ARCHIVE_NAME" -C "$SOURCE_DIR" .

echo "圧縮完了: $ARCHIVE_NAME"
