# Automated Audio File Saving and JSON File Generation

## Automatically Saved Audio Files

Audio files are automatically saved in the following format:

- **File Format**: `m4a`
- **Filename Format**: `content/ProgramName/Year/Month/Day`

### Example

- `content/ProgramName/2024/09/06.m4a`

## JSON File Generation

A JSON file containing the download timestamps is automatically generated each month. This file helps manage audio files by recording their download times.

### JSON File Structure

The JSON file is saved in the following format:

```json
{
  "year": "2024",
  "nendo": "2024",
  "month": "09",
  "files": [
    {
      "file_name": "content/ProgramName/2024/09/06.m4a",
      "download_time": "2024-09-06T12:34:56Z"
    },
    {
      "file_name": "content/ProgramName/2024/09/07.m4a",
      "download_time": "2024-09-07T14:22:30Z"
    }
  ]
}
```

## Dynamic URL Creation for Automatic Downloads

Based on the following `content/courses-all.json`, URLs are automatically generated for downloading programs:

```json
{
    "year": "2024年度前期",
    "url_json": "https://www.nhk.or.jp/radio-api/app/v1/web/ondemand/series?site_id={}&corner_site_id={}",
    "programs": [
        {"dir": "6805", "sub": "01", "title": "小学生の基礎英語"},
        {"dir": "6806", "sub": "01", "title": "中学生の基礎英語 レベル1"},
        {"dir": "6807", "sub": "01", "title": "中学生の基礎英語 レベル２"},
        {"dir": "6808", "sub": "01", "title": "中高生の基礎英語 in English"},
        {"dir": "0916", "sub": "01", "title": "ラジオ英会話"},
        {"dir": "4121", "sub": "01", "title": "ボキャブライダー"},
        {"dir": "3064", "sub": "01", "title": "エンジョイ・シンプル・イングリッシュ"},
        {"dir": "2331", "sub": "01", "title": "英会話タイムトライアル"},
        {"dir": "7512", "sub": "01", "title": "ニュースで学ぶ「現代英語」"},
        {"dir": "6809", "sub": "01", "title": "ラジオビジネス英語"},
        {"dir": "0915", "sub": "01", "title": "まいにち中国語"},
        {"dir": "6581", "sub": "01", "title": "ステップアップ中国語"},
        {"dir": "0951", "sub": "01", "title": "まいにちハングル講座"},
        {"dir": "6810", "sub": "01", "title": "ステップアップ ハングル講座"},
        {"dir": "0946", "sub": "01", "title": "まいにちイタリア語 初級編・応用編"},
        {"dir": "0943", "sub": "01", "title": "まいにちドイツ語 初級編・応用編"},
        {"dir": "0953", "sub": "01", "title": "まいにちフランス語 初級編・応用編"},
        {"dir": "0948", "sub": "01", "title": "まいにちスペイン語 初級編・応用編"},
        {"dir": "0956", "sub": "01", "title": "まいにちロシア語 初級編・応用編"},
        {"dir": "0937", "sub": "01", "title": "アラビア語講座"},
        {"dir": "2769", "sub": "01", "title": "ポルトガル語講座"},
        {"dir": "EEEE", "sub": "01", "title": "【最後の行】この行は消さない"}
    ]
}
```

The URLs are created dynamically by filling in the `site_id` and `corner_site_id` in the `url_json` field.

## Program Modification

To change the programs that are downloaded, simply edit the `Programs.txt` file. Each program name should be listed on a separate line to ensure proper formatting.

### Example of `content/Programs.txt`

```
小学生の基礎英語
中学生の基礎英語 レベル1
ラジオ英会話
ニュースで学ぶ「現代英語」
```

Make sure each program is on a new line to ensure the system correctly recognizes and downloads the desired content.
