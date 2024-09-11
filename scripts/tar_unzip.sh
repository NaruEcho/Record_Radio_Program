#!/bin/bash

# 圧縮ファイルが保存されているフォルダ
SOURCE_DIR="downloaded-artifacts"
# 圧縮ファイルを移動するフォルダ
DEST_DIR="./"

# 圧縮ファイルを移動して展開する
for subfolder in "$SOURCE_DIR"/*; do
  if [ -d "$subfolder" ]; then
  for file in "$subfolder"/*.tar.gz; do
    if [ -f "$file" ]; then
      # ファイルを移動
      mv "$file" "$DEST_DIR"
      # 移動後、展開
      tar -xzf "$DEST_DIR/$(basename "$file")" -C "$DEST_DIR"
      # 展開が終わったら圧縮ファイルを削除
      rm "$DEST_DIR/$(basename "$file")"   
      echo "展開完了: $file"
    fi
  done
  fi
done
