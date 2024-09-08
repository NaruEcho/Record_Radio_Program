#!/bin/bash

# Gitユーザー設定
git config --global user.name "actions-user"
git config --global user.email "65916846+actions-user@users.noreply.github.com"

# contentフォルダの変更を検出
if [[ `git status --porcelain content` ]]; then
  echo "Changes detected in content folder. Committing changes..."
  
  # 変更をステージングしてコミット
  git add content
  git commit -m "Auto-update content folder"
  
  # 変更をプッシュ
  git push origin main  # 必要に応じてブランチ名を変更
else
  echo "No changes detected in content folder."
fi
