#!/bin/bash

# Gitユーザー設定
git config --global user.name "actions-user"
git config --global user.email "65916846+actions-user@users.noreply.github.com"
git config --global http.postBuffer 524288000
git config --global http.maxRequestBuffer 524288000

# Git Large File Storage (LFS) のセットアップ
if ! git lfs > /dev/null 2>&1; then
  echo "Git LFS is not installed. Please install Git LFS to track large files."
  exit 1
fi
git lfs install

# Large file types (.mp3) をLFSで管理
git lfs track "*.mp3"
git add .gitattributes  # LFSトラッキング情報の追加

# contentフォルダの変更を検出
if [[ `git status --porcelain content` ]]; then
  echo "Changes detected in content folder. Committing changes..."

  # 変更をステージングしてコミット
  git add content
  git commit -m "Auto-update audio file in content folder"

  # 変更をプッシュ
  git push origin main || {
    echo "Error: Failed to push changes. Please check your network connection or remote settings."
    exit 1
  }
else
  echo "No changes detected in content folder."
fi