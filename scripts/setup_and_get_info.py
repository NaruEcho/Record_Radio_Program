import json
import requests
import sys
import os
import re
from zoneinfo import ZoneInfo
from datetime import datetime
from collections import OrderedDict 

# GITHUB_WORKSPACE 環境変数　ルートデディレクトリ
workspace = os.getenv('GITHUB_WORKSPACE', None)

def load_json(file_path):
    if os.path.exists(file_path):
        with open(file_path, 'r', encoding='utf-8') as file:
            return json.load(file)
    return {} # ファイルが存在しない場合は空の辞書を返す

# JSONに新しいエントリを追加する関数
def add_entry(json_data, date, entry):
    json_data[date] = entry
    return json_data

def save_json(file_path, json_data):
    with open(file_path, 'w', encoding='utf-8') as file:
        json.dump(json_data, file, ensure_ascii=False, indent=4)

def get_extract_broadcast_date(onair_date):
    try:
        # 正規表現で「年」と「月」と「日」を抽出する
        match = re.search(r"(\d{4})年(\d{1,2})月(\d{1,2})日", onair_date)
        if match:
            year = int(match.group(1))
            month = int(match.group(2))
            day = int(match.group(3))
            # datetime形式で返す
            return datetime(year, month, day)
        else:
            # 「年」が与えられていない場合
            match = re.search(r"(\d{1,2})月(\d{1,2})日", onair_date)
            if match:
                month = int(match.group(1))
                day = int(match.group(2))
                # 現在の年と月を取得
                now = datetime.now(ZoneInfo("Asia/Tokyo"))
                current_year = now.year
                # 放送日が現在の月より大きい場合は前年の放送と推定する
                if month > now.month:
                    year = current_year - 1
                else:
                    year = current_year
                # datetime形式で返す
                return datetime(year, month, day)
            return None
    except ValueError:
        return None

def read_programs():
    try:
        # JSONファイルの読み込み
        courses_json = load_json(f"{workspace}/content/courses-all.json")
        # ダウンロードする番組を配列形式で受け取る
        with open(f"{workspace}/content/programs.txt", 'r', encoding='utf-8') as file:
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
    back_array = []
    jsonData = read_programs()
    if jsonData is None:
        print("Error: Check the content/programs.txt")
        return None
    try:
        for info in jsonData:
            url = info["url"]
            try:
                response = requests.get(url)
                response.raise_for_status() # HTTPエラーが発生した場合は例外をスロー
            except requests.exceptions.RequestException as e:
                print(f"Error fetching {info['title']}: {e}")
            # JSONデータをパース
            data = response.json()
            folder_path_for_command = f"content/{info['folder_title']}"
            folder_path = f"{workspace}/{folder_path_for_command}"
            info_path = os.path.join(folder_path, "info.json")
            thumbnail_path = os.path.join(folder_path, "thumbnail.jpg")
            if not os.path.exists(info_path) or not os.path.exists(thumbnail_path):
                print(f"{info['title']}のinfoファイルを作成します")
                # 番組説明
                series_description = data.get('series_description', '説明がありません')
                series_url = data.get('series_url', '見つかりません')
                radio_broadcast = data.get('radio_broadcast', '配信されていません')
                schedule = data.get('schedule', '配信時間が設定されていません')
                thumbnail_url = data.get('thumbnail_url', None)
                program_data = OrderedDict([
                    ("title", info['title']),
                    ("radio_broadcast", radio_broadcast),
                    ("schedule", schedule),
                    ("series_url", series_url),
                    ("series_description", series_description)
                ])
                filtered_data = OrderedDict((k, v) for k, v in program_data.items() if v is not None)
                os.makedirs(folder_path, exist_ok=True)
                save_json(info_path, filtered_data)
                print(f"{info['title']}のinfoファイルを作成しました")
                try:
                    if thumbnail_url is not None:
                        print("save the thumbnail image")
                        img_response = requests.get(thumbnail_url)
                        img_response.raise_for_status() # エラーがあれば例外をスロー
                        # 画像を保存
                        with open(thumbnail_path, "wb") as file:
                            file.write(img_response.content)
                        print("saved the thumbnail image")
                except requests.exceptions.RequestException as e:
                    print(f"Error downloading image: {e}")
            episodes = data.get('episodes', None)
            if episodes is None:
                print(f"Error: {info['title']}/episodes not found")
                return None
            for episode in episodes:
                streaming_url = episode.get('stream_url', None)
                onair_date = episode.get('onair_date', None)
                closed_date = episode.get('closed_at', None)
                title_sub = episode.get('program_sub_title', None)
                program_title = episode.get('program_title', None)
                if onair_date is not None:
                    extract_broadcast_date = get_extract_broadcast_date(onair_date)
                    if extract_broadcast_date is not None:
                        date_key = extract_broadcast_date.strftime("%Y-%m-%d")
                        now_month_folder_path = os.path.join(folder_path, str(extract_broadcast_date.year).zfill(4), str(extract_broadcast_date.month).zfill(2))
                        audio_path = os.path.join(now_month_folder_path, str(extract_broadcast_date.day).zfill(2)) + ".mp3"
                        os.makedirs(now_month_folder_path, exist_ok=True)
                        if not os.path.exists(audio_path):
                            download_date = datetime.now(ZoneInfo("Asia/Tokyo")).strftime("%Y-%m-%d-%H-%M-%S")
                            onair_date_with_year = str(extract_broadcast_date.year) + "年" + onair_date
                            audio_path_for_command_line = os.path.join(folder_path_for_command, str(extract_broadcast_date.year).zfill(4), str(extract_broadcast_date.month).zfill(2), str(extract_broadcast_date.day).zfill(2)) + ".mp3"
                            broadcast_data = OrderedDict([
                                ("title", program_title),
                                ("sub_title", title_sub),
                                ("onair_date", onair_date_with_year),
                                ("closed_date", closed_date),
                                ("streaming_url", streaming_url),
                                ("audio_path", audio_path),
                                ("download_date", download_date)
                            ])
                            broadcast_filtered_data = OrderedDict((k, v) for k, v in broadcast_data.items() if v is not None)
                            broadcast_json_path = os.path.join(now_month_folder_path, "broadcast_info.json")
                            broadcast_json_data = load_json(broadcast_json_path)
                            # 日付をキーにしてJSONデータに追加
                            broadcast_json_data[date_key] = broadcast_filtered_data
                            save_json(broadcast_json_path, broadcast_json_data)
                            back_array.append(f"streaming_url:{streaming_url}")
                            back_array.append(f"audio_path:{audio_path_for_command_line}")
        return back_array
    except Exception as e:
        print(f"Error: {e}")
        return None
            
if __name__ == "__main__":
    # GITHUB_WORKSPACE 環境変数　ルートデディレクトリ
    if workspace is None:
        print("root directory not found")
    else:
        temp_path = f"{workspace}/scripts/temp.json"
        print("root directory found")
        print(f"WORKSPACE: {workspace}")
        pass_array = get_streaming_url()
        if pass_array is not None:
            print(pass_array)
            with open("scripts/temp.txt", "w") as file:
                file.write("\n".join(pass_array))
            print("saved download target info text")
        else:
            pass_array = []
            print("empty array")
            with open("scripts/temp.txt", "w") as file:
                file.write("\n".join(pass_array))
