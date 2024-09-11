#!/bin/bash

# 圧縮するフォルダのパス
SOURCE_DIR="temp"
# 圧縮後のファイル名（現在時刻とランダムなUUIDを組み合わせる）
ARCHIVE_NAME="archive_$(date +%Y%m%d_%H%M%S)_$(uuidgen).tar.gz"
# 保存先のフォルダ
DEST_DIR="archived"

# 保存先のフォルダが存在しない場合は作成
mkdir -p "$DEST_DIR"

# tempフォルダ内のすべてのファイルを圧縮
tar -czf "$DEST_DIR/$ARCHIVE_NAME" -C "$SOURCE_DIR" .

echo "圧縮完了: $DEST_DIR/$ARCHIVE_NAME"
