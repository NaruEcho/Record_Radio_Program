import json
import requests
import sys
import os

# GITHUB_WORKSPACE 環境変数　ルートデディレクトリ
workspace = os.getenv('GITHUB_WORKSPACE', '/default/path')

def read_programs():
    try:
        # JSONファイルの読み込み
        with open(f"{workspace}/content/courses-all.json", 'r', encoding='utf-8') as file:
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
            if not os.path.exists(f"{workspace}/content/{info["folder_title"]}/info.json"):
                print(f"{info["title"]}のinfoファイルを作成します")
                # 番組説明
                series_description = data.get('series_description', '説明がありません')
                series_url = data.get('series_url', '見つかりません')
                radio_broadcast = data.get('radio_broadcast', '配信されていません')
                schedule = data.get('schedule', '配信時間が設定されていません')
                program_data = {
                    "title": info["title"],
                    "radio_broadcast": radio_broadcast,
                    "schedule": schedule,
                    "series_url": series_url,
                    "series_description": series_description
                }
                with open(f"{workspace}/content/{info["folder_title"]}/info.json", "w", encoding="utf-8") as json_file:
                    json.dump(program_data, json_file, ensure_ascii=False, indent=4)
                print(f"{info["title"]}のinfoファイルを作成しました")
            episodes = data.get('episodes', None)
            if episodes is None:
                print(f"Error: {info["title"]}/episodes not found")
                sys.exit(1)
            for episode in episodes:
                streaming_url = episode.get('stream_url', False)
                onair_date = episode.get('onair_date', False)
                if streaming_url and onair_date:
                    print("streaming URL and onair date found")
            
            
