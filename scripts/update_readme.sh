#!/bin/bash

# ワークフローのステータスを取得
run_id=$(gh run list --limit 1 --json databaseId --jq '.[0].databaseId')
status=$(gh run view $run_id --json status --jq '.status')

# README.mdを更新
echo "Updating README.md with workflow status..."
sed -i "s/Workflow status: .*/Workflow status: $status/" README.md
