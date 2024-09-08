#!/bin/bash

# JSONファイルのパス
json_file="scripts/temp.json"

# jqコマンドが必要です
if ! command -v jq &> /dev/null; then
    echo "jq command not found. Install jq like sudo apt-get install -y jq"
    exit 1
fi

# JSONファイルを読み込んで、各エントリに対して処理を実行
jq -c '.[]' "$json_file" | while IFS= read -r entry; do
    url=$(echo "$entry" | jq -r '.streaming_url')
    path=$(echo "$entry" | jq -r '.audio_path')

    if [[ -n "$url" && -n "$path" ]]; then
        # ダブルクオテーションなしで実行する
        echo "ffmpeg -http_seekable 0 -i $url -loglevel quiet -ab 46k -ac 2 -ar 24000 -write_xing 0 $path"
        ffmpeg -http_seekable 0 -i $url -loglevel quiet -ab 46k -ac 2 -ar 24000 -write_xing 0 $path
    else
        echo "Error: URL or PATH not found"
    fi
done
