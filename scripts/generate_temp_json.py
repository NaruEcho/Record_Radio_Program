import json
import requests
import sys
import os

# GITHUB_WORKSPACE 環境変数　ルートデディレクトリ
workspace = os.getenv('GITHUB_WORKSPACE', '/default/path')

def read_programs():
    try:
        # JSONファイルの読み込み
        with open(f"{workspace}/content/courses-all.json.json", 'r', encoding='utf-8') as file:
            courses_json = json.load(file)       
        # ダウンロードする番組を配列形式で受け取る
        with open('content/programs.txt', 'r', encoding='utf-8') as file:
            programs_list = file.read().splitlines()      
        output_list = []
        for program in courses_json['programs']:
            if program['title'] in programs_list:
                url = courses_json['url_json'].format(program['dir'], program['sub'])
                output_list.append({
                    "title": program['title'],
                    "url": url,
                    "folder_title": program['folder_title']
                })  
        return output_list
        # json形式で出力
        output_json = json.dumps(output_list, ensure_ascii=False, indent=2)
        print(output_json)
        # ファイル保存
        with open("temp.json", "w", encoding="utf-8") as f:
            f.write(output_json)
    except Exception as e:
        print(f"Error: {e}")
        return None

def get_streaming_url():
    jsonData = read_programs()
    if jsonData is None:
        print("Error check the content/programs.txt")
        sys.exit(1)
    try:
        for info in jsonData:
            url = info["url"]
            response.requests.get(url)
            response.raise_for_status() # HTTPエラーが発生した場合は例外をスロー
            # JSONデータをパース
            data = response.json()
            if not os.path.exists(f"{workspace}/content/{info["title"]}/info.json"):
                print(f"{info["title"]}のinfoファイルを作成します")
                # 番組説明
                series_description = data.get('series_description', '説明はありません')
            episodes = data.get('episodes', [])
            for episode in episodes:
                streaming_url = episode.get('stream_url', 'URLがありません')
                onair_date = episode.get('onair_date', '配信日がありません')
            
            
