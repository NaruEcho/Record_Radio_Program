import json

# JSONファイルの読み込み
with open('content/courses-all.json.json', 'r', encoding='utf-8') as file:
    courses_json = json.load(file)

# ダウンロードする番組を配列形式で受け取る
with open('content/programs.txt', 'r', encoding='utf-8') as file:
    programs_list = file.read().splitlines()

for program in courses_json['programs']:
    if program['title'] in programs_text
