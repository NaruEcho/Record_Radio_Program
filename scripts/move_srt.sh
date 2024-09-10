#!/bin/bash

# temp フォルダー内のすべての .srt ファイルを探索
find ./temp -type f -name "*.srt" | while read -r file; do
  # ファイルのパスから一番上の temp フォルダーを除く
  dest_path=$(echo "$file" | sed 's|^./temp/||')
  
  # 移動先のディレクトリを作成（必要な場合）
  mkdir -p "$(dirname "$dest_path")"
  
  # ファイルをカレントディレクトリに移動
  mv "$file" "./$dest_path"
done
