import json

# JSONファイルの読み込み
with open('content/rename_program.json', 'r', encoding='utf-8') as file:
    program_names = json.load(file)

# 日本語の番組名から対応するアルファベット名を取得
japanese_name = "ラジオ英会話"
alphabet_name = program_names.get(japanese_name)

if alphabet_name:
    print(f"{japanese_name} is called '{alphabet_name}' in alphabet form.")
else:
    print(f"{japanese_name} does not exist in the list.")
