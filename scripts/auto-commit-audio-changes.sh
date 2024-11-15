#!/bin/bash

# Gitユーザー設定
git config --global user.name "actions-user"
git config --global user.email "65916846+actions-user@users.noreply.github.com"
git config --global http.postBuffer 1048576000  # 1GBまで対応
git config --global http.maxRequestBuffer 1048576000  # 1GBまで対応

# LFSが有効化されているか確認して無効化
if git lfs ls-files > /dev/null 2>&1; then
  echo "LFS is enabled. Disabling LFS..."
  git lfs uninstall
  if [[ -f .gitattributes ]]; then
    echo "Removing LFS settings from .gitattributes..."
    # LFSに関連する行のみ削除
    sed -i.bak '/filter=lfs/d' .gitattributes
  fi
else
  echo "LFS is not enabled. No actions required."
fi

# contentフォルダの変更を検出
if [[ `git status --porcelain content` ]]; then
  echo "Changes detected in content folder. Committing changes..."

  # 変更されたファイルを1つずつ処理
  for file in $(git status --porcelain content | awk '{print $2}'); do
    echo "Processing file: $file"

    # 100MB以上のファイルを検出
    if [[ $(du -b "$file" | awk '{print $1}') -gt 104857600 ]]; then
      echo "Error: The file '$file' exceeds the 100MB GitHub limit. Skipping this file."
      continue
    fi

    # ファイルを個別にステージングしてコミット
    git add "$file"
    git commit -m "Auto-update: $file"
    
    # ファイルをプッシュ
    git push origin main || {
      echo "Error: Failed to push $file. Please check your network connection or remote settings."
      exit 1
    }
  done
else
  echo "No changes detected in content folder."
fi
