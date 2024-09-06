import json

# JSONファイルの読み込み
with open('content/courses-all.json.json', 'r', encoding='utf-8') as file:
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

# json形式で出力
output_json = json.dumps(output_list, ensure_ascii=False, indent=2)
print(output_json)

# ファイル保存
with open("temp.json", "w", encoding="utf-8") as f:
    f.write(output_json)
